<?php
class Photo extends Controller {

	function Photo() {
		parent::Controller();
        $data = get_app_vars();
		$lng = $this->db_session->userdata('user_lang');

		$this->lang->load('phh',$lng);
        //$this->load->helper('BI_functions');

	}
    
	function index() {
		show_404(lang('page_not_found'));
	}
	
	function view() {
        
		$view_photo = null;
		$password = null;
		
		$this->config->load('ads');
		$cfg = $this->config->item('photo');	
		$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);

		$photo_id = $this->uri->segment(3);
		if (empty($photo_id)){
			 set_error(lang('page_not_found'));
		}
		
		if(isset($_POST['photo_password']) && !empty($_POST['photo_password'])) {
			$password = $_POST['photo_password'];
			$view_photo = $this->db_session->userdata('view_photo');
			$view_photo[$photo_id] = $password;
			$this->db_session->set_userdata('view_photo', $view_photo);
		}

		$view_photo = $this->db_session->userdata('view_photo');
		if($view_photo) {
			if(isset($view_photo[$photo_id])) {
				$password = $view_photo[$photo_id];
			}
		}

		//permissions
		$registered = $this->db_session->userdata('user_id');
		if (empty($registered)) $registered = 0;
		if  ($registered == 0)
			$erotic = -1;
		else  {
			$erotic = $this->db_session->userdata('erotic_allow');
			if (!$erotic)
				$erotic = modules::run('users_mod/users_ctr/get_age', $registered);
		}
		
		$data['user'] = modules::run('gallery_mod/gallery_ctr/get_user',$photo_id);
		$user_id = $data['user'][0]->user_id;
		
		$my = ($this->db_session->userdata('user_group') == "")? FALSE: TRUE;
		$my = ($my && ($this->db_session->userdata('user_id') == $user_id))? TRUE: FALSE;
		
		$moderation_state = MODERATION_STATE;
		$moderation_state = ($my === TRUE) ? 0 : 1;
		//end permissions

		$data['photo_one_img_html'] = modules::run('gallery_mod/gallery_ctr/get_photo_one_img',$photo_id, $moderation_state, $password, $registered, $erotic);
		$cur_user_info = modules::run('users_mod/users_ctr/get_user_info', $this->db_session->userdata('user_id'));
		
		$data['cur_user_info'] = $cur_user_info;
       
