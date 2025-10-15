<?php
namespace App\Controllers;
use App\Models\CategoryModel;



class Category extends BaseController {
	protected $categoryModel;
   
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->categoryModel = new CategoryModel();
   }
    public function index($catId=''){ 
        // echo "$catId";
        if($catId) {
			$ThisCategory = $this->categoryModel->getThisCategory($catId);
            $subcategories = json_decode($ThisCategory->subcategory_name, true) ?? [];
            $subcategoryString = implode(',', $subcategories);

			$data['category_name'] = $ThisCategory->category_name;
			$data['subcategory_name'] =  $subcategoryString;
			$data['diminish_rate'] = $ThisCategory->diminish_rate;
			$data['catId'] = $ThisCategory->cat_id;
        }
        else{
            $data['category_name'] = '';
			$data['subcategory_name'] =  '';
			$data['diminish_rate'] = '';
			$data['catId'] = null; 
        }
       
	 if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			
			$fv_id = $this->session->get('fav_id');
			$user_id = $this->session->get('fav_user_id');
        
           
			
            $data['menu'] = 14;
            $data['username'] = ucwords($this->session->get('fav_user_name'));
            $data['orgname'] = ucwords($this->session->get('fav_org'));
            $template = view('common/header',$data);    
			$template .= view('category');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/categoryjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}

    public function loadCategories()
    {
        $categories = $this->categoryModel->findAll();
        $data = [];
        $slno = 1;
        foreach ($categories as $category) {
            $subcategories = json_decode($category['subcategory_name'], true) ?? [];
            // $action = '<a href="'.base_url("category/$category->cat_id").'"><i class="fa fa-pencil-square-o"></i></a>';
			$action = '';
            $data[] = [
                'cat_id' => $category['cat_id'],
                'category_name' => $category['category_name'],
                'subcategories' => $subcategories,
                'diminish_rate' => $category['diminish_rate'],
                'action' => $action
                // 'action' => '<button class="update-category" data-id="'.$category['cat_id'].'">
                //                 <i class="fa fa-pencil-square-o"></i>
                //             </button>
                //             <button class="delete-category" data-id="'.$category['cat_id'].'">
                //                 <i class="fa fa-trash-o"></i>
                //             </button>'
            ];
        }
    
        return $this->response->setJSON(['data' => $data]); 
    }
    
    public function saveCategory()
{
    $cat_id = $this->request->getPost('cat_id');
    $category_name = $this->request->getPost('category_name');
    $subcategory_input = trim($this->request->getPost('subcategory_name')); // Trim spaces
    $diminish_rate = $this->request->getPost('diminish_rate');

    if (empty($category_name) || empty($subcategory_input) || empty($diminish_rate)) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'All fields are required.']);
    }

    // Convert subcategory input into an array (split by comma)
    $new_subcategories = array_map('trim', explode(',', $subcategory_input)); // Trim spaces after split

    if ($cat_id) {
        $existing_category = $this->categoryModel->find($cat_id);

        if (!$existing_category) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Category not found.']);
        }

        // Decode existing subcategories properly and ensure it's an array
        $existing_subcategories = json_decode($existing_category['subcategory_name'], true);
        if (!is_array($existing_subcategories)) {
            $existing_subcategories = [];
        }

        // Merge new and existing subcategories, remove duplicates, and re-index array
        $merged_subcategories = array_values(array_unique(array_merge($existing_subcategories, $new_subcategories)));

        // Save updated subcategories correctly
        $this->categoryModel->update($cat_id, [
            'category_name' => $category_name,
            'subcategory_name' => json_encode($merged_subcategories), // Store as JSON
            'diminish_rate' => $diminish_rate,
            'modified_on' => date("Y-m-d H:i:s"),
            'modified_by' => $this->session->get('fav_user_id'),
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Category updated successfully!']);
    } else {
        $existing_category = $this->categoryModel->getCategoryByName($category_name);

        if ($existing_category) {
            $existing_subcategories = json_decode($existing_category['subcategory_name'], true);
            if (!is_array($existing_subcategories)) {
                $existing_subcategories = [];
            }

            $merged_subcategories = array_values(array_unique(array_merge($existing_subcategories, $new_subcategories)));

            $this->categoryModel->update($existing_category['cat_id'], [
                'subcategory_name' => json_encode($merged_subcategories), // Ensure unique values
                'diminish_rate' => $diminish_rate,
                'modified_on' => date("Y-m-d H:i:s"),
                'modified_by' => $this->session->get('fav_user_id'),
            ]);
        } else {
            $data = [
                'category_name' => $category_name,
                'subcategory_name' => json_encode($new_subcategories), // Store as array
                'diminish_rate' => $diminish_rate,
                'created_on' => date("Y-m-d H:i:s"),
                'created_by' => $this->session->get('fav_user_id') ?? null,
            ];
            $this->categoryModel->createCategory($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Category saved successfully!']);
    }
}

    

//Delete category

    public function deleteCategory() {
        $categoryId = $this->request->getPost('category_id');
      
        $model = new CategoryModel();
        if ($model->deleteCategory($categoryId)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Category deleted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete category.']);
        }
    }
    //Delete subactegory
    public function deleteSubcategory() {
        $categoryId = $this->request->getPost('category_id');
        $subIndex = $this->request->getPost('sub_index');

        $model = new CategoryModel();
        if ($model->deleteSubcategory($categoryId, $subIndex)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Subcategory deleted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete subcategory.']);
        }
    }
    //dimish rate prepopulate 
    public function getDiminishRate()
{
    $category_name = $this->request->getPost('category_name');

    if (!$category_name) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Category name is required.']);
    }

    $category = $this->categoryModel->where('category_name', $category_name)->first();

    if ($category) {
        return $this->response->setJSON(['status' => 'success', 'diminish_rate' => $category['diminish_rate']]);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Category not found.']);
    }
}


   



    

}
?>