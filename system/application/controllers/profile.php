<?php
class Profile extends Controller {

	private $_view_deleted = FALSE;
	private $per_page = 20;
	private $page = 1;

	function Profile() {
		parent::Controller();

		$data = get_app_vars();
		$this->user_id=isset($data['user_id'])?$data['user_id']:0;
		
		$lng = $this->db_session->userdata('user_lang');
		$this->lang->load('phh',$lng);
	}
    
	function index()
	{
		$this->view();
	}

	function viewdeleted() {
		$this->_view_deleted = TRUE;
		$this->view();
		$this->_view_deleted = FALSE;
	}

	function view_profile (){
		$_user_id = $this->db_session->userdata('user_id');
		if (!empty ($_user_id))	
			$this->view();
		else
			redirect (base_url() . 'register');		
	}
	
	function photos_sort_order(){
		
		$user_id = intval($this->uri->segment(3));		
		$sort_order = $this->uri->segment(4);
		$sort_as = $this->uri->segment(5);
		
		
		$user_id = ($user_id > 0)? $user_id: $this->db_session->userdata('user_id');
		$my = ($this->db_session->userdata('user_group') == "")? FALSE: TRUE;
		$my = ($my && ($this->db_session->userdata('user_id') == $user_id))? TRUE: FALSE;
		
		if($my) $user_id = $this->db_session->userdata('user_id');
		else $user_id = intval($this->uri->segment(3));
		
		$sort_array = array(
			'date' => array('sort_by' => false, 'sort_as' => 'desc'),
			'title' => array('sort_by' => false, 'sort_as' => 'asc'),
			'popular' => array('sort_by' => false, 'sort_as' => 'desc')
		);
		
		$order_by = "";
		$_sort_order = 'date';
		$_sort_as = 'asc';
		
		if(!empty($sort_order)) {
			if($sort_order == 'date') {
				$order_by = 'photos.date_added';
				$_sort_order = 'date';				
				
				if(!empty($sort_as)) {
					if($sort_as == 'asc'){
						$sort_array['date']['sort_by'] = true;
						$sort_array['date']['sort_as'] = 'desc';
						$_sort_as = 'asc';
					}
					elseif ($sort_as == 'desc') {
						$sort_array['date']['sort_by'] = true;
						$sort_array['date']['sort_as'] = 'asc';
						$_sort_as = 'desc';
					}
				}
				
			} elseif ($sort_order == 'title') {
				$order_by = 'photos.title';
				$_sort_order = 'title';
				
				if(!empty($sort_as)) {
					if($sort_as == 'asc'){
						$sort_array['title']['sort_by'] = true;
						$sort_array['title']['sort_as'] = 'desc';
						$_sort_as = 'asc';
					}
					elseif ($sort_as == 'desc') {
						$sort_array['title']['sort_by'] = true;
						$sort_array['title']['sort_as'] = 'asc';
						$_sort_as = 'desc';
					}
				}
				
			} elseif ($sort_order == 'popular') {
				$order_by = 'see_cnt';
				$_sort_order = 'popular';
				
				if(!empty($sort_as)) {
					if($sort_as == 'asc'){
						$sort_array['popular']['sort_by'] = true;
						$sort_array['popular']['sort_as'] = 'desc';
						$_sort_as = 'asc';
					}
					elseif ($sort_as == 'desc') {
						$sort_array['popular']['sort_by'] = true;
						$sort_array['popular']['sort_as'] = 'asc';
						$_sort_as = 'desc';
					}
				}
			}
			if(!empty($sort_as) && !empty($order_by)) {
				if($sort_as == 'asc' || $sort_as == 'desc') {
					$order_by .= ' '.$sort_as;
				}
			}
		} else {
			$order_by = 'photos.date_added desc';
			$sort_array['date']['sort_by'] = true;
			$sort_array['date']['sort_as'] = 'asc';
			$_sort_order = 'date';
			$_sort_as = 'desc';
		}
		
		$sort_array['order_by'] = $order_by;
		$sort_array['sort_order'] = $_sort_order;
		$sort_array['sort_as'] = $_sort_as;		
		$sort_array['user_id'] = $user_id;		
		
		return $sort_array;
	}
	
