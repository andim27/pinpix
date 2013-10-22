<?php
/*
static $ci;
if (!is_object($ci)) $ci = &get_instance();
$ci->load->file(APPPATH.'models/tree_mdl.php');
*/

$path = APPPATH;
modules::load_file('tree_mdl',$path.'/models/');
$path = MODBASE.modules::path();
modules::load_file('tree_mdl',$path.'/models/');

class Category_mdl extends Tree_mdl {

	function Category_mdl()
    {
        //parent::Tree_mdl('categories_tree');  
		$this->set_table('categories_tree'); 
    }
    
	function get_tree(){
		return $this->get_items();
	}
	
	function get($cat_id){
		$query = "SELECT categories_tree.name FROM categories_tree WHERE categories_tree.id = " . $cat_id;
		return $this->db->query($query)->result();
	}
	
	function get_old($cat_id){
		$conditions = array('id'=>clean($cat_id));
		return $this->get_items($conditions);
	}

	function get_categories_list(){
		$query = "SELECT categories_tree.id, categories_tree.name FROM categories_tree WHERE categories_tree.id > 1";
		return $this->db->query($query)->result();
	}
	
	//when we delete category, we should move photos and albums of it 
	//from album_category_map and photo_category_map
	//lets move them then to default category 'raznoe'  with id = 96
	function updateCAF($category_id)
	{
		$query = "UPDATE photo_category_map SET category_id = 96 where category_id = " . $category_id;
		echo $query;
			$t = $this->db->simple_query($query);
			
		$query = "UPDATE album_category_map SET category_id = 96 where category_id = " . $category_id;	
		echo $query;
			return $this->db->simple_query($query);				
	}
}
?>