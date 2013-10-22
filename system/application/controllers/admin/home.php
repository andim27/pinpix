<?php
class Home extends Controller {

	function Home() {
		parent::Controller();

		$lng = $this->db_session->userdata('user_lang');

		$this->lang->load('phh',$lng);
		$this->lang->load('admin',$lng);

		$this->load->helper('BI_moderation');
		$this->load->library('email');
		$this->config->load('email');
	}

	function index()
	{		
		$data['auth_block'] = modules::run('users_mod/users_ctr/register');

		$user_id = $this->db_session->userdata('user_id');

		if ( ! empty($user_id))
		{
			$can = kh_acl_check($this->db_session->userdata('user_group'), 'adminarea', 'View');
	        if ( ! $can) {
				redirect('', 'location');
	        }
		}
		$this->load->view('admin/_home', $data);
	}

    function ajax_actions() {
  	$action = $this->input->post('action');
		$data = '';
		switch($action) {
			case "get_admin_menu":
				$user_id = $this->db_session->userdata('user_id');
				if (! empty($user_id)) {
					$can = kh_acl_check($this->db_session->userdata('user_group'), 'adminarea', 'View');
					if ($can) {
						$data = $this->load->view('admin/admin_menu', '', TRUE);
					} else {
						$data = '';
					}
				}
			break;
			case "set_photo_filter":
				try {
					$photo_filter = array(
						'category'   => $_POST['category'],
						'username'   => $_POST['username'],
						'title'      => $_POST['title'],
						'date'       => array(
							'start' => (empty($_POST['date_from'])?null:date("Y-m-d H:i:s", strtotime($_POST['date_from']))),
							'end'   => (empty($_POST['date_till'])?null:date("Y-m-d H:i:s", strtotime($_POST['date_till'])))
						),
						'moderation' => $_POST['moderation']
					);
					$this->db_session->set_flashdata('photo_filter', $photo_filter);
					$data = 1;
				} catch (Exception $e) {
					$data = -1;
				}
				break;
			case "clear_photo_filter":
				try {
					$this->db_session->set_flashdata('photo_filter', null);
				} catch (Exception $e) {
					$data = -1;
				}
				break;				
			case "clear_user_filter":
				try {
					$this->db_session->set_flashdata('user_filter', null);
				} catch (Exception $e) {
					$data = -1;
				}
				break;
			case "set_user_filter":
				try {
					$user_filter = array(
						'login'     => $_POST['login'],
						'username'   => $_POST['username'],
						'usersname'  => $_POST['usersname'],
						'useremail'  => $_POST['useremail'],
						'date'       => array(
							'start'  => (empty($_POST['date_from'])?null:date("Y-m-d H:i:s", strtotime($_POST['date_from']))),
							'end'    => (empty($_POST['date_till'])?null:date("Y-m-d H:i:s", strtotime($_POST['date_till'])))
						),
						'moderation' => $_POST['moderation']		
					);
					$this->db_session->set_flashdata('user_filter', $user_filter);
					$data = 1;
				} catch (Exception $e) {
					$data = -1;
				}
				break;
			case "set_album_filter":
				try {
					$album_filter = array(
						'category'   => $_POST['category'],
						'username'   => $_POST['username'],
						'title'      => $_POST['title'],
						'date'       => array(
							'start' => (empty($_POST['date_from'])?null:date("Y-m-d H:i:s", strtotime($_POST['date_from']))),
							'end'   => (empty($_POST['date_till'])?null:date("Y-m-d H:i:s", strtotime($_POST['date_till'])))
						),
						'moderation' => $_POST['moderation']
					);
					$this->db_session->set_flashdata('album_filter', $album_filter);
					$data = 1;
				} catch (Exception $e) {
					$data = -1;
				}
				break;
			case "clear_album_filter":
				try {
					$this->db_session->set_flashdata('album_filter', null);
				} catch (Exception $e) {
					$data = -1;
				}
				break;
		}
		$this->output->set_output($data);
	}

