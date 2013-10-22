<?php
class Album extends Controller {

	function Album() {
		parent::Controller();
		$lng = $this->db_session->userdata('user_lang');
		$this->lang->load('phh',$lng);
	}

	function index(){
		show_404(lang('page_not_found'));
	}

	function view() {
		$album_id = $this->uri->segment(3);
		
		$this->config->load('ads');
		$cfg = $this->config->item('album');	
		$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);
		
		$password = null;
		if(isset($_POST['album_password']) && !empty($_POST['album_password'])) {
			$password = $_POST['album_password'];
			$view_album = $this->db_session->userdata('view_album');
			$view_album[$album_id] = $password;
			$this->db_session->set_userdata('view_album', $view_album);
		}
		$view_album = $this->db_session->userdata('view_album');
		if($view_album) {
			if(isset($view_album[$album_id]))
				$password = $view_album[$album_id];
		}

		//permissions
		$registered = $this->db_session->userdata('user_id');
		if (empty($registered)) $registered = 0;

		$user = modules::run('users_mod/users_ctr/profile_get', $registered);
		if  ($registered == 0 ) {
			$erotic = -1;
		} 
		else  
		{
			$erotic = $this->db_session->userdata('erotic_allow');			
		}

        $moderatoin_state = MODERATION_STATE;  //=1 - const: system\application\config\constants.php

		//end permissions

		$object_type = 'photo';
		$per_page = 32;

		$cpage = null;
		$sort_type = null;
		$sort_order = null;

		if ($this->uri->segment(4)=='page') {
			$cpage = $this->uri->segment(5);
		} else {
			$sort_type = $this->uri->segment(4);
			if ($this->uri->segment(5)=='page') {
				$cpage = $this->uri->segment(6);
			} else {
				$sort_order = $this->uri->segment(5);
				$cpage = $this->uri->segment(7);
			}
		}
				
        if (empty($sort_type)){
    		$sort_type = $this->db_session->userdata('a_sort_type');
    	}
    	else
    		$this->db_session->set_userdata('a_sort_type', $sort_type);
    		
    	if (empty($sort_type))
    		$sort_type = 1;
    	
        if (empty($sort_order)){
    		$sort_order = $this->db_session->userdata('a_sort_order');
    	}
    	else
    		$this->db_session->set_userdata('a_sort_order', $sort_order);

    	$sort_order = ($sort_order == "d")? "d": "a";			
    			
		$data['view_album_block'] = modules::run('gallery_mod/gallery_ctr/view_album_photos', $album_id, $per_page, $cpage, $moderatoin_state, null, $password, $registered, $erotic, $sort_type, $sort_order);
		$this->load->view('_album', $data);
	}
	
	//function, that shows all user albums. 
	//access from link "all albums" at sidebar at some pages 
	function view_user_albums()
	{
		$user_id = $this->uri->segment(3);
		if (empty ($user_id)) redirect (base_url());
		
		$name = modules::run('users_mod/users_ctr/profile_get', $user_id);
		
		$my = ($this->db_session->userdata('user_group') == "")? FALSE: TRUE;
		$my = ($my && ($this->db_session->userdata('user_id') == $user_id))? TRUE: FALSE;
		
		if (empty ($name))
			redirect (base_url());
			
		$this->config->load('ads');
		$cfg = $this->config->item('all_albums');	
		$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);
			
		//permissions
		$moderation_state = MODERATION_STATE;
		$registered = $this->db_session->userdata('user_id');
		if (empty($registered)) $registered = 0;
		if  ($registered == 0)
			$erotic = -1;
		else  
			$erotic = $this->db_session->userdata('erotic_allow');			
				
		//end permissions
		
		if ( $user_id == $this->db_session->userdata('user_id') )
        {
	        $moderation_state = "all";
	        $registered = 1;
	        $erotic = 1;
        }       
		
		$per_page = 32;	
		$cpage = null;
		$sort_type = null;
		$sort_order = null;

		if ($this->uri->segment(4)=='page') {
			$cpage = $this->uri->segment(5);
		} else {
			$sort_type = $this->uri->segment(4);
			if ($this->uri->segment(5)=='page') {
				$cpage = $this->uri->segment(6);
			} else {
				$sort_order = $this->uri->segment(5);
				$cpage = $this->uri->segment(7);
			}
		}

		$photos = null;

		if ($my) {
			$data['all_albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', 'all', $registered, $erotic, $user_id);
			$photos = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, $user_id, 0, 1, -1, 1, 1, true, 'title asc');
		} else {
			$data['all_albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', MODERATION_STATE, $registered, $erotic, $user_id);
			$photos = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, $user_id, MODERATION_STATE, $registered, $erotic, 1, 1, true, 'title asc');
		}
		
		$cnt_photos = 0;		
		if(!empty($photos) && isset($photos['count'])) $cnt_photos = $photos['count'];
		
		$data['all_albums_cnt'] = (!empty($data['all_albums']))?count($data['all_albums']):0;
		$data['SeeCnt']=$this->rating->getSeeCnt('user',$user_id);
		$data['Balls']=$this->rating->getBalls('user',$user_id);
		$data['user_comments_count'] = (int)modules::run('comments_mod/comments_ctr/get_comments_count_per_user', $user_id);				
		$data['cnt_photos'] = $cnt_photos;
		$data['user'] = modules::run('users_mod/users_ctr/profile_get',$user_id);
		$user_avatar_path = modules::run('users_mod/users_ctr/get_user_avatar_path');	
		$data['avatar'] = modules::run('users_mod/users_ctr/get_avatar_src', $user_id);
		$data['user_avatar_path'] = $user_avatar_path;
		
		// end all the stuff for sitebar user info		
		$data['all_user_albums'] = modules::run('gallery_mod/gallery_ctr/view_all_user_albums', $user_id, $moderation_state, $registered, $erotic, $per_page, $cpage, $sort_type, $sort_order);
		
		$this->load->view('_user_albums', $data);		
	}

}

/* end of file */