	function view()
	{
		$_user_id = intval($this->uri->segment(3));
		$_user_id = ($_user_id > 0)? $_user_id: $this->db_session->userdata('user_id');
		$my = ($this->db_session->userdata('user_group') == "")? FALSE: TRUE;
		$my = ($my && ($this->db_session->userdata('user_id') == $_user_id))? TRUE: FALSE;
		if ($_user_id == 0)
			redirect('', 'location');

		$data = array("my" => $my);

        $data['user'] = modules::run('users_mod/users_ctr/profile_get',$_user_id);

		$this->config->load('ads');
		$cfg = $this->config->item('profile');
		$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);
        
		//permissions
        $registered =  $this->db_session->userdata('user_id');

        if (empty($registered)) $registered = 0;

        if  ($registered == 0)
            $erotic = -1;
        else  {
            $erotic = $this->db_session->userdata('erotic_allow');
            if (!$erotic)
              $erotic = modules::run('users_mod/users_ctr/get_age', $registered);
        }
        //end permissions
        
        if (!empty ($data['user']))
        {
            $data['avatar'] = modules::run('users_mod/users_ctr/get_user_avatar', $_user_id);
  		
            $moderation_state = null;
            
			if ($this->_view_deleted)
				$moderation_state = ($my === TRUE) ? MOD_DELETED : 1;    			
    		else
    			$moderation_state = ($my === TRUE) ? 0 : 1; 
    		
			$data['Balls'] = $this->rating->getBalls('user',$_user_id);
			$data['SeeCnt'] = $this->rating->getSeeCnt('user',$_user_id);
    		    		
			if ($this->_view_deleted) {
				$data['view_deleted'] = TRUE;
			}
			
			$page_number = $this->uri->segment(7);
			$this->page = empty($page_number) ? 1 : $page_number;
			if($this->page >= 1 && $this->page <= 5) $this->num_links = 25;
			elseif($this->page > 5 && $this->page <= 10) $this->num_links = 19;
			elseif($this->page > 10 && $this->page <= 14) $this->num_links = 15;
			elseif($this->page > 14 && $this->page <= 25) $this->num_links = 13;
			elseif ($this->page > 25 && $this->page <= 94) $this->num_links = 12;
			elseif ($this->page > 94 && $this->page <= 102) $this->num_links = 11;
			elseif ($this->page > 102) $this->num_links = 10;
			
			$sort_array = $this->photos_sort_order();
			$data['sort_array'] = $sort_array;
			$data['cpage'] = $this->page;
			
			$order_by = $sort_array['order_by'];

    		if ($my) {
    			if ($this->_view_deleted){
    				$data['photos'] = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, $_user_id, MOD_DELETED, 1, -1, $this->per_page, $this->page, true, $order_by);
    			} else {
    				$data['photos'] = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, $_user_id, 0, 1, -1, $this->per_page, $this->page, true, $order_by);
    			}
    		}
    		else
    			$data['photos'] = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, $_user_id, MODERATION_STATE, $registered, $erotic, $this->per_page, $this->page, true, $order_by);
    		
    		$data['cnt_photos'] = $data['photos']['count'];
			unset($data['photos']['count']);

			$data['categories'] = modules::run('catalog_mod/catalog_ctr/get_categories_list');
			$data['competitions'] = modules::run('competition_mod/competition_ctr/get_competitions_list');				
			$data['photos'] = modules::run('gallery_mod/gallery_ctr/small_img_prepare', $data['photos']);			
			if(!empty($data['photos'])) {
				foreach ($data['photos'] as $photo) {
					$photo->competition_id = modules::run('competition_mod/competition_ctr/get_competitions_by_photos', $photo->photo_id);
					$photo->id_album_main = modules::run('gallery_mod/gallery_ctr/get_albums_main_photo_list', $photo->album_id);
				}
			}					
			$data['view_allowed'] = array(
				2 => lang('view_public'),
				1 => lang('view_registered'),
				0 => lang('view_private')
			);
			if ($this->_view_deleted)
				$page_url = base_url().'profile/viewdeleted/'.$_user_id.'/'.$sort_array['sort_order'].'/'.$sort_array['sort_as'].'/page/';
			else
				$page_url = base_url().'profile/view/'.$_user_id.'/'.$sort_array['sort_order'].'/'.$sort_array['sort_as'].'/page/';
			
			$data['paginate_args'] = array(
				'total_rows'  => $data['cnt_photos'],
				'per_page'    => $this->per_page,
				'cur_page'    => $this->page,
				'num_links' => 25,
				'base_url'  => $page_url,
				'uri_segment' => 8
			);
			
			$this->config->load('../modules/gallery_mod/config/config');
			$data['allowed_types'] = explode('|', $this->config->item('img_types'));
			$data['allowed_types'] = '*.'.implode(';*.', $data['allowed_types']).';';
			$data['file_max_size'] = $this->config->item('file_max_size');
			$data['deleted_cnt'] = modules::run('gallery_mod/gallery_ctr/get_deleted_count_per_user', $_user_id);
    		if ($my)
    		{
    			if ($this->_view_deleted){
	    			$data['albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', MOD_DELETED, $registered, $erotic, $_user_id);
	    		} else {
	    			$data['albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', 'all', $registered, $erotic, $_user_id);	
	    		}

			} else {
				$data['albums'] = modules::run('gallery_mod/gallery_ctr/get_sitebar_albums', MODERATION_STATE, $registered, $erotic, $_user_id);
			}

    		$data['all_albums'] = $data['albums'];
    		$data['all_albums_cnt'] = (!empty($data['albums']))?count($data['albums']):0;	
        }
        else set_error(lang("page_not_found"));
        cache_clear();
		$this->load->view('_user_profile',$data);
	}
	
	function edit()
	{
		$_user_id = $this->db_session->userdata('user_id');
		if (empty ($_user_id))	
			redirect (base_url() . 'register');
		
		$saved = FALSE;

		if (isset($_POST['user_name']))
		{
			$saved = modules::run('users_mod/users_ctr/profile_save');
		}
		$data['profile_edit_block'] = modules::run('users_mod/users_ctr/profile_edit');
		$this->load->view('_user_profile_edit_new', $data);
	}
	
	function save() {
		modules::run('users_mod/users_ctr/profile_save');
		
		if (isset_errors()) {
			$this->db_session->set_flashdata('errors', get_errors());
		} else {
			$this->db_session->set_flashdata('message', lang('settings_save_well'));
		}
		
		redirect(base_url()."profile/edit");
	}
    
	function imedit() {
		if (empty($_POST)) show_404(lang('page_not_found'));
		$_POST['photo_name'] = mysql_real_escape_string($_POST['photo_name']);
		$_POST['photo_desc'] = mysql_real_escape_string($_POST['photo_desc']);
		$erotic = 0;
		if (isset($_POST['erotic_p']) && ($_POST['erotic_p'] == 'on')){
			$erotic = 1;
		}
		$photo_data = array(
			'photo_id'      => $_POST['photo_id'],
			'title'         => $_POST['photo_name'],
			'date_modified' => date("Y-m-d H:i:s"),
			'view_allowed'  => $_POST['view_allowed'],
			'view_password' => $_POST['photo_password'],
			'erotic_p'      => $erotic,
			'description'   => $_POST['photo_desc']
		);
		modules::run('gallery_mod/gallery_ctr/update_photo', $photo_data);
		modules::run('gallery_mod/gallery_ctr/update_photo_album', $_POST['photo_id'], $_POST['photo_category'], $_POST['photo_album']);
		if (!empty($_POST['photo_competition'])){
			$user_id = $this->db_session->userdata('user_id');
			$competition_info = array(
				'comp_work_submit' => TRUE,
				'title'            => $_POST['photo_name'],
				'description'      => $_POST['photo_desc'],
				'user_id'          => $user_id,
				'competition_id'   => $_POST['photo_competition']
			);
			modules::run('competition_mod/competition_ctr/add_work_to_competition', $_POST['photo_id'], $competition_info);
		}
		if (isset($_POST['photo_main_page']) && ($_POST['photo_main_page'] = "on")){
			modules::run('gallery_mod/gallery_ctr/set_album_main_photo', $_POST['photo_album'], $_POST['photo_id']);
		} else {
			modules::run('gallery_mod/gallery_ctr/clear_album_main_photo', $_POST['photo_album'], $_POST['photo_id']);
		}
		redirect(base_url().'profile/view/'.$this->uri->segment(3), 'location');
	}

	function imupload() {
		$this->benchmark->mark('code_start');
		$CI =& get_instance();
		$photo_uploads_temp =  $CI->config->slash_item('photo_uploads_temp');
		log_message('error', '$photo_uploads_temp: '.$photo_uploads_temp);
		if (!empty($_FILES)) 
		{
			log_message('debug', 'DEBUG. $_FILES '.var_export($_FILES, true));
			$tempFile = $_FILES['Filedata']['tmp_name'];
			
			$fileParts  = pathinfo($_FILES['Filedata']['name']);
			$type = imext2mime(array_pop(explode(".", $_FILES['Filedata']['name'])));
			
			$tmpname = 'fu'.substr(md5(microtime()), 0, 5).".tmp";			
			$targetFile = $photo_uploads_temp.$tmpname;
			
			log_message('debug', 'DEBUG. $tmpname '.$tmpname);
			log_message('debug', 'DEBUG. $targetFile '.$targetFile);
			
			$res = move_uploaded_file($tempFile,$targetFile);
			log_message('debug', 'DEBUG. $res muve upload. '.$res);
			
			$data = (Object)array('name' => urlencode($_FILES['Filedata']['name']), 
									'tmp_name' => urlencode($targetFile),
									'size' => $_FILES['Filedata']['size'],
									'type' => $type
								);
			$data = json_encode($data);
			
			$this->output->set_output($data);
			/*$data='{name: "'.urlencode($_FILES['Filedata']['name']).'", 
					tmp_name: "'. urlencode($targetFile).'", 
					size: '.$_FILES['Filedata']['size'].', 
					type: "'.$type.'"}';
		
			echo ($data);*/
		}
		else
	    	log_message("error", "imupload - _FILES is empty");
	    $this-> benchmark-> mark('code_end');
		log_message('debug', 'Time generation imupload:'.$this->benchmark->elapsed_time('code_start', 'code_end'));
	}

	function imadd() {
		$this->benchmark->mark('code_start');
		if (empty($_POST)) show_404(lang('page_not_found'));
		
		$user_id = $this->db_session->userdata('user_id');
		
		$data = get_app_vars();		
		$type = pathinfo($_POST['p_filename']);
		$type = $type['extension'];
		log_message('info', '$POST data. '.var_export($_POST, true));
		
		$err = "";
        $mes = "Upload photo...";
		if (($type == 'zip') || ($type == 'rar'))
		{
			$this->config->load('upload');
			$this->config->load('../modules/gallery_mod/config/config');
			$dirpath = $this->config->item('upload_path').'arch/';
			if ($this->_UnArchive($_POST['p_tmp_name'], $dirpath, $type)) {
				$mask = explode('|', $this->config->item('img_types'));
				$mask = '{*.'.implode(',*.', $mask).'}';
				if ($objs = glob($dirpath.$mask, GLOB_BRACE)) {
                    $files_cnt=1;
                    foreach($objs as $obj) {
                        $pathinfo = pathinfo($obj);
                        if (!eregi($pathinfo['extension'],$this->config->item('img_types'))){continue;}
                       
						$data = array(
							'p_filename' => $pathinfo['basename'],
							'p_tmp_name' => $dirpath.$pathinfo['basename'],
							'p_filesize' => filesize($dirpath.$pathinfo['basename']),
							'p_filetype' => imext2mime($pathinfo['extension']),
							'p_title'    => substr($pathinfo['basename'], 0, (strlen($pathinfo['extension'])+1)*-1),
							'p_descr'    => $_POST['p_descr'],
							'P_alloved'  => $_POST['P_alloved'],
							'p_erotic'   => $_POST['p_erotic'],
							'p_cat_id'   => $_POST['p_cat_id'],
							'p_album_id' => $_POST['p_album_id'],
							'p_pwd'      => $_POST['p_pwd'],
							'p_comp'     => isset($_POST['p_comp'])?$_POST['p_comp']:0,
							'u_id'       => $user_id
						);
						
						$photo_id = modules::run('gallery_mod/gallery_ctr/add_photo', $data);
                        $this->rating->calcTotalFotoBalls($photo_id );
                        if ($photo_id !== FALSE) {
							modules::run('gallery_mod/gallery_ctr/place_photo', $photo_id, mysql_real_escape_string($_POST['p_cat_id']), mysql_real_escape_string($_POST['p_album_id']));
                            $files_cnt++;
						} else {							
							log_message("error", "profile/imadd can'n generate images");
						}
						if(file_exists($pathinfo['dirname'].'/'.$pathinfo['basename']))
							unlink($pathinfo['dirname'].'/'.$pathinfo['basename']);
					}
				}
				removeDirRec($dirpath);
			}
            $mes=lang("upload_place")."(".$files_cnt.")";
		} else {
			$photo_id = modules::run('gallery_mod/gallery_ctr/add_photo', $_POST);
			
			if ($photo_id !== FALSE){
				modules::run('gallery_mod/gallery_ctr/place_photo', $photo_id, mysql_real_escape_string($_POST['p_cat_id']), mysql_real_escape_string($_POST['p_album_id']));
                $this->rating->calcTotalFotoBalls($photo_id );
                $mes=lang("upload_place")." (1)";
			} else {
				log_message("error", "profile/imadd can'n generate images");
                $mes=lang("upload_gen_err");//can't generate images";
                $err=$mes;
			}
		}
		@unlink($_POST['p_tmp_name']);
		
		$data = (Object)array("err" => $err,"mes" =>$mes);
		$data = json_encode($data);
		$this-> benchmark-> mark('code_end');
		log_message('debug', 'Time generation imadd:'.$this->benchmark->elapsed_time('code_start', 'code_end'));
		echo $data;
		exit;
	}

	function upload() {
		$this->benchmark->mark('code_start');
		
		$user_id = $this->db_session->userdata('user_id');
		
		$this->load->helper('file');
		log_message('error', 'UPLOAD. start');
		
		$mes = "";
		$err = "";
		
		if (!empty($_FILES)) {
			
			log_message('error', 'UPLOAD. $_FILES: '.var_export($_FILES, true));
			log_message('error', 'ls command: '.exec("ls -l ".$_FILES['userfile']['tmp_name']));
			
			$upload_data = modules::run('gallery_mod/gallery_ctr/upload_photo', 'userfile');
			
			if(empty($upload_data)) {
				$err = "Этот файл не был загружен";
				log_message('error', 'file '.$_FILES['userfile']['name']." has not been uploaded");				
			}
			if(!empty($_POST) && !empty($upload_data)) {
				log_message('error', 'UPLOAD. $_POST: '.var_export($upload_data, true));
				
				$title = $this->input->post('photo_title');
				$description = $this->input->post('photo_desc');
				$view_allowed = $this->input->post('view_allowed');
				$erotic = $this->input->post('erotic');				
				$category_id = $this->input->post('cat');
				$album_id = $this->input->post('upload_img_alb');				
				$pwd = $this->input->post('photo_password');
				$pwd_confirm = $this->input->post('photo_password_confirm');
				
				if(empty($title)) {
					$err = "Необходимо внести название к фотографии";
					unlink($upload_data['full_path']);
				} elseif (empty($category_id)) {
					$err = "Необходимо выбрать категорию";
					unlink($upload_data['full_path']);
				} elseif (empty($album_id)) {
					$err = "Необходимо выбрать альбом";
					unlink($upload_data['full_path']);
				}
				else {
				
					$type = substr($upload_data['file_ext'], 1);
					
					$photos = array();
					
					if (($type == 'zip') || ($type == 'rar'))
					{	
						$upload_data_zip = $upload_data;
										
						$config = $this->load->config('upload');
						$dirpath = $config['upload_path'].'arch/';
						if ($this->_UnArchive($upload_data['full_path'], $dirpath, $type)) {
							$mask = explode('|', $config['allowed_types']);
							$mask = '{*.'.implode(',*.', $mask).'}';
							
							if ($objs = glob($dirpath.$mask, GLOB_BRACE)) {
			                    $files_cnt=1;
								
			                    foreach($objs as $obj) {
									
									$pathinfo = pathinfo($obj);
									if (!eregi($pathinfo['extension'],$config['allowed_types'])){continue;}
									
									$val = array(
										'p_filename' => $pathinfo['basename'],
										'p_tmp_name' => $dirpath.$pathinfo['basename'],
										'p_filesize' => filesize($dirpath.$pathinfo['basename']),
										'p_filetype' => imext2mime($pathinfo['extension'])
									);
									
									$upload_data = modules::run('gallery_mod/gallery_ctr/do_upload_photo', $val);
								
									$photo_data = $upload_data;
									$exif = @exif_read_data($upload_data['full_path']);
									
									$photos[] = array('photo_data' => $photo_data, 'exif' => $exif);
								}
							}
							removeDirRec($dirpath);
							unlink($upload_data_zip['full_path']);
						}
			            $mes=lang("upload_place")."(".$files_cnt.")";
					} else {					
						$photo_data = $upload_data;
						$exif = @exif_read_data($_FILES['userfile']['tmp_name']);
						
						$photos[] = array('photo_data' => $photo_data, 'exif' => $exif);
					}
					
					if(!empty($photos)) {
						foreach ($photos as $photo) {
							
							$upload_data = $photo['photo_data'];
							
							$post_data = array(
								'title' => $title,
								'description' => $description,
								'view_allowed' => $view_allowed,
								'erotic_p' => (($erotic == true) ? '1': 0),
								'pwd' => $pwd,
								'pwd_confirm' => $pwd_confirm,
								'cat' => $category_id,
								'upload_img_alb' => $album_id,
								'exif' => $photo['exif'],
								'user_id' => $user_id,
								'moderation_state' => 0
							);
							log_message('debug', 'UPLOAD. $post_data: '.var_export($post_data, true));
							
							$photo_id = null;
							$photo_id = modules::run('gallery_mod/gallery_ctr/set_photo', $upload_data, $post_data);
							
							if ($photo_id !== FALSE && is_integer($photo_id)){
								modules::run('gallery_mod/gallery_ctr/place_photo', $photo_id, $category_id, $album_id);
				                $this->rating->calcTotalFotoBalls($photo_id);
				                $mes=lang("upload_place")." (1)";
				                
							} else {
								log_message("error", "profile/imadd can'n generate images");
				                $mes=lang("upload_gen_err");//can't generate images";
				                $err=$mes;
							}						
						}
					}				
					$this-> benchmark-> mark('code_end');
					log_message('debug', 'Time generation:'.$this->benchmark->elapsed_time('code_start', 'code_end'));
				}
			}			
		}
		$this->db_session->set_flashdata('error', $err);
		$this->db_session->set_flashdata('message', $mes);
		
		redirect('profile/view_profile');
	}
	
	function albumedt() {
		if (empty($_POST)) show_404(lang('page_not_found'));
		$user_id = $this->db_session->userdata('user_id');
		modules::run('gallery_mod/gallery_ctr/album_edit', $user_id, $_POST);
		redirect(base_url().'profile/view/'.$this->uri->segment(3), 'location');
	}

	function albumedt1($user_id) {
		if (empty($_POST)) show_404(lang('page_not_found'));
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($dbuser_id == $user_id) 
		{
			modules::run('gallery_mod/gallery_ctr/album_edit', $user_id, $_POST);
			redirect(getenv("HTTP_REFERER"), 'location');
		}
		else 
		{
			show_404(lang('page_not_found'));
		}
	}
	
	function imdel($user_id =null, $photo_id = null, $sort_type = 1, $sort_order = 'a', $cpage = 1) {
		if (empty ($user_id) )
				show_404(lang('page_not_found'));
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($dbuser_id == $user_id) {
			if (empty ($photo_id) )
				show_404(lang('page_not_found'));
			modules::run("gallery_mod/gallery_ctr/delete_photo", $photo_id);
			redirect(base_url()."profile/view/$user_id");
		} else {
			show_404(lang('page_not_found'));
		}
	}

	function albdel($user_id = null, $album_id = null, $sort_type = 1, $sort_order = 'a', $cpage = 1) {
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($dbuser_id == $user_id) {
			if (empty ($album_id) )
				show_404(lang('page_not_found'));
			modules::run("gallery_mod/gallery_ctr/delete_album", $album_id, $user_id);
			redirect(base_url()."profile/view/$user_id");
		} else {
			show_404(lang('page_not_found'));
		}
	}

	function albdel1($user_id = null, $album_id = null) {
		if (empty ($album_id) || empty ($user_id))
				show_404(lang('page_not_found'));
		
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($dbuser_id == $user_id) {
			modules::run("gallery_mod/gallery_ctr/delete_album", $album_id, $user_id);
			redirect(getenv("HTTP_REFERER"), 'location');
		} else {
			show_404(lang('page_not_found'));
		}
	}
	
	function albrevert($user_id = null, $album_id = null, $sort_type = 1, $sort_order = 'a', $cpage = 1) {
		if (empty ($album_id) || empty ($user_id))
				show_404(lang('page_not_found'));
				
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($user_id == $dbuser_id) {
			modules::run("gallery_mod/gallery_ctr/revert_album", $album_id, $user_id);
			redirect(base_url()."profile/viewdeleted/$user_id");
		} else {
			show_404(lang('page_not_found'));
		}
	}

	function imrevert($user_id = null, $image_id = null, $sort_type = 1, $sort_order = 'a', $cpage = 1) {
		if (empty ($user_id) || empty ($image_id))
				show_404(lang('page_not_found'));
				
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($user_id == $dbuser_id) {
			modules::run("gallery_mod/gallery_ctr/revert_image", $image_id, $user_id);
			redirect(base_url()."profile/viewdeleted/$user_id");
		} else {
			show_404(lang('page_not_found'));
		}
	}

	function imremove($user_id = null, $image_id = null, $sort_type = 1, $sort_order = 'a', $cpage = 1){
		if (empty ($user_id) || empty ($image_id))
				show_404(lang('page_not_found'));
				
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($user_id == $dbuser_id) {
			modules::run("gallery_mod/gallery_ctr/remove_photo", $image_id);
			redirect(base_url()."profile/viewdeleted/$user_id");
		} else {
			show_404(lang('page_not_found'));
		}
	}
	
	function alremove($user_id = null, $album_id = null){
		if (empty ($user_id) || empty ($album_id))
				show_404(lang('page_not_found'));
				
		$dbuser_id = $this->db_session->userdata('user_id');
		if ($user_id == $dbuser_id) {
			modules::run("gallery_mod/gallery_ctr/remove_album", $album_id);
			redirect(base_url()."profile/viewdeleted/$user_id");
		} else {
			show_404(lang('page_not_found'));
		}
	}
	
	/**
	 * 
	 * @return boolean 
	 * @param String $arcpath - archive
	 * @param object $dirpath - path to save unarchived files
	 * @param object $type - type of archive. Alloved: zip, rar.
	 */
	function _UnArchive($arcpath, $dirpath, $type){

		if (!file_exists($arcpath) || !is_file($arcpath)) {
			return FALSE;
		}
		if (file_exists($dirpath) && is_dir($dirpath)) {
			removeDirRec($dirpath);
		}
		if(!is_dir($dirpath)) {
			mkdir($dirpath, 0777);
		}		
		switch($type) {
			case 'zip':
				$zip_file = zip_open($arcpath);
				if ($zip_file) {
					while ($entry = zip_read($zip_file)) {
						if (zip_entry_filesize($entry) > 0) {
							if (zip_entry_open($zip_file, $entry, "r")) {
								$file = basename(zip_entry_name($entry));
								$fp = fopen($dirpath.$file, "w+");
								$buf = zip_entry_read($entry, zip_entry_filesize($entry));
								zip_entry_close($entry);
								fwrite($fp, $buf);
								fclose($fp);
							}
						}
					}
				} else {
					return FALSE;
				}
				break;
			case 'rar':
				log_message('error', " path to temp file" . $arcpath);	

				$rar_file = rar_open($arcpath);
				if ($rar_file === FALSE) {
				log_message('error', " rar_open_error" . $arcpath);	
					return FALSE;
				}
					log_message('error', " rar_open_ok" . $arcpath);	
				$entries_list = rar_list($rar_file);
				foreach ($entries_list as $entry) {
					if (!empty($entry->crc)) {
						log_message('error', " add_photo photoid" . $dirpath.basename($entry->name));	
						//$entry->extract(realpath('.'), $dirpath.basename($entry->name));
						$entry->extract(false, $dirpath.basename($entry->name));
					}
				}
				break;
			default:
				return FALSE;
				break;
		}
		return TRUE;
	}

}