	function _remap($method) {
		if ( $method=='ajax_actions') {
			$this->$method();
		}
		$user_id = $this->db_session->userdata('user_id');

		if ( ! empty($user_id)) {
			$can = kh_acl_check($this->db_session->userdata('user_group'), 'adminarea', 'View');
			$this->$method();
		} elseif ($method!='index') {
			redirect(url('admin_home_url'), 'location');
		} else {
			$this->index();
		}
	}
    function pma() {
      $data=array();
      echo $this->load->view('admin/_pma', $data,true);
    }
	function users()
	{

		$data['auth_block'] = modules::run('users_mod/users_ctr/authorize');

		if ( isset($_POST['block']) && $_POST['block'] == 'user_details' )
			$data['user_block'] = modules::run('users_mod/users_ctr/get_user_details');
		else 
			$data['user_block'] = modules::run('users_mod/users_ctr/get_all_users_list');
				
		$this->load->view('admin/_users', $data);
	}	

	function catalog()
	{
		$data['auth_block'] = modules::run('users_mod/users_ctr/authorize');
		
		if (isset($_POST['removed_lft'])) 
		{
			modules::run('catalog_mod/catalog_ctr/delete_category');
		}
		$context = 'admin';
		$data['catalog_block'] = modules::run('catalog_mod/catalog_ctr/view_tree', $context);
		$this->load->view('admin/_catalog', $data);
	}

	function category()
	{
		$data['auth_block'] = modules::run('users_mod/users_ctr/authorize');
		
		$save_cat = FALSE;
		
		if ($_POST)
		{
			$save_cat = modules::run('catalog_mod/catalog_ctr/save_category');
		}
		
		if ($save_cat)
		{
			redirect(url('admin_catalog_url'));
		} 
		else 
		{
			$cat_id = $this->uri->segment(3);
			$data['category_block'] = modules::run('catalog_mod/catalog_ctr/edit_category', $cat_id);
			$this->load->view('admin/_category', $data);
		}
	}

	function tags()
	{
		$data['auth_block'] = modules::run('users_mod/users_ctr/authorize');
		
		$filter = MOD_NEW;
		$per_page = 5; 
		$page = uri_segment(4);
		$data['tags_block'] = modules::run('tags_mod/tags_ctr/view_tags_moderation', $filter, $page, $per_page);
		
		$this->load->view('admin/_tags',$data);
		
	}

	function comments()
	{
		if (!empty($_POST['ms_all'])) 
		{						
			$id = $_POST['cid'];
			$ms = $_POST['ms_all'];
				
			modules::run('comments_mod/comments_ctr/update_moderation_state', $id, $ms );
		}
			
		$data['auth_block'] = modules::run('users_mod/users_ctr/authorize');
		
		$filter = MOD_NEW;
		$per_page = 20; 
		$page = uri_segment(4);
		if(!empty($page)) {
			if($this->db_session->flashdata('comments_filter')){
	    		$filter = $this->db_session->flashdata('comments_filter');
	    		$this->db_session->keep_flashdata('comments_filter');
	    	}
		}
		
		$page = empty($page) ? 1 : $page;
		
		$data['comments_block'] = modules::run('comments_mod/comments_ctr/view_comments_moderation', $filter, $page,  $per_page, ' comment_date desc ');
		log_message('error', 'comments data: '.var_export($data['comments_block'], true));
		$this->load->view('admin/_comments', $data);
	}