        if ($data['photo_one_img_html']!=-1)
		{
			$user_avatar_path = modules::run('users_mod/users_ctr/get_user_avatar_path');
			$data['SeeCnt']=$this->rating->getSeeCnt('user',$user_id);
			$data['Balls']=$this->rating->getBalls('user',$user_id);
			$data['user_comments_count'] = (int)modules::run('comments_mod/comments_ctr/get_comments_count_per_user', $user_id);					
			$data['avatar'] = modules::run('users_mod/users_ctr/get_avatar_src', $user_id);
			$data['categories'] = modules::run('catalog_mod/catalog_ctr/get_categories_list');
			
			$object_type = 'photo';
			$comments_per_page = 10;
			$page = uri_segment(5);
			if(empty($page)) $page = 1;
			
            $my = ($this->db_session->userdata('user_group') == "")? FALSE: TRUE;
			$my = ($my && ($this->db_session->userdata('user_id') == $user_id))? TRUE: FALSE;
			
            if ($my) {
				$data['all_albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', 'all', $registered, $erotic, $user_id);
				$photos = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, $user_id, 0, 1, -1, 1, 1, true, 'title asc');
				
			} else {
				$data['all_albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', MODERATION_STATE, $registered, $erotic, $user_id);
				$photos = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, $user_id, MODERATION_STATE, $registered, $erotic, 1, 1, true, 'title asc');
			}
			
			$data['cnt_photos'] = (isset($photos['count'])) ? $photos['count'] : 0;			
			$data['all_albums_cnt'] = (!empty($data['all_albums']))?count($data['all_albums']):0;			
			$data['photo_comments_block'] = modules::run('comments_mod/comments_ctr/view_object_comments',$object_type, $photo_id,  $data['avatar'], $comments_per_page, $page, 1, array(), true);
		}
       	$photo = modules::run('gallery_mod/gallery_ctr/get_photo',$photo_id, 'lg', $moderation_state);
        $data['download_str']=$this->get_download_str($photo);
        
		$this->load->view('_photo_new', $data);
	}
	
    function get_download_str($photo) {
       $resolution_arr = $this->config->item("resolution_foto");
       if (empty($resolution_arr )) {return "<span>???x???</span>";}
       
       $urlImg = $this->config->item('photo_uploads').date("m", strtotime($photo->date_added))."/".$photo->photo_id."-lg".$photo->extension;
       list($src['width'], $src['height']) = getimagesize($urlImg);
       $res_key_arr = array();
       foreach ($resolution_arr as $k =>$v) {
              if (($src['width'] >= $v["w"]) and ($src['height'] >= $v["h"])) {
                 array_push($res_key_arr,$v);
              }
       }
       $phsrc_lg = date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'/'.$photo->extension;
       if (count($res_key_arr) <= 0) {return "";}
       $out_str=lang('download').'<br />';
       foreach ($res_key_arr as $k=>$resol) {
          $out_str.='<a class="a_b0" href="'. base_url() . 'save/savef/'.$phsrc_lg.'/'.$k.'">'.strval($resol["w"])."x".strval($resol["h"]).'</a>&nbsp;&nbsp;';
       }
      return $out_str;
    }
    
	function add(){

		$add_photo_result = FALSE;

		$album_id = $this->uri->segment(3);
		if (empty($album_id)){
			show_404(lang('page_not_found'));		
			return false;
		}
		if ($_POST)
		{
			$add_photo_result = modules::run('gallery_mod/gallery_ctr/add_photo_to_album');
			if ($add_photo_result) {
				$added_photo_postdata = modules::run('gallery_mod/gallery_ctr/get_last_insert_photo');
				if ( ! empty($added_photo_postdata)) {
					modules::run('tags_mod/tags_ctr/save_object_tags','photo', $added_photo_postdata['photo_id'], $added_photo_postdata['photo_tags']);
				}
			}
		}
		else
		 	show_404(lang('page_not_found'));

		if ( $add_photo_result) 
		{
			redirect(url('view_album_url', $album_id));
		}
		else
		{
			$context = 'select_options';
			$categories = modules::run('catalog_mod/catalog_ctr/view_tree',$context); 		
			
			$addition_block = modules::run('tags_mod/tags_ctr/get_tags_input_block','photo');
			
			$data['add_photo_block'] = modules::run('gallery_mod/gallery_ctr/view_photo_form', null, $categories, $addition_block, $album_id);
			
			$this->load->view('_photo_add',$data); 		
		}
				
    }
	
	function edit(){
		
		$edit_photo_result = FALSE;
		$album_id = 0;
		
		if (isset($_POST['photo_name']))
		{
			if (isset($_POST['photo_album'])) 
				$album_id = $_POST['photo_album'];
			$edit_photo_result = modules::run('gallery_mod/gallery_ctr/edit_photo');
			
			if ($edit_photo_result) {
				$photo_postdata = modules::run('gallery_mod/gallery_ctr/get_last_insert_photo');
				if ( ! empty($photo_postdata)) {
					$edit_photo_result = $edit_photo_result && modules::run('tags_mod/tags_ctr/save_object_tags','photo', $photo_postdata['photo_id'], $photo_postdata['photo_tags']);
				}
			}
			
		}
		else
			show_404(lang('page_not_found'));		
					
		if ( $edit_photo_result) 
		{
			if ( ! empty($album_id)) redirect(url('view_album_url', $album_id));
		}
		else 
		{
			$context = 'select_options';
			$categories = modules::run('catalog_mod/catalog_ctr/view_tree',$context); 		
			
			$photo_id = $this->uri->segment(3);
			$addition_block = modules::run('tags_mod/tags_ctr/get_tags_input_block','photo',$photo_id); 
			
			$data['edit_photo_block'] = modules::run('gallery_mod/gallery_ctr/view_photo_form', $photo_id, $categories, $addition_block);
			
			$this->load->view('_photo_edit',$data); 		
		}
				
    }
    
	function edit_() {
		$photo_id = $this->uri->segment(3);
		if (empty($photo_id)){
			show_404(lang('page_not_found'));		
		}
		$context = 'select_options';
		$categories = modules::run('catalog_mod/catalog_ctr/view_tree',$context); 	
		
		
		$addition_block = modules::run('tags_mod/tags_ctr/get_tags_input_block','photo',$photo_id); 
		
		$edit_photo_block = modules::run('gallery_mod/gallery_ctr/edit_photo', $photo_id, $categories, $addition_block); 		
		
		$data['edit_photo_block'] = $edit_photo_block;

        $this->load->view('_photo_edit', $data);
	}
	
	function delete() {
		$data['photo_id'] = $this->uri->segment(3);
		if (empty($data['photo_id'])){
			show_404(lang('page_not_found'));		
		}
		$this->load->view('_photo_delete', $data);
	}

	function vote_action()
	{
        if (!isset($_POST)){return;}
        modules::run('gallery_mod/gallery_ctr/vote_action');
	}
	
	function prop_action()
    {
        if (!isset($_POST)){return;}
        modules::run('gallery_mod/gallery_ctr/prop_action');
    }
	
    function save_photo(){
		if (empty($_POST['photo_link'])){
			show_404(lang('page_not_found'));		
		}
		$filename = $_POST['photo_link'];
				
		$ftime = date("D, d M Y H:i:s T", filemtime($filename)); 
		$fd = @fopen($filename, "rb"); 
		if (!$fd){ 
		  header ("HTTP/1.0 403 Forbidden"); 
		  exit; 
		} 
		// Если запрашивающий агент поддерживает докачку 
		if ($HTTP_SERVER_VARS["HTTP_RANGE"]) { 
		  $range = $HTTP_SERVER_VARS["HTTP_RANGE"]; 
		  $range = str_replace("bytes=", "", $range); 
		  $range = str_replace("-", "", $range); 
		  if ($range) {fseek($fd, $range);} 
		} 
		$content = fread($fd, filesize($filename)); 
		fclose($fd); 
		if ($range) { 
		  header("HTTP/1.1 206 Partial Content"); 
		} 
		else { 
		  header("HTTP/1.1 200 OK"); 
		} 
		header("Content-Disposition: attachment; filename=$fn"); 
		header("Last-Modified: $ftime"); 
		header("Accept-Ranges: bytes"); 
		header("Content-Length: ".($fsize-$range)); 
		header("Content-Range: bytes $range-".($fsize -1)."/".$fsize); 
		header("Content-type: application/octet-stream"); 
		print $content; 
		exit; 

    }
    
    function upload_photo()
    {
		$_user_id = $this->db_session->userdata('user_id');
       	$my = ($this->db_session->userdata('user_group') == "")? FALSE: TRUE;
		$my = ($my && ($this->db_session->userdata('user_id') == $_user_id))? TRUE: FALSE;
		
		if (! $my) redirect(base_url(). 'register');
		
		$registered =  $this->db_session->userdata('user_id');

        if (empty($registered)) $registered = 0;

        if  ($registered == 0)
            $erotic = -1;
        else  {
            $erotic = $this->db_session->userdata('erotic_allow');
            if (!$erotic)
              $erotic = modules::run('users_mod/users_ctr/get_age', $registered);
        }

        $data['user'] = modules::run('users_mod/users_ctr/profile_get',$_user_id);
		
        $this->config->load('ads');
		$cfg = $this->config->item('photo_add');	
		$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);
		
        if (!empty ($data['user']))
        {		
    			$data['categories'] = modules::run('catalog_mod/catalog_ctr/get_categories_list');
    			$data['competitions'] = modules::run('competition_mod/competition_ctr/get_competition');
				if ($my)
	    		{
	    			$data['albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', 'all', $registered, $erotic, $_user_id);		    		
	
				} else {
					$data['albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', MODERATION_STATE, $registered, $erotic, $_user_id);
				}
    			$data['view_allowed'] = array(
    				2 => lang('view_public'),
    				1 => lang('view_registered'),
    				0 => lang('view_private')
    			);

    			$this->config->load('../modules/gallery_mod/config/config');
    			$data['allowed_types'] = explode('|', $this->config->item('img_types'));
    			$data['allowed_types'] = '*.'.implode(';*.', $data['allowed_types']).';';
    			$data['file_max_size'] = $this->config->item('file_max_size');
    		    			
        }     //  if (!empty ($data['user']))
        else 
        	redirect(base_url(). 'register');	
		$this->load->view('_photo_upload', $data);		
    }
    
	function downloadFile($filename=null, $mimetype='application/octet-stream') {
		if (empty($filename)){
			show_404(lang('page_not_found'));		
		}
		if (!file_exists($filename)) die('Файл не найден');

		$from=$to=0; $cr=NULL;

		if (isset($_SERVER['HTTP_RANGE'])) {
			$range=substr($_SERVER['HTTP_RANGE'], strpos($_SERVER['HTTP_RANGE'], '=')+1);
			$from=strtok($range, '-');
			$to=strtok('/'); if ($to>0) $to++;
			if ($to) $to-=$from;
			header('HTTP/1.1 206 Partial Content');
			$cr='Content-Range: bytes ' . $from . '-' . (($to)?($to . '/' . $to+1):filesize($filename));
		} else {
			header('HTTP/1.1 200 Ok');
		}

		$etag=md5($filename);
		$etag=substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
		header('ETag: "' . $etag . '"');

		header('Accept-Ranges: bytes');
		header('Content-Length: ' . (filesize($filename)-$to+$from));
		if ($cr) header($cr);

		header('Connection: close');
		header('Content-Type: ' . $mimetype);
		header('Last-Modified: ' . gmdate('r', filemtime($filename)));
		$f=fopen($filename, 'r');
		header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
		if ($from) fseek($f, $from, SEEK_SET);
		if (!isset($to) or empty($to)) {
			$size=filesize($filename)-$from;
		} else {
			$size=$to;
		}
		$downloaded=0;
		while(!feof($f) and !connection_status() and ($downloaded<$size)) {
			echo fread($f, 512000);
			$downloaded+=512000;
			flush();
		}
		fclose($f);
	}
	
}

/* end of file */