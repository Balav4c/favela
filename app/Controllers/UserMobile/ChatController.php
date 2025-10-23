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

    public function createOrSendMessage()
    {
        $input = $this->request->getJSON(true);

        $sender_id = $input['sender_id'] ?? null;
        $receiver_id = $input['receiver_id'] ?? null;
        $message = $input['message'] ?? '';

        if (empty($sender_id) || empty($receiver_id) || empty($message)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'sender_id, receiver_id, and message are required'
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

        // Generate unique key for chat
        $unique_key = 'chat_' . $fv_id . '_' . min($sender_id, $receiver_id) . '_' . max($sender_id, $receiver_id);

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

    // Get all chats
    public function getChats()
    {
        // Read JSON body or GET query
        $input = $this->request->getJSON(true);
        $chatId = $input['chat_id'] ?? $this->request->getGet('chat_id');

        if (!empty($chatId)) {
            // Return specific chat
            $chat = $this->chatModel->where('chat_id', $chatId)->where('status', 1)->first();

            if ($chat) {
                return $this->response->setJSON([
                    'status' => true,
                    'chat' => $chat
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Chat not found'
                ]);
            }
        } else {
            // Return all active chats
            $chats = $this->chatModel->where('status', 1)->orderBy('chat_id', 'DESC')->findAll();

            return $this->response->setJSON([
                'status' => true,
                'chats' => $chats
            ]);
        }
    }


    // Get messages by chat_id
    public function getMessages()
    {
        $input = $this->request->getJSON(true);

        $chat_id = $input['chat_id'] ?? null;
        $page = (int) ($input['page'] ?? 1);
        $limit = 10; // messages per page

        if (empty($chat_id)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'chat_id is required'
            ]);
        }

        $offset = ($page - 1) * $limit;

        // Get total messages count
        $total = $this->messageModel->where('chat_id', $chat_id)->countAllResults(false);

        // Get messages for this page (latest messages first)
        $messages = $this->messageModel
            ->where('chat_id', $chat_id)
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();

        // Reverse so oldest message comes first
        $messages = array_reverse($messages);

        return $this->response->setJSON([
            'status' => true,
            'chat_id' => $chat_id,
            'page' => $page,
            'per_page' => $limit,
            'total_messages' => $total,
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
            return $this->response->setJSON(['status' => false, 'message' => 'chat_id and receiver_id required']);
        }

        $this->messageModel
            ->where('chat_id', $chatId)
            ->where('receiver_id', $receiverId)
            ->set(['status' => 3])
            ->update();

        return $this->response->setJSON(['status' => true, 'message' => 'Messages marked as read']);
    }
}