	function photos() {

		$this->db_session->keep_flashdata('photo_filter');
	
		if (!empty($_POST['action']) && ($_POST['action'] == 'setAll') ) {

			$id = $_POST['cid'];
			$ms = $_POST['ms_all'];

			foreach ($id as $photo_id )
			{			
				$update = array(
					'photo_id' => $photo_id,
					'moderation_state' => $ms
				);
				modules::run('gallery_mod/gallery_ctr/update_photo', $update);
			}
			redirect('admin/photos');
		}
		else if (isset($_POST['photo_id']) && !empty($_POST['photo_id']))
		{
			$update = array(
				'photo_id'         => $_POST['photo_id'],
				'erotic_p'         => $_POST['erotic'],
				'moderation_state' => $_POST['moderation_state']
			);

			modules::run('gallery_mod/gallery_ctr/update_photo', $update);

			$oldms = $_POST['old_mod_state'];
			$ms = $_POST['moderation_state'];

			if ((($oldms == 1)&&($ms == 2))||(($oldms == 2)&&($ms == 1)))
			redirect($_SERVER['REQUEST_URI']);
			redirect('admin/photos');
		}
		
		define('PHOTOS_PER_PAGE', 10);
		$cpage = intval($this->uri->segment(5));
		$cpage = (empty($cpage))? "1": $cpage;
		$sort_order = $this->uri->segment(3);
		$sort_order = (empty($sort_order))? "desc": $sort_order;

		$data = array();
		$data['photo_filter'] = $this->db_session->flashdata('photo_filter');
		if (empty($data['photo_filter'])) {
			$data['photo_filter'] = array(
				'category'   => 0,
				'username'   => '',
				'title'      => '',
				'date'       => array(
					'start' => '',
					'end'   => ''
				),
				'moderation' => -999
			);
		}
		$data['photos']	= modules::run("gallery_mod/gallery_ctr/get_admin_photos_list", 1, $sort_order, $cpage, PHOTOS_PER_PAGE, $data['photo_filter']);
		$cnt = $data['photos']['cnt'];
		$data['photos'] = $data['photos']['photos'];
		$data['cpage'] = $cpage;
		$data['sort_order'] = $sort_order;

		$data['paginate_args'] = array(
			'total_rows' => $cnt,
			'per_page' => PHOTOS_PER_PAGE,
			'num_links' => 10,
			'cur_page' => $cpage,
			'uri_segment' => 5,
			'prev_link' => '&lt;',
			'next_link' => '&gt;',
			'first_link' => '&lt;&lt;',
			'last_link' => '&gt;&gt;',
			'base_url' => base_url().'admin/photos/'.$sort_order.'/page/'
		);

		$data['categories'] = modules::run('catalog_mod/catalog_ctr/get_categories_list');
		$this->load->view('admin/_photos', $data);
	}
	
    function editor() {
     $data['auth_block'] = modules::run('users_mod/users_ctr/register');
	 $user_id = $this->db_session->userdata('user_id');
     $photo_id=$this->uri->segment(3);
     $photo_cnt=$this->uri->segment(4);
	 if ( ! empty($user_id))
		{
			$can = kh_acl_check($this->db_session->userdata('user_group'), 'adminarea', 'View');
            if ( $photo_cnt != 1) {
             if ( ! $can) {
				redirect('', 'location');
                return;
	         }
            }
	 }
     $data  = array();
     $data['page']="editor";

     $data['photo_id']=$photo_id;
     $data['photos']     = modules::run("gallery_mod/gallery_ctr/get_all_photos",MOD_FEATURED_MAIN);
     $photo= $data['photos'][0];
     foreach ($data['photos'] as $item) {
           if  ($item->photo_id == $photo_id) {
               $photo = $item;
               break;
           }
     }

     $data['src_lg']   = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
     $data['src_md']   = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
     $data['src_head'] = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-head'.$photo->extension;
     $this->load->view('admin/_editor', $data);
    }
	
