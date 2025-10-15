<?php 
namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model {
    protected $table = 'asset_category';
    protected $primaryKey = 'cat_id';
    protected $allowedFields = ['category_name', 'subcategory_name', 'diminish_rate', 'created_by', 'created_on'];

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function createCategory($data) {
        return $this->insert($data);
    }

    public function getCategoryByName($category_name) {
        return $this->where('category_name', $category_name)->first();
    }

    public function updateCategory($id, $data) {
        return $this->update($id, $data);
    }
    public function getAllCategory() {
        return $this->findAll(); 
    }
       // Delete a category
       public function deleteCategory($category_id) {
        return $this->delete($category_id);
    }
    public function deleteSubcategory($category_id, $sub_index) {
        $category = $this->find($category_id);
        if (!$category) {
            return false;
        }

        $subcategories = json_decode($category['subcategory_name'], true);

        if (!is_array($subcategories) || !isset($subcategories[$sub_index])) {
            return false; 
        }

      
        unset($subcategories[$sub_index]);

        
        $subcategories = array_values($subcategories);

       
        return $this->update($category_id, ['subcategory_name' => json_encode($subcategories)]);
    }

    public function getThisCategory($catId) {
		return $this->db->query("select * from asset_category where cat_id = ".$catId)->getRow();
	}
    public function modifyCategory($cat_id,$data) {
		return $this->db->table('asset_category')
					->where(["cat_id" => $cat_id])
					->set($data)
					->update();
	}
}
?>