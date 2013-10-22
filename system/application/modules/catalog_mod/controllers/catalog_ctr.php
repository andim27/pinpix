<?php
class Catalog_ctr extends Controller {
	
	var $_lng = '';
	
    function Catalog_ctr()
    {
         parent::Controller();
		 
         $this->_lng = $this->db_session->userdata('user_lang');
         $this->load->language('catalog',$this->_lng);
    }
	

	function index()
	{
		
	}
	
	function get_simple_tree()
	{
		$this->load->model('category_mdl','category');
		return $this->category->get_tree();		
	}
	
	function get_tree()
	{
	  $this->load->model('category_mdl','category');
	  //$catalog = $this->category->get_tree();
	  $catalog = $this->cache->process('cat_list', array($this->category, 'get_tree'),  array(), 3*60);
	  $columns_count = 3;
	  
	  $cats_main = array();
	  if ($catalog) 
	  {
	        foreach ($catalog as $i=>$cat)
	        {
	                if ($cat->level == 2)
	                {
	                        $cats_main[] = $cat;
	                }
	        }
	        $col_cnt = floor(count($cats_main)/$columns_count);
	        $cats = array();
	        $n = 0; 
	        $i = 0;
	        foreach ($catalog as $cat)
	        {
	                if ($cat->level == 2)
	                {
	                        if ($i>=$col_cnt*($n+1)) $n++;
	                        $i++;
	                }
	                if ($cat->level > 1)
	                {
	                        $cats[$n][] = $cat;
	                }
	                
	        }
	  }
	  return $cats;
	}
	
	function get_categories(){
		$categories = $this->get_tree();
		$categories_tree = null;
		
		if($categories) {		
			foreach ($categories as $index => &$category) {
				$categories_tree[$index] = $this->view_tree_($category, 'header');
			}
		}
		return $categories_tree;
	}
	
	function view_tree($context='')
	{
		$catalog = $this->get_simple_tree();
		
		$context = empty($context)?'':$context.'_';
		$this->load->config($context.'tree_view');
		
		$branch_before = $this->config->item('branch_before');
		$branch_after = $this->config->item('branch_after');
		$item_before = $this->config->item('item_before');
		$item_text = $this->config->item('item_text');
		$item_after = $this->config->item('item_after');
		
		return $this->category->get_tree_view($catalog, $branch_before, $branch_after, $item_before, $item_text, $item_after);
	}
	
	function view_tree_($catalog, $context='')
	{
		$ctx = empty($context)?'':$context.'_';
		$this->load->config($ctx.'tree_view');
		$config = $this->config->item($context);
		//log_message('debug','########  --  '.var_export($config, TRUE));
		
		return $this->category->get_tree_view_ext($catalog, $config);
	}
		
	function save_category()
	{
		if ( ! $_POST) return FALSE; 
		
		//$this->load->library('form_validation');
		//$this->load->language('form_validation',$this->_lng);
		//$config = $this->load->config('form_validation');
	  	//$cfg = $this->config->item('catalog_ctr/edit_category');
        //$this->form_validation->set_rules($cfg);
        
	  	//if ($this->form_validation->run() == TRUE)
		{
			//print_r($_FILES); die();
			$this->load->model('category_mdl', 'category');
			
			$parent_id = ($_POST['cat_parent_id']);
			//$lft = ($_POST['cat_lft'])z;
			//$rgt = ($_POST['cat_rgt']);
			$cat_id = ($_POST['cat_id']);
			
			$cat_name = clean($_POST['cat_name']);
			$cat_desc = clean($_POST['cat_desc']);
			//$cat_sort = clean($_POST['cat_sort']);
			
			$branch_fields = array();
			$item_fields = array(
				'name'=>$cat_name,
				'description'=>$cat_desc
				//'sort_order'=>$cat_sort
			);
			
			//if (empty($lft) || empty($rgt)) 
			if (empty($cat_id)) 
			{ 
				$cat_id = $this->category->add_item($parent_id, $branch_fields, $item_fields);
			}
			else 
			{
				//$cat_id = $this->category->update_item($lft, $rgt, $parent_id, $branch_fields, $item_fields);
				$cat_id = $this->category->update_item($cat_id, $parent_id, $branch_fields, $item_fields);
			}
			
			if( ! $cat_id) 
			{
				set_error('error_data_saving');
				return FALSE;
			}
			
			$this->load->config('catalog_config');
			$this->load->library('upload');
			$this->load->library('image_lib');
			
			///////////////////////////////////////////
			if ( ! empty($_FILES['cat_icon']['name'])) 
			{	
				$this->upload->upload_path =  $this->config->item('category_icons_dir');
				$ext = $this->upload->get_extension($_FILES['cat_icon']['name']);
				$_FILES['cat_icon']['name'] = $cat_id.'_ico'.$ext;
				if ( $this->upload->do_upload('cat_icon')) 
				{
					$icon_upload_data = $this->upload->data();				
					//echo '<br/><br/>'; print_r($icon_upload_data); die();	
					$config['image_library'] = 'gd2';
					$config['source_image'] = $icon_upload_data['full_path'];
					$config['maintain_ratio'] = TRUE;
					$config['width'] = $this->config->item('icon_width');
					$config['height'] = $this->config->item('icon_height');					
					$this->image_lib->initialize($config);												
					$this->image_lib->resize();
					
					$image = $this->image_lib->image_create_gd();
					$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
					imageconvolution($image, $gaussian, 16, 0);
					$this->image_lib->image_save_gd($image);
				}
				else {
					set_error($this->upload->display_errors());
					return FALSE;
				}
			} 
			
			if ( ! empty($_FILES['cat_preview']['name']))
			{	
				$this->upload->upload_path =  $this->config->item('category_previews_dir');
				$ext = $this->upload->get_extension($_FILES['cat_preview']['name']);
				$_FILES['cat_preview']['name'] = $cat_id.'_preview'.$ext;
				if ( $this->upload->do_upload('cat_preview')) 
				{
					$preview_upload_data = $this->upload->data();				
					//echo '<br/><br/>'; print_r($preview_upload_data); die();	
					$config['image_library'] = 'gd2';
					$config['source_image'] = $preview_upload_data['full_path'];
					$config['maintain_ratio'] = TRUE;
					$config['width'] = $this->config->item('preview_width');
					$config['height'] = $this->config->item('preview_height');					
					$this->image_lib->initialize($config);									
					$this->image_lib->resize();
				}
				else {
					set_error($this->upload->display_errors());
					return FALSE;
				}
			} 
			///////////////////////////////////////////
			
			$icon_name = $icon_upload_data['file_name'];
			$preview_name = $preview_upload_data['file_name'];
			if ( ! empty($icon_name) ||  ! empty($preview_name) )
			{
				$item_fields = array();
				
				if ( ! empty($icon_name)) $item_fields['icon'] = clean($icon_name);
				if ( ! empty($preview_name)) $item_fields['preview'] = clean($preview_name);
				
				//$cat_id = $this->category->update_item($lft, $rgt, $parent_id, $branch_fields, $item_fields);
				$cat_id = $this->category->update_item($cat_id, $parent_id, $branch_fields, $item_fields);
				
				if( ! $cat_id) 
				{
					set_error('error_data_saving');
					return FALSE;
				}
			}
			unset($_POST);
			return TRUE;
			
		}
		return FALSE;
	}
	