    function albums() {
		$this->db_session->keep_flashdata('album_filter');
		if (isset($_POST['album_id']) && !empty($_POST['album_id'])) {
			$update = array(
				'album_id'         => $_POST['album_id'],
				'erotic_p'         => $_POST['erotic'],
				'moderation_state' => $_POST['moderation_state']
			);
			modules::run('gallery_mod/gallery_ctr/update_album', $update);
			unset($update);
			$this->send_user_mail_album($_POST['album_id'], $_POST['moderation_state']);
			redirect('admin/albums');
		}
		define('ALBUMS_PER_PAGE', 20);
		$cpage = intval($this->uri->segment(5));
		$cpage = (empty($cpage))? "1": $cpage;
		$sort_order = $this->uri->segment(3);
		$sort_order = (empty($sort_order))? "asc": $sort_order;

		$data = array();
		$data['album_filter'] = $this->db_session->flashdata('album_filter');
		if (empty($data['album_filter'])) {
			$data['album_filter'] = array(
				'category'   => 0,
				'username'   => '',
				'title'      => '',
				'date'       => array(
					'start' => '',
					'end'   => ''
				),
				'moderation' => -999
			);
		}
		$data['albums']     = modules::run("gallery_mod/gallery_ctr/get_admin_albumss_list", 1, $sort_order, $cpage, ALBUMS_PER_PAGE, $data['album_filter']);
		$cnt                = $data['albums']['cnt'];
		$data['albums']     = $data['albums']['albums'];
		$data['cpage']      = $cpage;
		$data['sort_order'] = $sort_order;

		$data['paginate_args'] = array(
			'total_rows' => $cnt,
			'per_page' => ALBUMS_PER_PAGE,
			'num_links' => 10,
			'cur_page' => $cpage,
			'uri_segment' => 5,
			'base_url' => base_url().'admin/albums/'.$sort_order.'/page/'
		);

		$data['categories'] = modules::run('catalog_mod/catalog_ctr/get_categories_list');
		$this->load->view('admin/_albums', $data);
	}

	function pages() {
		$item_title = $this->input->post('title');
		$item_content = $this->input->post('content');
		$save = $this->input->post('save');
		
		$data = array();
		$page = "help";
		switch(strtolower($this->uri->segment(3))) {
			case 'faq': $page = "faq"; break;
			case 'agreement': $page = "agreement"; break;
			case 'contacts': $page = "contacts"; break;
			case 'jury1': $page = "jury1"; break;
			case 'jury2': $page = "jury2"; break;
			case 'jury3': $page = "jury3"; break;
			case 'conditions': $page = "conditions"; break;
			default: $page = "help"; break;
		}
		$data['page'] = $page;

		$lang = "ru";
		switch(strtolower($this->uri->segment(4))) {
			case 'en': $lang = "en"; break;
			case 'kz': $lang = "kz"; break;
			default: $lang = "ru"; break;
		}
		$data['lang'] = $lang;
		if ($save == 'save') {
			$content = '<?php'."\n\t".
								'$page_info = array();'."\n\t".
								'$page_info[\'title\'] = \''.$item_title.'\';'."\n\t".
								'$page_info[\'content\'] = \''.$item_content.'\';'."\n\n\t".
								'return $page_info;'."\n".
								'/* EOF */';
			file_put_contents(STATIC_PATH.'pages/'.$lang.'/'.$page.'.php', $content);
			redirect('admin/pages/'.$page.'/'.$lang);
		}
		$data['page_info'] = _loadPageData($page, $lang, $this->config->item('language'));
		$this->load->view('admin/_pages', $data);
	}

	function ads()
	{
		$data['auth_block'] = modules::run('users_mod/users_ctr/authorize');

		if ( isset($_POST['block']) && $_POST['block'] == 'ads_details' )
			$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_banner_details');
		else 
			$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_all_banners_list');
				
		$this->load->view('admin/_ads', $data);
	}
	
	function city()
    {
      	$data['auth_block'] = modules::run('users_mod/users_ctr/register');
		$user_id = $this->db_session->userdata('user_id');
		if ( ! empty($user_id))
		{
			$can = kh_acl_check($this->db_session->userdata('user_group'), 'adminarea', 'View');
	        if ( ! $can) {
				redirect('', 'location');
                return;
	        }
		}
        $data= modules::run('users_mod/users_ctr/get_user_city_place',$user_id);
        $this->load->view('admin/_city', $data);
    }
	
