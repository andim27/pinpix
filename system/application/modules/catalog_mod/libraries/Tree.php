<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
// ------------------------------------------------------------------------

/**
 * Tree Class
 * 
 * @package		Tree
 * @subpackage	Libraries
 * @category	Tree
 * @author		Alina Brus
*/
class Tree {
	var $id;
	var $name;
	//var $type;
	var $level = 0;
	var $properties = array();
	var $items = array();
	
	function Tree($id=null, $name='', $properties=array()){
		$this->id = $id;
		$this->name = $name;
		$this->properties = $properties;
	}

	function add_item($id, $name, $properties=array(), $parent_id=null){
		if (empty($parent_id)) {
			$item = new Tree($id,$name,$properties);
			$item->level = 1;
			$this->items[] = $item;
			return TRUE;
		}
		else {
			$branch = $this->find_item($this,$parent_id);
			if ($branch) {
				$item = new Tree($id,$name,$properties);
				$item->level = $branch->level+1;
				$branch->items[] = $item;
				return TRUE;
			}
			else return FALSE;
		}
		
	}
	
	
	function find_item($branch, $id){
		foreach ($branch->items as $item){
			if ($item->id==$id) return $item;	
			$found = $this->find_item($item, $id);
			if ($found) return $found;
		}
		return FALSE;
	}
	
	function print_me(){
		print_r($this);
	}	
	
	function _get_tree_view($branch_before, $branch_after, $item_before, $item_text, $item_after, $branch=null){
		if (empty($branch)) $branch = $this; 
		
		$view = '';
		if ( ! empty($branch->items) ) {
			$view .= $branch_before;
			foreach ($branch->items as $item){
				
				$_item_ = $item_text;
				$_item_ = str_replace('%id%',$item->id,$_item_ );
				$_item_ = str_replace('%name%',$item->name,$_item_ );
				$_item_ = str_replace('%level%',$item->level,$_item_ );
				$view .= $item_before.$_item_;
				
				if ( ! empty($item->items) ) 
					$view .= $this->get_tree_view($branch_before, $branch_after, $item_before, $item_text, $item_after, $item);
				
				$view .= $item_after;
			}
			$view .= $branch_after;
		}

		return $view;
	}
	
	function get_tree_view($branch_before, $branch_after, $item_before, $item_text, $item_after, $branch=null){
		if (empty($branch)) $branch = $this; 
		
		$view = '';
		if ( ! empty($branch->items) ) {
			$view .= $branch_before;
			foreach ($branch->items as $item){
				
				$_item_ = $item_text;
				$_item_ = str_replace('%id%',$item->id,$_item_ );
				$_item_ = str_replace('%name%',$item->name,$_item_ );
				$_item_ = str_replace('%level%',$item->level,$_item_ );
				$view .= $item_before.$_item_;
				
				if ( ! empty($item->items) ) 
					$view .= $this->get_tree_view($branch_before, $branch_after, $item_before, $item_text, $item_after, $item);
				
				$view .= $item_after;
			}
			$view .= $branch_after;
			
			$view = str_replace('%level%',$branch->level+1,$view );
		}

		return $view;
	}

}
// END Tree Class
?>
