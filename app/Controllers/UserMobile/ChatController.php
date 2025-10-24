<?php
namespace App\Controllers\UserMobile;

use App\Controllers\BaseController;
use App\Models\UserMobile\ChatModel;
use App\Models\UserMobile\MessageModel;
use App\Models\UserMobile\ResidenceModel;
class ChatController extends BaseController
{
    protected $chatModel;
    protected $messageModel;

    public function __construct()
    {
        $this->chatModel = new ChatModel();
        $this->messageModel = new MessageModel();
        $this->residenceModel = new ResidenceModel();
    }
    public function getContactsByFvId()
    {
        $input = $this->request->getJSON(true);
        $fv_id = $input['fv_id'] ?? $this->request->getPost('fv_id');

        if (!$fv_id) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'fv_id is required'
            ]);
        }

        try {
            $db = \Config\Database::connect();

            // ----- Residents (via user_residences) -----
            $residents = $db->table('app_users as u')
                ->select("u.uid as id, u.name, u.profile_photo, 'resident' as type, 1 as user_type")
                ->join('user_residences as ur', 'ur.us_id = u.uid')
                ->where('ur.fv_id', $fv_id)
                ->get()
                ->getResult();

            // ----- Security (via flats_security mapping) -----
            $security = $db->table('security as s')
                ->select("s.sc_id as id, s.security_name as name, '' as profile_photo, 'security' as type, 2 as user_type")
                ->join('flats_security as fs', 'fs.sc_id = s.sc_id')
                ->where('fs.fv_id', $fv_id)
                ->get()
                ->getResult();

            // Merge residents + security
            $contacts = array_merge($residents, $security);

            return $this->response->setJSON([
                'status' => true,
                'fv_id' => $fv_id,
                'contacts' => $contacts
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }
    }

    public function createOrSendMessage()
    {
        $input = $this->request->getJSON(true);

        $sender_id = $input['sender_id'] ?? null;
        $receiver_id = $input['receiver_id'] ?? null;
        $sender_type = $input['sender_type'] ?? null; // 1 = Resident, 2 = Security
        $receiver_type = $input['receiver_type'] ?? null; // 1 = Resident, 2 = Security
        $message = $input['message'] ?? '';

        // Validate required fields
        if (empty($sender_id) || empty($receiver_id) || empty($message) || empty($sender_type) || empty($receiver_type)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'sender_id, receiver_id, sender_type, receiver_type, and message are required'
            ]);
        }

        // Get sender's fv_id from user_residences
        $flats = $this->residenceModel->getOrgsList($sender_id);
        $flat = $flats[0] ?? null;
        if (!$flat) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Sender is not assigned to any flat'
            ]);
        }

        $fv_id = $flat->fv_id;
        if ($sender_id < $receiver_id) {
            $small_id = $sender_id;
            $small_type = $sender_type;
            $large_id = $receiver_id;
            $large_type = $receiver_type;
        } else {
            $small_id = $receiver_id;
            $small_type = $receiver_type;
            $large_id = $sender_id;
            $large_type = $sender_type;
        }

        // Create unique key using sorted IDs
        $unique_key = $small_id . '_' . $small_type . '-' . $large_id . '_' . $large_type;

        // Check if chat exists
        $chat = $this->chatModel->where('unique_key', $unique_key)->first();

        if (!$chat) {
            // Create new chat
            $chatId = $this->chatModel->insert([
                'unique_key' => $unique_key,
                'fv_id' => $fv_id,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            ]);
            $chat = $this->chatModel->find($chatId);
        }

        // Send message
        $msgId = $this->messageModel->insert([
            'chat_id' => $chat['chat_id'],
            'fv_id' => $fv_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'sender_type' => $sender_type,
            'receiver_type' => $receiver_type,
            'message' => $message,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $msgDetails = $this->messageModel->find($msgId);

        return $this->response->setJSON([
            'status' => true,
            'chat' => $chat,
            'message_details' => $msgDetails,
            'message' => 'Chat created and message sent successfully'
        ]);
    }

    public function getChatsByUser()
    {
        $input = $this->request->getJSON(true);
        $user_id = $input['user_id'] ?? null;
        $user_type = $input['user_type'] ?? null; // 1=resident, 2=security

        if (!$user_id || !$user_type) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'user_id and user_type are required'
            ]);
        }

        try {
            $db = \Config\Database::connect();

            // Search for chats where user is a participant
            $search_key_part = $user_id . '_' . $user_type;
            $chats = $this->chatModel
                ->where('status', 1)
                ->groupStart()
                ->like('unique_key', $search_key_part . '-') // user first
                ->orLike('unique_key', '-' . $search_key_part) // user second
                ->groupEnd()
                ->orderBy('chat_id', 'DESC')
                ->findAll();

            $result = [];

            foreach ($chats as $chat) {
                if (!isset($chat['unique_key']))
                    continue; // safety check

                // Extract the other participant
                $parts = explode('-', $chat['unique_key']);
                if (count($parts) != 2)
                    continue; // safety check

                $first = explode('_', $parts[0]);
                $second = explode('_', $parts[1]);

                if (count($first) != 2 || count($second) != 2)
                    continue;

                if ($first[0] == $user_id && $first[1] == $user_type) {
                    $other_id = $second[0];
                    $other_type = $second[1];
                } else {
                    $other_id = $first[0];
                    $other_type = $first[1];
                }

                // Get contact info safely
                if ($other_type == 1) {
                    $contact = $db->table('app_users')
                        ->select('uid as id, name, profile_photo')
                        ->where('uid', $other_id)
                        ->get()
                        ->getRow();
                    if (!$contact)
                        continue;
                    $contact->type = 'resident';
                    $contact->user_type = 1;
                } else {
                    $contact = $db->table('security')
                        ->select('sc_id as id, security_name as name')
                        ->where('sc_id', $other_id)
                        ->get()
                        ->getRow();
                    if (!$contact)
                        continue;
                    $contact->profile_photo = '';
                    $contact->type = 'security';
                    $contact->user_type = 2;
                }

                // Get last message
                $lastMessage = $this->messageModel
                    ->where('chat_id', $chat['chat_id'])
                    ->orderBy('created_at', 'DESC')
                    ->first();

                $result[] = [
                    'chat_id' => $chat['chat_id'],
                    'unique_key' => $chat['unique_key'],
                    'last_message' => $lastMessage['message'] ?? '',
                    'last_message_time' => $lastMessage['created_at'] ?? null,
                    'contact' => [
                        'id' => $contact->id,
                        'name' => $contact->name,
                        'profile_photo' => $contact->profile_photo ?? '',
                        'type' => $contact->type,
                        'user_type' => $contact->user_type
                    ]
                ];
            }

            return $this->response->setJSON([
                'status' => true,
                'user_id' => $user_id,
                'chats' => $result
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }
    }

    // Get messages by chat_id
    public function getMessagesByChat()
    {
        $input = $this->request->getJSON(true) ?? [];

        $user_id = $input['user_id'] ?? null;
        $user_type = $input['user_type'] ?? null; // 1 = resident, 2 = security
        $chat_id = $input['chat_id'] ?? null;

        if (isset($input['page_index'])) {
            $page_index = (int) $input['page_index'];
        } elseif (isset($input['pageIndex'])) {
            $page_index = (int) $input['pageIndex'] + 1;
        } else {
            $page_index = 1;
        }

        $page_index = max(1, $page_index);

        // page_size or pageSize
        $page_size = (int) ($input['page_size'] ?? $input['pageSize'] ?? 10);
        $page_size = max(1, $page_size);

        if (!$user_id || !$user_type || !$chat_id) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'user_id, user_type, and chat_id are required'
            ]);
        }
            // Check if chat exists
            $chat = $this->chatModel->where('chat_id', $chat_id)->first();
            if (!$chat) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Chat not found'
                ]);
            }

            // Verify that user is a participant
            if (strpos($chat['unique_key'], $user_id . '_' . $user_type) === false) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'You are not a participant in this chat'
                ]);
            }

            // compute offset (page_index is 1-based)
            $offset = ($page_index - 1) * $page_size;
            $messageBuilder = $this->messageModel->builder();
            $total = (int) $messageBuilder
                ->where('chat_id', $chat_id)
                ->countAllResults();
            $messageBuilder2 = $this->messageModel->builder();
            $rows = $messageBuilder2
                ->where('chat_id', $chat_id)
                ->orderBy('created_at', 'DESC')
                ->orderBy('msg_id', 'DESC') // tie-breaker
                ->limit($page_size, $offset)
                ->get()
                ->getResultArray();
            $messages = array_reverse($rows);

            // Pagination meta
            $total_pages = (int) ceil($total / $page_size);
            $has_more = $page_index < $total_pages;

            return $this->response->setJSON([
                'status' => true,
                'chat_id' => $chat_id,
                'page_index' => $page_index,
                'page_size' => $page_size,
                'total_messages' => $total,
                'total_pages' => $total_pages,
                'has_more' => $has_more,
                'messages' => $messages
            ]);
       
    }
    // Mark messages as read
    public function markRead()
    {
        $input = $this->request->getJSON(true);
        $chatId = $input['chat_id'] ?? null;
        $receiverId = $input['receiver_id'] ?? null;

        if (empty($chatId) || empty($receiverId)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'chat_id and receiver_id are required'
            ]);
        }

        // Check if messages exist for this chat and receiver
        $messagesCount = $this->messageModel
            ->where('chat_id', $chatId)
            ->where('receiver_id', $receiverId)
            ->where('status !=', 3) // only mark unread/delivered
            ->countAllResults(false);

        if ($messagesCount == 0) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No messages found to mark as read'
            ]);
        }

        // Update messages to read
        $this->messageModel
            ->where('chat_id', $chatId)
            ->where('receiver_id', $receiverId)
            ->where('status !=', 3)
            ->set(['status' => 3])
            ->update();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Messages marked as read'
        ]);
    }
}