    function competitions()
	{
		$data['auth_block'] = modules::run('users_mod/users_ctr/authorize');

		if ( isset($_POST['block']) && $_POST['block'] == 'comp_details' )
			$data['comp_block'] = modules::run('competition_mod/competition_ctr/get_comp_details');
		else
			$data['comp_block'] = modules::run('competition_mod/competition_ctr/get_all_comp_list');
				
		$this->load->view('admin/_competitions', $data);
	}
    
	function competitions_photos()
	{
		$photo_id = $this->input->post('photo_id');
		$moderation_state = $this->input->post('moderation_state');
		$place_description = $this->input->post('place_description');
		$place = $this->input->post('place');
		$comp_id = 	intval($this->uri->segment(3));
		$comp_name = modules::run('competition_mod/competition_ctr/get_competition_name', $comp_id );
		
		if (!empty($photo_id)) 
		{
			if ($moderation_state != "")
			{
				$update = array(
					'competition_id' => $comp_id,
					'photo_id' => $photo_id,
					'moderation_state' => $moderation_state
				);
				modules::run('competition_mod/competition_ctr/update_comp', $update);
				unset($update);

				$this->send_user_mail_competition($photo_id, $moderation_state, $comp_name );
			}
			if (!empty ($place) || !empty ($place_description))
			{
				$update = array(
					'competition_id'    => $comp_id,
					'photo_id' 		    => $photo_id,
					'place_taken'       => $place,
					'place_description' => $place_description
				);
				modules::run('competition_mod/competition_ctr/update_comp', $update);
				unset($update);
			}
			if ($place == 1)
			{
				$update = array(
					'competition_id'    => $comp_id,
					'photo_id' 		    => $photo_id,
				);
				modules::run('competition_mod/competition_ctr/set_1_place', $update);
				unset($update);
			}
		}
		
		define('PHOTOS_PER_PAGE', 16);
		$cpage = intval($this->uri->segment(5));
		$cpage = (empty($cpage))? "1": $cpage;
		
		$photos = modules::run("competition_mod/competition_ctr/get_admin_competition_photos", $comp_id, PHOTOS_PER_PAGE, $cpage);		
		$photos_count = $photos['count'];
		unset($photos['count']);
		
		$data['competition_users'] = modules::run("competition_mod/competition_ctr/get_competition_users", $comp_id);		
		$data['photos'] = $photos;
		$data['cpage'] = $cpage;
		$data['name'] = $comp_name;
		$data['comp_id'] = $comp_id;
		
		$data['paginate_args'] = array(
				'total_rows' => $photos_count,
				'per_page'   => PHOTOS_PER_PAGE,
				'num_links'  => 10,
				'js_function' => 'filter_photos',
				'cur_page'   => $cpage
		);

		$this->load->view('admin/_comp_photos', $data);
	}
	
	function places()
	{
       	$data['place_block'] = modules::run('gallery_mod/gallery_ctr/get_places_list');
		$this->load->view('admin/_places', $data);
	}
	
	function send_user_mail_competition($photo_id, $moderation_state, $comp_name ){
	
		if (($moderation_state == 0 ) ||($moderation_state == -2))
			return 0;
			
		$data = modules::run('users_mod/users_ctr/get_user_email', $photo_id);
		if (!$data)	
			return 0;
			
		$photo_title = modules::run('gallery_mod/gallery_ctr/get_photo_title', $photo_id);

		$lng = $data->language; 
		$this->lang->load('email_photo_moderated',$lng);

		$subject = 'Служба поддержки Pinpix.kz';
		
		$val = array();
		$val['login'] = $data->login;
		$val['photo_title'] = $photo_title;
		
		if ($moderation_state >=1 )
			$message = $this->load->view("admin/_approved_message_html_tpl", $val, TRUE);
		if ($moderation_state == -1 )
			$message = $this->load->view("admin/_declined_message_html_tpl", $val, TRUE);
			
		$this->send_email($data->email, $subject, $message);		
	}
	