function imext2mime($ext){
	$types = array(
		'gif'  => 'image/gif',
		'xbm'  => 'image/x-xbitmap',
		'xpm'  => 'image/x-xpixmap',
		'png'  => 'image/x-png',
		'ief'  => 'image/ief',
		'jpeg' => 'image/jpeg',
		'jpg'  => 'image/jpeg',
		'jpe'  => 'image/jpeg',
		'tiff' => 'image/tiff',
		'tif'  => 'image/tiff',
		'rgb'  => 'image/rgb',
		'g3f'  => 'image/g3fax',
		'xwd'  => 'image/x-xwindowdump',
		'pict' => 'image/x-pict',
		'ppm'  => 'image/x-portable-pixmap',
		'pgm'  => 'image/x-portable-graymap',
		'pbm'  => 'image/x-portable-bitmap',
		'pnm'  => 'image/x-portable-anymap',
		'bmp'  => 'image/bmp',
		'ras'  => 'image/x-cmu-raster',
		'pcd'  => 'image/x-photo-cd',
		'cgm'  => 'image/cgm',
		'mil'  => 'image/x-cals',
		'cal'  => 'image/x-cals',
		'fif'  => 'image/fif',
		'dsf'  => 'image/x-mgx-dsf', 
		'cmx'  => 'image/x-cmx',
		'wi'   => 'image/wavelet',
		'dwg'  => 'image/x-dwg',
		'dxf'  => 'image/x-dxf',
		'svf'  => 'image/vnd.svf', 
		'bw'   => 'image/x-sgi-bw',
		'rgba' => 'image/x-sgi-rgba',
		'sgi'  => 'image/x-sgi-rgba',
		'eps'  => 'image/x-eps',
		'epsi' => 'image/x-eps',
		'epsf' => 'image/x-eps'
	);
	$ext = strtolower($ext);
	if ($ext[0] == '.') {
		$ext = substr($ext, 1);
	}
	if (isset($types[$ext])) {
		return $types[$ext];
	} else {
		return "image/*";
	}
}

function removeDirRec($dir) {
	exec("rm  -f /usr/local/apache2/htdocs/uploads/arch/*.*", $output);
	if ($objs = glob($dir."*")) {
		foreach($objs as $obj) {
			is_dir($obj) ? removeDirRec($obj) : unlink($obj);
		}
	}
//	rmdir($dir);
}
/* end of file */
	
