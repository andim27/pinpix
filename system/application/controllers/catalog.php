<?php
class Catalog extends Controller {
	    
    function Catalog()
    {
         parent::Controller();
		 
         $lng = $this->db_session->userdata('user_lang');
         
         $this->lang->load('phh',$lng);
         
    }
    
	function index()
	{
		show_404(lang('page_not_found'));
	}
	
	function category()
	{
		$cat_id = $this->uri->segment(3);
		if (empty ($cat_id))
			show_404(lang('page_not_found'));
			
		$data['register_block'] = modules::run('users_mod/users_ctr/register');
		 
		$this->config->load('ads');
		$cfg = $this->config->item('category');	
		$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);
		$data['flashbox_block'] = true;  
        $cpage = null;
        $sort_type = 3;
        $sort_order = 'd';

        if ($this->uri->segment(4)=='page')
            $cpage = $this->uri->segment(5);
        else
            {
            if ($this->uri->segment(4))	
            	$sort_type = $this->uri->segment(4);
            if ($this->uri->segment(5)=='page')
                $cpage = $this->uri->segment(6);
            else
                {
                if ($this->uri->segment(5))		
                	$sort_order = $this->uri->segment(5);
                $cpage = $this->uri->segment(7);
                }
        }       

        //permissions

        $registered = $this->db_session->userdata('user_id');

        if (empty($registered)) $registered = 0;

        if  ($registered == 0)
            $erotic = -1;            
        else  
        {
            $erotic = $this->db_session->userdata('erotic_allow');		
        }

        //end permissions
        if (($cat_id == 67)&&($erotic == -1))
			$data['category_albums_block'] = "<h3>" . lang ('erotic_banned'). "</h3>";
		else
		{
	        $moderatoin_state = MODERATION_STATE;  //=1 - const: system\application\config\constants.php
			$perpage = 32;
	        $data['category_albums_block'] = modules::run('gallery_mod/gallery_ctr/view_category_photos', $cat_id, $moderatoin_state, $registered, $erotic, $sort_type, $cpage, $sort_order, $perpage);
	
			$limit=10;
			$moderation_state=array(1,2);
		}
        //$data['category_tags_block'] = modules::run('tags_mod/tags_ctr/view_object_list_tags', $cat_id, $limit, $moderation_state);

		$this->load->view('_category',$data);
	}
	
	function get_c()
	{
	  $cats = modules::run('catalog_mod/catalog_ctr/get_tree'); 
	  print_r ($cats );
	}

}

/* end of file */