	function send_user_mail($photo_id, $moderation_state ){
		if ($moderation_state == 0 ) 
			return 0;
		$data = modules::run('users_mod/users_ctr/get_user_email', $photo_id);
		if (!$data)	
			return 0;

		//$lng = $this->db_session->userdata('user_lang');
		$lng = $data->language; 
			
		$this->lang->load('email_photo_moderated',$lng);
		
		$subject = lang('photo_moderated_subj');

		$message = lang('photo_moderated');
							
		$message = str_replace("%login%",$data->login, $message);
		$message = str_replace("%link_url%",base_url().'photo/view/'.$photo_id ,$message);
		$message = str_replace("%ms%", lang('ms'.$moderation_state) ,$message);
		
		$this->load->helper('email');			
		echo send_email($data->email, $subject, $message);
		
		
	}

	function send_user_mail_album($album_id, $moderation_state ){
		if ($moderation_state == 0 ) 
			return 0;
		$data = modules::run('users_mod/users_ctr/get_user_email_album', $album_id);
		if (!$data)	
			return 0;

		$lng = $data->language; 
		$this->lang->load('email_photo_moderated',$lng);
		
		$subject = lang('album_moderated_subj');

		$message = lang('album_moderated');
							
		$message = str_replace("%login%",$data->login, $message);
		$message = str_replace("%link_url%",base_url().'album/view/'.$album_id ,$message);
		$message = str_replace("%ms%", lang('ams'.$moderation_state) , $message);
		
		$this->load->helper('email');			
		send_email($data->email, $subject, $message);
		
	}

	function send_email($email, $subject, $message) {	
	
		$this->email->clear();
		$this->email->from('support.pinpix@pinpix.kz', 'pinpix.kz');
		$this->email->to($email);
		$this->email->subject($this->_mail_encode($subject, "utf-8"));
		$this->email->message($message, "utf-8");
		
		return $this->email->send();
	}

	function _mail_encode($text, $encoding) {
		$result = "=?".$encoding."?b?".base64_encode($text)."?=";
		return $result;
	}
	
	function delivery(){
		$delivery_send = $this->input->post('delivery_send');
		
		$data = array();
		$data['success'] = "";
		$data['users'] = "";
				
		if($delivery_send) {
			$all_users = modules::run('users_mod/users_ctr/get_users');		
			$all_users = $all_users['photos'];
			
			$delivery_subject = 'Конкурс "Летняя игра" на Pinpix.kz';
			$delivery_text = $this->load->view("admin/_delivery_html_tpl", null, TRUE);
			log_message('debug', 'email has been sent to: ');		
			foreach ($all_users as $index=>$user) {
				$this->send_email($user->email, $delivery_subject, $delivery_text);
				log_message('debug', 'email #'.$index.' has been sent to: '.$user->email);
			}
//			$this->send_email("d.mussina@sea2sky.kz", $delivery_subject, $delivery_text);
//			$this->send_email("monah40@gmail.com", $delivery_subject, $delivery_text);
			$data['success'] = "Рассылка успешно завершена.";
			$data['users'] = $all_users;
		}		
		$this->load->view('admin/_delivery', $data);
	}
}

function _loadPageData($page, $lng, $default_lng){
	$PAGE_PATH = STATIC_PATH.'pages/';
	if (is_file($PAGE_PATH.$lng.'/'.$page.'.php')) {
		return include($PAGE_PATH.$lng.'/'.$page.'.php');
	} elseif (is_file($PAGE_PATH.$default_lng.'/'.$page.'.php')) {
		return include($PAGE_PATH.$default_lng.'/'.$page.'.php');
	} else {
		set_error(lang("page_not_found"));
	}
}

/* end of file */