	function delete_category()
	{
		if ( ! $_POST) return FALSE;
		
		$lft = ($_POST['removed_lft']);
		$rgt = ($_POST['removed_rgt']);
		
		if ( empty($lft) || empty($rgt) ) return FALSE;
		
		$this->load->model('category_mdl', 'category');
		
		$categories = $this->category->get_items(array('lft'=>$lft, 'rgt'=>$rgt));
		
		if ( ! is_array($categories) || empty($categories) ) return FALSE;
		
		$category = $categories[0];
		
		$res = $this->category->delete_item($lft, $rgt);
		if($res) 
		{
			$this->category->updateCAF($category->id);
			$this->load->config('catalog_config');
			unlink(trim($this->config->item('category_icons_dir'),'/').'/'.$category->icon);
			unlink(trim($this->config->item('category_previews_dir'),'/').'/'.$category->preview);
			unset($_POST);
			return TRUE;
		}	
		return FALSE;
	}
	
	function edit_category($cat_id=null)
	{
		$this->load->model('category_mdl','category');
		$this->category->set_root_visibility(TRUE);
		
		$this->load->config('catalog_config');
		
		$data['cat_parent'] = $this->category->get_root()->id;
		/*
		$data['cat_lft'] = '';
		$data['cat_rgt'] = '';
		*/
		$data['cat_id'] = '';
			
		$data['cat_name'] = '';
		$data['cat_description'] = '';
		//$data['cat_sort_order'] = '';
		
		
		if ($_POST) 
		{			
			$data['cat_parent'] = set_value('cat_parent_id');
			/*
			$data['cat_lft'] = set_value('cat_lft');
			$data['cat_rgt'] = set_value('cat_rgt');
			*/
			$data['cat_id'] = set_value('cat_id');
			
			$data['cat_name'] = set_value('cat_name');
			$data['cat_description'] = set_value('cat_desc');
			//$data['cat_sort_order'] = set_value('cat_sort');
		}
		elseif ( ! empty($cat_id)) 
		{
			$category = $this->category->get_old($cat_id);
			
			if (is_array($category) && ! empty($category)) 
			{
				$category = $category[0];
				
				$data['cat_parent'] = $category->parent_id;
				/*
				$data['cat_lft'] = $category->lft;
				$data['cat_rgt'] = $category->rgt;
				*/
				$data['cat_id'] = $category->id;
				
				$data['cat_name'] = $category->name;
				$data['cat_description'] = $category->description;
				//$data['cat_sort_order'] = $category->sort_order;

				if ( ! empty($category->icon)) $data['cat_icon_src'] = trim($this->config->item('rel_category_icons_dir'), '/').'/'.$category->icon; //$category->id.'_ico'.$ext;
				if ( ! empty($category->preview)) $data['cat_preview_src'] = trim($this->config->item('rel_category_previews_dir'), '/').'/'.$category->preview; 
			}
			else
			{
				set_error('Requested Category hasn\'t been found!');
			}
		}
		
		$data['cat_list'] = $this->view_tree('select_options');
		//$data['cat_count'] = $this->category->found_rows();
		
		$this->load->view('category_form',$data);
	}

	function get_categories_list(){
		$this->load->model('category_mdl','category');
		//return $this->category->get_categories_list();
		 return $this->cache->process('categories_list', array($this->category, 'get_categories_list'), array(), 3*60);
	}

    function get_category_name($cat_id){
        if (empty ($cat_id)) return "no category"; else
		$this->load->model('category_mdl','category');
		$cat = $this->category->get($cat_id);
        if (!empty ($cat[0]->name))
            return $cat[0]->name;
        else return "no category";
	}
}

	


/* end of file */