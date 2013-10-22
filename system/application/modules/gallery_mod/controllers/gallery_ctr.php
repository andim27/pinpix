<?php
/*define('MOD_NEW', 0);
define('MOD_APPROVED', 1);
define('MOD_FEATURED', 2);
define('MOD_DECLINED', -1);*/

class Gallery_ctr extends Controller {

	var $_lng = '';
	var $_last_insert_photo = array();

	function Gallery_ctr() {
		parent::Controller();

		$this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('gallery',$this->_lng);
		$this->load->library('Voting');
        $this->load->helper('bi_functions');
	}

	function index() {}

	function get_all_albums($moderation_state, $registered = 0, $erotic = -1, $user_id = '') {
		$this->load->model('album_mdl','album');
		$albums = $this->album->get_albums($moderation_state, $registered, $erotic, $user_id);
		return $albums;
	}
	
	function get_sitebar_albums($moderation_state, $registered = 0, $erotic = -1, $user_id = '') {
		$this->load->model('album_mdl','album');
		$albums = $this->album->get_sitebar_albums($moderation_state, $registered, $erotic, $user_id);
		return $albums;
	}

	function get_all_photos($moderation_state) {
		$this->load->model('photo_mdl','photo');
		$photos = $this->photo->get(null, $moderation_state);
		return $photos;
	}

	function get_photos_new($photo_id=null, $user_id=null, $moderation_state, $registered=0, $erotic=-1, $per_page=0, $page=1, $with_count=false, $order_by='', $period='', $order_in=""){
		$this->load->model('photo_mdl','photo');
		return $this->photo->get_photos($photo_id, $user_id, $moderation_state, $registered, $erotic, $per_page, $page, $with_count, $order_by, $period, $order_in);
	}
	
	function get_category_albums($cat_id, $moderation_state, $registered = 0, $erotic = -1, $sort_type=1 , $cpage=1, $sort_order='', $perpage = ''){
		$this->load->model('album_mdl','album');
		$albums = $this->album->get_category_albums($cat_id, $moderation_state, $registered, $erotic, $sort_type, $cpage, $sort_order, $perpage);

		return $albums;
	}

	function get_declined_albums($per_page=0, $page=1, $user_id) {
//		if(!$user_id) return false;

		$this->load->model('album_mdl', 'album');
		return $this->album->get_declined_albums($per_page, $page, $user_id);
	}
	
	function get_user_albums($user_id, $orderby=null, $moderation_state = '1', $registered = 0, $erotic = -1){
		$this->load->model('album_mdl','album');
		$albums = $this->album->get_user_albums($user_id, $orderby, $moderation_state, $registered, $erotic);
		return $albums;
	}
	
	function get_user_albums_info($user_id)
	{
		$this->load->model('album_mdl','album');
		$albums = $this->album->get_user_albums_info ($user_id);
			return $albums;
	}
	
	function get_album($album_id){
		$this->load->model('album_mdl','album');
		return $this->album->get_album($album_id);
	}
	
	function get_album_photos($album_id, $per_page=0, $moderation_state, $user_id = null, $registered=0, $erotic=-1, $sort_type=1, $page=1,$sort_order = '') {
		$this->load->model('album_mdl','album');
		return $this->album-> get_album_photos($album_id, $per_page, $moderation_state, $user_id, $registered, $erotic, $sort_type, $page, $sort_order) ;
//		get_album_photos($album_id, $per_page, $page, $moderation_state, $user_id);
	}
	
	function get_category_photos($cat_id, $per_page=0, $moderation_state, $registered=0, $erotic=-1, $sort_type=1, $page=1,$sort_order = '') {
		$this->load->model('album_mdl','album');
		return $this->album->get_category_photos($cat_id, $per_page, $moderation_state, $registered, $erotic, $sort_type, $page, $sort_order) ;
//		get_album_photos($album_id, $per_page, $page, $moderation_state, $user_id);
	}

	function get_declined_album_photos($album_id, $per_page=0, $page=1, $user_id = null){
		$this->load->model('album_mdl','album');
		return $this->album->get_declined_album_photos($album_id, $per_page, $page, $user_id);
	}

	function get_declined_albums_count() {
		$this->load->model('album_mdl','album');
		return $this->album->get_declined_albums_count();
	}
	
	function get_photo_prop_new($photo_id, $size='lg', $moderation_state, $registered=0, $erotic = -1){
		$this->load->model('photo_mdl','photo');
		return $this->photo->get_prop_photo($photo_id, $registered, $erotic, $moderation_state);
	}

    function get_photo($photo_id, $size='lg', $moderation_state, $registered=0, $erotic = -1){
		$this->load->model('photo_mdl','photo');
		return $this->photo->get_single_photo($photo_id, $registered, $erotic, $moderation_state);
	}
	
	function get_main_album_photo($photo_id, $moderation_state, $registered=0, $erotic = -1){
		$this->load->model('photo_mdl','photo');
		return $this->photo->get_main_album_photo($photo_id, $moderation_state, $registered, $erotic);
	}
	
	function get_user($photo_id)   {
		if  (empty ($photo_id)){
			set_error(lang('error_empty_request'));
//			$this->_setError('error_empty_request');
			return false;
		}
		$this->load->model('photo_mdl','photo');
		$user = $this->photo->get_user($photo_id);
		if ($user) {
			return ($user);
		} else {
			set_error(lang('error_empty_user'));
		}
	}
	
	function get_album_user($album_id)   {
		if  (empty ($album_id)){
			set_error(lang('error_empty_request'));
			return false;
		}
		$this->load->model('album_mdl','album');
		$user = $this->photo->get_album_user($album_id);
		if ($user) {
			return ($user[0]->user_id);
		} else {
			set_error(lang('error_empty_user'));
		}
	}

	function get_photos_count_per_user ($user_id, $moderation_state = 1, $registered = 0, $erotic = -1) {
		if  (empty ($user_id)){
			set_error(lang('page_not_found'));
			return false;
		}
		$this->load->model('photo_mdl','photo');
		return $this->photo->get_photos_count_per_user($user_id,  $moderation_state, $registered, $erotic);
	}

	function get_photos_count_for_main ($registered = 0, $erotic = -1, $moderatoin_state = 1, $period = 7) {
		$this->load->model('photo_mdl','photo');
		return $this->cache->process('photos_count', array($this->photo, 'get_photos_count'),array($registered, $erotic, $moderatoin_state, $period), 3*60 );
		//return $this->photo->get_photos_count($registered, $erotic, $moderatoin_state, $period);
	}
	
 
	function get_photo_prop_html($photo,$user_id,$my,$registered, $erotic)
	{
		$data['view_allowed'] = array(
			2 => lang('view_public'),
			1 => lang('view_registered'),
			0 => lang('view_private')
		);
		$data['photo'] = $photo;
		$data['photo_id']     = $photo->photo_id;
		$data['categories']   = modules::run('catalog_mod/catalog_ctr/get_categories_list');
		$data['competitions'] = modules::run('competition_mod/competition_ctr/get_competitions_list');
		$competition_photo = modules::run('competition_mod/competition_ctr/get_competitions_by_photos', $photo->photo_id);
		if(empty($competition_photo)) {
			$competition_photo = (Object)array('competition_id' => null, 'photo_id' => null);
		} elseif (is_array($competition_photo)) {
			$competition_photo = $competition_photo[0];
		}
		$data['competition_photo'] = $competition_photo;
		$data['all_albums']   = $this->get_user_albums( $user_id, '', (($my === TRUE)?'all':1), $registered, $erotic);
		return $this->load->view('photo_prop', $data);
	}

	function get_photo_one_img($photo_id, $moderation_state, $password = null, $registered = 0, $erotic = -1, $size='lg')
	{
		$out_str="";
		$data="";
		$photo = $this->get_photo_prop_new($photo_id, $size, $moderation_state, $registered, $erotic );
		
		if (empty($photo)) {
			set_error(lang('page_not_found'));
			return -1;
		}
		if ($this->check_access_photo($photo,$moderation_state) ==-1) {
			return -1;
		}
	
		if ($this->check_registration_photo($photo,$erotic,$registered) == -1) {
			return -1;
		}	

		if(!empty($photo->view_password)) 
		{
			if(empty($password)) 
				$data['password'] = false;
			else 
			{
				$password = $password;
				if ($photo->view_password == $password) 
					$data['password'] = true;
				else 
				{			
					$data['password'] = false;
					$data['pwd_wrong_mess'] = true;
				}
			}
		}

		modules::load_file('constants',MODBASE.modules::path().'/config/');
		$photo->src_md = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
		$photo->src_lg = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
		$photo->code_link =lang('code_link_txt');
		$data['user_id']= $this->db_session->userdata('user_id');
		$data['photo'] = $photo;
		$data['user']   = $this->get_user($photo->photo_id);
		$data['next'] = $this->get_next_photo_id ($photo_id, $moderation_state);
		$data['prev'] = $this->get_prev_photo_id ($photo_id, $moderation_state);
		$this->rating->addAction('see',$photo_id);

		$t = modules::run('comments_mod/comments_ctr/get_object_comments', 'photo', $photo->photo_id, 0, 1, null, null, 1, true);
		$t = empty($t) ? 0 : count($t);
		if ($t) {
			$data['coments_count'] = $t;
		}else {
			$data['coments_count'] = 0;
		}
		if ($photo->user_id == $data['user_id']){
			$data['my']=TRUE;
		} else {
			$data['my']=FALSE;
		}
		//---b:AndMak---
		$p_min=false;
		$config = $this->load->config();
		$h_min	=$this->config->item('h_min');
		$w_min	=$this->config->item('w_min');
		$data['photo_prop_html']=$this->get_photo_prop_html($photo,$data['user_id'],$data['my'],$registered,$erotic);
		$data['vote_html']     =$this->voting->get_view_html2($photo_id);
		$data['vote_avg_html'] =$this->voting->get_avg_html($photo_id);
		$data['vote_cnt']      =$this->voting->getVotes('foto',$photo_id);
		$data['user_vote_cnt'] =$this->voting->getUserVotes($photo_id,$data['user_id']);
		$data['vote_action']   =$this->voting->setVoteAction($photo_id);
		$data['see_cnt_photo'] =$this->rating->getSeeCnt('foto',$photo_id);
		$data['rating_ball_photo']=$this->rating->getBalls('foto',$photo_id);
		$data['fl_cont_main']  =$this->fl->get_control_js($this->fl->get_random_file(),"100%","100%","fl_container");
		$data['w_fon_w'] = 625;
		$data['w_fon_h'] = 592;
		if (((intval(($photo->md_width)) < $w_min) && ($photo->md_height < $h_min)) ) {
			$p_min=true;
			$f_w = $photo->md_width."px";
			$f_h = $photo->md_height."px";
		}
		if (intval(($photo->md_width)) >  intval(($photo->md_height)) ) { //gorisontaly orientiered
			$data['land']=TRUE;
			if ($p_min == false) //image is not less then 625/592
				$data['pad_top']=(592-$photo->md_height)*0.32;
			else 
				$data['pad_top']=($photo->md_width - $photo->md_height)*0.32;     
		} 
		else 
		{
			$data['land']=FALSE;
			if ($p_min == false) //image is not less then 625/592
				$data['pad_left']=(625-$photo->md_width)*0.25;
			else 
				$data['pad_left']=($photo->md_height - $photo->md_width)*0.25;                      
		}
		$f_h=$photo->md_height > $data['w_fon_h']?$data['w_fon_h']:$photo->md_height;
		$f_w=$photo->md_width  > $data['w_fon_w']?$data['w_fon_w']:$photo->md_width;
		$data['fl_cont_html'] = $this->fl->get_block_html($photo->src_md,$f_w,$f_h,"fl_container_one");
		$data['fl_cont_js'] = $this->fl->get_control_js($photo->src_md,$f_w,$f_h); //--$h_str		
		$data['p_min']=$p_min;
		//---e:AndMak-
		$data['keywords'] = $photo->title;
		$this->load->view('photo_one_img', $data);
		return ;
	}
	
	function get_all_my_photos($user_id, $per_page=0, $page=1, $sort=1, $order = "desc", $moderation_state = "nodel", $registered=1, $erotic=1){
		$this->load->model('album_mdl','album');
		return $this->album->get_all_photos_per_user($user_id, $per_page, $page, $moderation_state, $sort, $order, $registered, $erotic);
	}

	function get_albums_main_photo_list($albums_ids){
		$this->load->model('album_mdl','album');
		return $this->album->get_albums_main_photo_list($albums_ids);
	}

	function get_deleted_count_per_user($user_id) {
		$result = array();
		
		$this->load->model('album_mdl','album');
		$result['albums'] = $this->album->get_deleted_albums_count_per_user($user_id);
		$this->load->model('photo_mdl','photo');
		$result['photos'] = $this->photo->get_deleted_photos_count_per_user($user_id);
		return $result;
	}

	function get_next_photo_id ($photo_id, $moderation_state=1) {
		$this->load->model('photo_mdl','photo');
		return $this->photo->get_next_photo_id ($photo_id, $moderation_state);
	}

	function get_prev_photo_id ($photo_id, $moderation_state=1) {
		$this->load->model('photo_mdl','photo');
		return $this->photo->get_prev_photo_id ($photo_id, $moderation_state);
	}

	function get_admin_photos_list($sort_type=1, $sort_order='desc', $cpage=1, $per_page, $search_criteria = array()) {
		$this->load->model('photo_mdl','photo');
		modules::load_file('constants',MODBASE.modules::path().'/config/');
		return $this->photo->get_admin_photos_list($sort_type, $sort_order, $cpage, $per_page, $search_criteria);
	}

	function get_admin_albumss_list($sort_type=1, $sort_order='asc', $cpage=1, $per_page, $search_criteria = array()) {
		$this->load->model('album_mdl','album');
		return $this->album->get_admin_albumss_list($sort_type, $sort_order, $cpage, $per_page, $search_criteria);
	}

	function get_photo_title($photo_id)
	{
		$this->load->model('photo_mdl');
		return $this->photo_mdl->get_photo_title($photo_id);	
	}
	
    function get_last_insert_photo(){
		$last_insert_photo = $this->_last_insert_photo;
		$this->_last_insert_photo = array();
		return $last_insert_photo;
	}
	
    function view_category_albums($cat_id, $moderation_state, $registered = 0, $erotic = -1, $sort_type=1 , $cpage=1, $sort_order='', $perpage){
		$category = null;
    	$category = $this->uri->segment(3);

    	$data['albums'] = $this->get_category_albums($cat_id, $moderation_state, $registered, $erotic, $sort_type, $cpage, $sort_order, $perpage);
		if(!empty($data['albums'])) {
			$col_photos = $data['albums'][0]['count_photos'];
			//getting number of comments, rating and count of views
			$i=0;
			foreach ($data['albums'][0]['photos'] as $photo) :
				$t = modules::run('comments_mod/comments_ctr/get_object_comments_count_by_id', 'photo', $photo->photo_id);
				if ($t) {
					$data['coms'][$i] = $t;
				} else {
					$data['coms'][$i] = 0;
				}
				$data['balls'][$i] = $this->rating->getBalls('foto', $photo->photo_id);
				$data['views'][$i] = $this->rating->getSeeCnt('foto', $photo->photo_id);
				$i++;
			endforeach;
		} else {
			$col_photos = 0;
		}
		$data['category'] = $category;
		$data['paginate_args'] = array(
			'total_rows' => $col_photos,
			'per_page'   => $perpage,
			'cur_page'   => $cpage
		);
		$data['sort_order']=$sort_order;
		$data['sort_type']=$sort_type;

		$this->load->view('albums',$data);
	}

	function view_declined_albums() {
		$user_id = $this->db_session->userdata('user_id');
		$data['albums'] = $this->get_declined_albums($user_id);
		$this->load->view('declined_albums', $data);
	}

	function view_user_albums($user_id, $orderby=null){
		$data['albums'] = $this->get_user_albums($user_id, $orderby);
		$this->load->view('user_albums',$data);
	}

	function view_album($album_id){
		$data['album'] = $this->get_album($album_id);
		$this->load->view('album', $data);
	}

	function view_album_photos($album_id, $per_page=0, $page=1, $moderation_state, $user = null, $password = null, $registered = 0, $erotic = -1, $sort_type = 1, $sort_order = '') {
		$user_id = null;
		$path = MODBASE.modules::path();
		modules::load_file('constants',$path.'/config/');
		$this->load->model('album_mdl','album');

		if($user) {
			if(is_numeric($user)) {
				$user_id = $user;
			} elseif (is_object($user)) {
				$user_id = $user->user_id;
			}
		}
		// get album
		$album = $this->get_album($album_id);
		
        if (empty($album)) {
          set_error(lang('page_not_found'));
          return false;
		}
		
		if ($album->user_id == $this->db_session->userdata('user_id'))
			$data['my'] = true;
		else
			$data['my'] = false;
		if (($album->moderation_state <  $moderation_state)&& ($this->db_session->userdata('user_id')!=$album->user_id))
		{
          set_error(lang('abum_not_approved'));
          return false;
		}
		
		if ($data['my']) {
			$sitebar_albums = $this->get_sitebar_albums('all', $registered, $erotic, $album->user_id);
		} else {
			$sitebar_albums = $this->get_sitebar_albums(MODERATION_STATE, $registered, $erotic, $album->user_id);
		}

		if(!empty($album)) {
			if  (!empty($album->view_password)) {
				if(empty($password)) {
					$data['password'] = false;
				} else {
				    //	$password = md5($password);
					if($album->view_password == $password) {
						$data['password'] = true;
					} else {
						$data['password'] = false;
						$data['pwd_wrong_mess'] = true;
					}
				}
			}
		//	echo "album->user_id " . $album->user_id . " user_id = " .$this->db_session->userdata('user_id'); exit;
			if (($album->user_id)==$this->db_session->userdata('user_id'))
				$data['password'] = true;
		}
		$data['opened_album_id'] = $album_id;
		$data['photos'] =  $this->get_album_photos($album_id, $per_page, $moderation_state, $user_id, $registered, $erotic, $sort_type, $page, $sort_order);
		$data['photos'] =  $this->small_img_prepare ($data['photos']);
	
		$data['album'] = $album;
		$data['albums'] = $sitebar_albums;
		$data['paginate_args'] = array(
            'total_rows' =>  $this->album->get_album_photos_count($album_id, $registered, $erotic, $moderation_state),
			'per_page'   => $per_page
		);
		$data['user'] = $user;

		$data['sort_order']=$sort_order;
		$data['sort_type']=$sort_type;
		$data['keywords'] = $album->title;
		$this->load->view('album', $data);
	}

	function view_declined_album_photos($album_id, $per_page = 0, $page = 1) {
		$user_id = null;
		$path = MODBASE.modules::path();
		modules::load_file('constants',$path.'/config/');
		$this->load->model('album_mdl','album');

		$user_id = $this->db_session->userdata('user_id');
		// get album
		$album = $this->get_album($album_id);

		$data['photos'] = $this->get_declined_album_photos($album_id, $per_page, $page, $user_id);
		$data['album'] = $album;
		$data['paginate_args'] = array(
			'total_rows' => $this->album->get_album_photos_count($album_id),
			'per_page'   => $per_page
		);
		$data['user'] = $user_id;

		$this->load->view('declined_photos', $data);
	}

	function view_photo($photo_id, $moderation_state, $password = null, $registered = 0, $erotic = -1, $size='lg') {
		$photo = $this->get_photo($photo_id, $size, $moderation_state, $registered, $erotic );
		if (empty($photo)) {
          set_error(lang('page_not_found'));
          return -1;
		}
        $this->check_access_photo($photo,$moderation_state);
        $this->check_registration_photo($photo, $erotic, $registered);


		if(!empty($photo)) {
			if(!empty($photo->view_password)) 
			{
				if(empty($password)) 
					$data['password'] = false;
				else 
				{
					$password = $password;
					if ($photo->view_password == $password)
						$data['password'] = true;
					else 			
						$data['password'] = false;
				}
			}

			modules::load_file('constants',MODBASE.modules::path().'/config/');
			$photo->src_md = date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
            $photo->src_lg = date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
			//$photo->src_lg = "/".PHOTO_UPLOAD_DIR.'/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
//			$photo->code_link = base_url("").PHOTO_UPLOAD_DIR.'/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
			$photo->code_link =lang('code_link_txt');
			$data['photo'] = $photo;
		    $data['albums'] = $this->get_user_albums(end($this->get_user($photo_id))->user_id, null, $moderation_state, $registered, $erotic);
			if (isset ($photo->albums[0]))
		    	$data['alb']    = $photo->albums[0];
			$data['user']   = $this->get_user($photo->photo_id);
			if ($this->uri->segment(4) === 'competition') {
				$competition_data = $this->db_session->userdata('competition');
				$positon = array_shift(array_keys($competition_data['photos_ids'], $photo_id));
				if ($positon == 0) {
					$data['next'] = $competition_data['photos_ids'][($positon+1)].'/competition';
					$data['prev'] = $competition_data['photos_ids'][(count($competition_data['photos_ids'])-1)].'/competition';
				} elseif ($positon == (count($competition_data['photos_ids'])-1)) {
					$data['next'] = $competition_data['photos_ids'][0].'/competition';
					$data['prev'] = $competition_data['photos_ids'][($positon-1)].'/competition';
				} else {
					$data['next'] = $competition_data['photos_ids'][($positon+1)].'/competition';
					$data['prev'] = $competition_data['photos_ids'][($positon-1)].'/competition';
				}
				$data['pathway_main'] = $competition_data['pathway_main'];
				$data['pathway_name'] = $competition_data['pathway_name'];
			} else {
				$data['next'] = $this->get_next_photo_id ($photo_id, $moderation_state);
				$data['prev'] = $this->get_prev_photo_id ($photo_id, $moderation_state);
			}

			$t = modules::run('comments_mod/comments_ctr/get_object_comments_count_by_id', 'photo', $photo->photo_id);
			if ($t) {
				$data['coments_count'] = $t;
			}
            //---b:AndMak---
			$data['vote_html']     =$this->voting->get_view_html2($photo_id);
			$data['vote_avg_html'] =$this->voting->get_avg_html($photo_id);
			$data['see_cnt_photo'] =$this->rating->getSeeCnt('foto',$photo->photo_id);
			$data['fl_cont_main']  =$this->fl->get_control_js($this->fl->get_random_file(),"100%","100%","fl_container");
            if (intval(($photo->md_width)) >  intval(($photo->md_height)) ) {
                $data['land']=TRUE;
                $h_str=strval($photo->md_height)."px";
                $data['pad_top']=(625-$photo->md_height)*0.32;
                $data['fl_cont_html']  =$this->fl->get_block_html($photo->src_md,"625px",$h_str,"fl_container_one");
    			$data['fl_cont_js']    =$this->fl->get_control_js($photo->src_md,"625px",$h_str);
            } else {
                $data['land']=FALSE;
                $data['fl_cont_html']  =$this->fl->get_block_html($photo->src_md,"450px","592px","fl_container_one");
    			$data['fl_cont_js']    =$this->fl->get_control_js($photo->src_md,"450px","592px");
            }
			$this->rating->addAction('see',$photo_id);
            //---e:AndMak-

		} else { //if get_photo returned empty result
		   //	$data['error_forbidden'] = (lang("error_forbidden"));
             set_error(lang('page_not_found'));
             return -1;
               }

		$data['moderation_state'] = $moderation_state;
		$data['registered'] = $registered;
		$data['erotic'] = $erotic;

		$this->load->view('photo', $data);
	}

	function view_photo_form($photo_id=null, $categories=null, $addition_block='', $album_id=null) {
		//log_message('debug', '  ##########  view_photo_form: '.var_export($photo_id,TRUE));

		$data['photo'] = null;
		$data['photo_title'] = '';
		$data['photo_desc'] = '';

		if ( ! empty($photo_id)) {
			$this->load->model('photo_mdl','photo');
			$photo = $this->photo->get($photo_id, 'all');

			$config = $this->load->config();
			$photo->src_md = trim($this->config->item('rel_photo_upload_dir')).'/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-sm'.$photo->extension;

			$data['photo'] = $photo;
			$data['photo_title'] = $photo->title;
			$data['photo_desc'] = $photo->description;
			$data['album_id'] = 0;
			if (is_array($photo->albums) && ! empty($photo->albums)) {
				$data['album_id'] = $photo->albums[0]->album_id;
			}
		} else {
			$data['album_id'] = $album_id;
		}

		if (isset($_POST['photo_name'])) {
			$data['photo_title'] = $_POST['photo_name'];
		}

		if (isset($_POST['photo_album'])) {
			$data['album_id'] = $_POST['photo_album'];
		}

		$data['categories'] = $categories;

		$this->load->model('album_mdl','album');
		$data['albums'] = $this->album->get_albums('all');

		$data['configurable_addition_block'] = $addition_block;

		$this->load->view('photo_form', $data);
	}
	
	function view_my_albums($user_id=null, $moderation_state="all", &$p_data=array(), $registered=0, $erotic=-1, $private_my = 0){
		if (empty($user_id))
			return FALSE;
		$this->load->model('album_mdl','album');
		$tmp_albums = $this->album->get_my_albyms($user_id, $moderation_state, $registered, $erotic, $private_my );
		$albums = array();
		$albums_ids = array();
		foreach ($tmp_albums as $idx => $album) {
			if (empty($album->p_count)) {
				$albums[$idx]['photos'] = array();
			} else {
				$albums[$idx]['photos'] = range(1, $album->p_count);
			}
			unset($album->p_count);
			$albums[$idx]['album'] = $album;
			$albums_ids[] = $album->album_id;
		}
		unset($tmp_albums);
		$coments_count = modules::run('comments_mod/comments_ctr/get_objects_comments_count', 'album', $albums_ids);
		if (empty($coments_count)) $coments_count = array();
		foreach ($albums as $idx => $album) {
			$albums[$idx]['album']->comment_cnt = 0;
			foreach ($coments_count as $comment) {
				if ($comment->id == $album['album']->album_id) {
					$albums[$idx]['album']->comment_cnt = $comment->c_count;
					break;
				}
			}
		}
		
	  	$data['albums'] = $albums;
	  	$data['registered'] = $registered;
	  	$data['erotic'] = $erotic;
		$data = array_merge($p_data, $data);
		$this->load->view('albums_sitebar',$data);
	}
		
	function view_all_user_albums($user_id, $moderation_state, $registered = 0, $erotic = -1, $per_page, $page, $sort_type, $sort_order) {
		$this->load->model('album_mdl','album');
		$data['albums'] = $this->album->get_all_user_albums ($user_id, $moderation_state, $registered, $erotic, $per_page, $page, $sort_type, $sort_order);
		$data['paginate_args'] = array(
			'total_rows' => $data['albums']['albcnt'],
			'per_page'   => $per_page,
			'cur_page'   => $page
		);
		$data['user_id'] = $user_id;
		$data['sort_order'] = $sort_order;
		$data['sort_type'] = $sort_type;
		//for editing
		$data['categories'] = modules::run('catalog_mod/catalog_ctr/get_categories_list');
    	$data['competitions'] = modules::run('competition_mod/competition_ctr/get_competitions_list');
		$data['view_allowed'] = array(
    				2 => lang('view_public'),
    				1 => lang('view_registered'),
    				0 => lang('view_private')
    			);		
		$this->load->view('all_user_albums',$data);
	}
	
	function view_category_photos($cat_id, $moderation_state, $registered, $erotic, $sort_type, $page, $sort_order, $per_page) {
	
		$data['photos'] =  $this->get_category_photos($cat_id, $per_page, $moderation_state, $registered, $erotic, $sort_type, $page, $sort_order);
		$data['photos'] =  $this->small_img_prepare ($data['photos']);
		$data['paginate_args'] = array(
            'total_rows' =>  $this->album->get_category_photos_count($cat_id, $registered, $erotic, $moderation_state),
			'per_page'   => $per_page
		);
	
		$data['sort_order']=$sort_order;
		$data['sort_type']=$sort_type;
		$data['cat_id']=$cat_id;
		$data['category_name']= modules::run('catalog_mod/catalog_ctr/get_category_name', $cat_id);
		$data['keywords'] = $data['category_name'];
		$this->load->view('category', $data);
	}
	
	function add_photo_to_album() {
		if ($_POST) {
			$album_id = $_POST['photo_album'];
			if( ! $album_id) {
				set_error('error_undefined_album');
				return FALSE;
			}

			$this->load->model('album_mdl','album');
			$this->load->model('photo_mdl','photo');

			$category = $this->album->get_album_category($album_id);
			$category_id = null;
			if (is_array($category) && ! empty($category)) {
				$category_id = $category[0]->id;
			}

			$photo_id = $this->add_photo($_POST['photo_name']);
			if ( ! $photo_id) return FALSE;
			return  $this->photo->add_to_album($photo_id, $category_id, $album_id);
		}
		return FALSE;
	}

	function place_photo($photo_id, $category_id ,$album_id){
		$this->load->model('photo_mdl','photo');
		return  $this->photo->add_to_album($photo_id, $category_id, $album_id);
	}

	function add_photo($data=array()) {
		if (empty($data)) return FALSE;
		
		$user_id = $this->db_session->userdata('user_id');		

		if ($user_id) {
			$title = $this->input->xss_clean(trim($data['p_title']));			
			$this->load->model('photo_mdl','photo');

			$config = $this->load->config();

			$path_upload = trim($this->config->item('photo_upload_dir')).'/'.date("m").'/';
					
			if(!file_exists($path_upload)) {
				if ( ! mkdir($path_upload, 0777)) {
					set_error('error_create_upload_dir');
				}
			}
		
			$config['upload_path'] = $path_upload;
			$config['allowed_types'] = $this->config->item('img_types');
			$config['max_size'] = $this->config->item('file_max_size');
			$config['remove_spaces'] = TRUE;
			if (isset($this->load->_ci_classes['upload']) && ($this->load->_ci_classes['upload'] == 'upload')) {
				$this->upload->initialize($config);
			} else {
				$this->load->library('upload', $config);
			}
			
			if ( $this->upload->do_postupload($data)) {
				$upload_data = $this->upload->data();
				log_message('debug', 'upload_data: '.var_export($upload_data, true));
				
				/**
				 * verify extention call exec for convert to jpeg if file has tiff, raw extention
				 */
				$to_convert = array(".tif", ".tiff", ".jp2", ".pcx", ".tif", ".psd", "cpt"); 
				
				if(in_array(strtolower($upload_data['file_ext']), $to_convert)){
					log_message('debug', 'this file is TIFF.');
					$file_path_in = $upload_data['full_path'];
					$file_path_out = $upload_data['file_path'].$upload_data['raw_name'].".jpg";
					
					$result = $this->image_lib->convert_to_jpeg($file_path_in, $file_path_out);
					if($result) {
						unset($file_path_in);
						
						$file_out_name = $upload_data['raw_name'].".jpg";
						
						$upload_data['file_name'] = $file_out_name;
						$upload_data['file_type'] = "image/jpeg";
						$upload_data['full_path'] = $upload_data['file_path'].$file_out_name;
						$upload_data['orig_name'] = $file_path_out;
						$upload_data['file_ext'] = ".jpg";											
					}
				}				
				$photo_id = $this->photo->add_photo($title, $user_id);
				if ($photo_id) {
					// creating lg, md and sm images
					$img_size = $this->config->item('img_size');
					// rename the original file
					$original_path = $upload_data['file_path']."/".$upload_data['raw_name'].$upload_data['file_ext'];
							
					$new_path_lg = $upload_data['file_path']."/".$photo_id.'-lg'.$upload_data['file_ext'];
					$res = rename($original_path, $new_path_lg);
					if ( ! $res)
						log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded file hasn\'t been renamed!');

					// head
					if (!isset($this->load->_ci_classes['image_lib']) || ($this->load->_ci_classes['image_lib'] != 'image_lib')) {						
						$this->load->library('image_lib');
					}
					
					$config['image_library'] = 'gd2';
					
//					$this->image_lib->initialize($config);										
					list($src['width'], $src['height']) = getimagesize($new_path_lg);
					$y_axis = round(($src['height']) /2) - $img_size['head']['height']/2;
					$x_axis = round(($src['width']) /2) - $img_size['head']['width']/2;
					
					$config['source_image'] = $new_path_lg;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = FALSE;
					$config['width'] = $img_size['head']['width'];
					$config['height'] = $img_size['head']['height'];	
					$flag_res = 0;
					if ($src['height'] < $img_size['head']['height'])
					{						
						$config['height'] = $img_size['head']['height'];	
						$flag_res = 1;					
					}				
					if ($src['width'] < $img_size['head']['width'])
					{						
						$config['width'] = $img_size['head']['width'];	
						$flag_res = 1;					
					}
										
					if ($flag_res == 1)
					{
						$this->image_lib->initialize($config);
						$this->image_lib->resize();
						$original_path = $upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];
						$new_path_h = $upload_data['file_path'].$photo_id.'-head'.$upload_data['file_ext'];
						
						$res = rename($original_path, $new_path_h);
						if ( ! $res)
							log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to head size!');												
						
						list($src['width'], $src['height']) = getimagesize($new_path_h);
						$y_axis = round(($src['height']) /2) - $img_size['head']['height']/2;
						$x_axis = round(($src['width']) /2) - $img_size['head']['width']/2;					
						
						$config['source_image'] = $new_path_h;
						
						$original_path = $upload_data['file_path'].$photo_id.'-head_thumb'.$upload_data['file_ext'];					
						
					}
					else //if image is bigger then head-size-suppozed
					{															
						$original_path = $upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];														
					}
					
					//log_message('error',__FILE__.', line '.__LINE__.', before crop  '  );
						
					$config['y_axis'] = $y_axis;
					$config['x_axis'] = $x_axis;
					$config['height'] = $img_size['head']['height'];
					$config['width']  = $img_size['head']['width'];
						
					$this->image_lib->initialize($config);							
					if ( ! $this->image_lib->crop())
					{
//					    log_message('error',__FILE__.', line '.__LINE__.', head-size2222222222222 error: ' . $this->image_lib->display_errors());
					}	
										
					$new_path_h = $upload_data['file_path'].$photo_id.'-head'.$upload_data['file_ext'];
					$res = rename($original_path, $new_path_h);
					if ( ! $res)
						log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to head size!');
					
					// middle
					$config['image_library'] = 'gd2';
					$config['source_image'] = $new_path_lg;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
										
					$config['width'] = $img_size['middle']['width'];
					$config['height'] = $img_size['middle']['height'];
					
					list($src['width'], $src['height']) = getimagesize($new_path_lg);
					//in case of small image we dont resize 
					if (($src['height']< $config['height'])&& ($src['width']< $config['width']))
					{
						$config['height'] = $src['height'];
						$config['width'] = $src['width'];
					}
					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();

					$original_path = $upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];
					$new_path_md = $upload_data['file_path'].$photo_id.'-md'.$upload_data['file_ext'];
					$res = rename($original_path, $new_path_md);
					if ( ! $res)
						log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to middle size!');
				
					// thumbnail
					
					$this->image_lib->clear();
					$config['image_library'] 	= 'gd2';		
					$config['source_image'] = $new_path_lg;
					
					list($src['width'], $src['height']) = getimagesize($new_path_lg);
														
					if ($src['width'] >= $src['height']*$img_size['thumbnail']['width'] / $img_size['thumbnail']['height'] )
						$config['width'] = $img_size['thumbnail']['width'];
					else
						$config['height'] = $img_size['thumbnail']['height'];
					
					$config['maintain_ratio'] = TRUE;
								
					//in case of small image we dont resize 
					if (($src['height']< $img_size['thumbnail']['height'])&& ($src['width']< $img_size['thumbnail']['width']))
					{
						$config['height'] = $src['height'];
						$config['width'] = $src['width'];
					}
					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();
					
					$new_path_sm = $upload_data['file_path'].$photo_id.'-sm'.$upload_data['file_ext'];
					$res = rename($original_path, $new_path_sm);
					if ( ! $res) {
						log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to small size!');
					} else {
						//Aply Unsharp Mask
						$real_size = getimagesize($new_path_sm);
						log_message('debug', '$real_size '.var_export($real_size, true));						
						$usm = imagecreatetruecolor($real_size[0], $real_size[1]);
						$sm_img = imagecreatefromstring(file_get_contents($new_path_sm));
						imagecopy($usm, $sm_img, 0, 0, 0, 0, $real_size[0], $real_size[1]);
						imagedestroy($sm_img);
						$this->load->helper('unsharp');
						$usm = $this->UnsharpMask($usm, 125, 0.75, 0.25);
						switch($real_size[2]) {
							case '1'; // It GIF
								imagegif($usm, $new_path_sm);
								break;
							case '2'; // It JPG
								imagejpeg($usm, $new_path_sm);
								break;
							case '6'; // It BMP
								imagewbmp($usm, $new_path_sm);
								break;
							default: // Will be PNG
								imagepng($usm, $new_path_sm);
								break;
						}
						imagedestroy($usm);
					}

					$md = getimagesize($new_path_md);
					$sm = getimagesize($new_path_sm);
					$exif = @exif_read_data($data['p_tmp_name']);
					$photo_extras = array(
						'photo_id'           => $photo_id,
						'lg_width'           => $upload_data['image_width'],
						'lg_height'          => $upload_data['image_height'],
						'size'	             =>	$upload_data['file_size'],
						'extension'	         =>	$upload_data['file_ext'],
						'md_width'           => $md[0],
						'md_height'          => $md[1],
						'sm_width'           => $sm[0],
						'sm_height'          => $sm[1],
						'exif_camera'        => (isset($exif['Model'])?$exif['Model']:NULL),
						'exif_shooting_date' => (isset($exif['DateTime'])?$exif['DateTime']:NULL),
						'exif_focal_length'  => (isset($exif['COMPUTED']['ApertureFNumber'])?$exif['COMPUTED']['ApertureFNumber']:NULL),
						'exif_exposure_time' => (isset($exif['ExposureTime'])?$exif['ExposureTime']:NULL),
						'exif_aperture'      => (isset($exif['ApertureValue'])?$exif['ApertureValue']:NULL),
						'exif_focus_dist'    => (isset($exif['FocalLength'])?$exif['FocalLength']:NULL),
						'description'        => clean(trim($data['p_descr'])),
						'view_allowed'       => $data['P_alloved'],
						'erotic_p'           => (($data['p_erotic'] == 'yes')? '1': 0),
						'date_modified'      => date("Y-m-d H:i:s")
					);

					if  ($data['P_alloved'] == 0) {
						$photo_extras['view_password'] = $this->input->xss_clean($data['p_pwd']);
					}
					$res = $this->photo->update_photo($photo_id, $photo_extras);
					if ( ! $res)
						log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo data hasn\'t been updated in DB!');
					$this->_last_insert_photo = $photo_extras;
				
					if ( isset ($data['p_comp']) && ($data['p_comp']!=0) )
			        	modules::run('competition_mod/competition_ctr/add_work_to_competition2', $photo_id, $data['p_comp']);
			
					return $photo_id;
					
				} else {
					unlink($upload_data['full_path']);
					log_message("error", "error_data_saving");
					set_error('error_data_saving');
				}
			} else {
				set_error($this->upload->display_errors());
				log_message("error", $this->upload->display_errors());
			}
		} else {
			return FALSE;
		}
	}

	function do_upload_photo($data=array()){
		$config = $this->load->config();

		$path_upload = trim($this->config->item('photo_upload_dir')).'/'.date("m").'/';
				
		if(!file_exists($path_upload)) {
			if ( ! mkdir($path_upload, 0777)) {
				set_error('error_create_upload_dir');
			}
		}
	
		$config['upload_path'] = $path_upload;
		$config['allowed_types'] = $this->config->item('img_types');
		$config['max_size'] = $this->config->item('file_max_size');
		$config['remove_spaces'] = TRUE;
		if (isset($this->load->_ci_classes['upload']) && ($this->load->_ci_classes['upload'] == 'upload')) {
			$this->upload->initialize($config);
		} else {
			$this->load->library('upload', $config);
		}
		
		if ( $this->upload->do_postupload($data))
			$upload_data = $this->upload->data();
			log_message('debug', 'upload_data: '.var_export($upload_data, true));
		return $upload_data;
	}
	
	function upload_photo($fieldname){
		if(!$fieldname) return false;
		log_message('debug', 'UPLOAD. upload_attach');
		
		$config = $this->load->config('upload');

		$config['upload_path'] .= 'photos/'.date("m").'/';
				
		if(!file_exists($config['upload_path'])) {
			if ( ! mkdir($config['upload_path'], 0755)) {
				set_error('error_create_upload_dir');
			}
		}
		
		$this->load->library('upload', $config);
		
		if ( $this->upload->do_upload($fieldname)) {
			return $this->upload->data();				
			
		} else {
			set_error($this->upload->display_errors());
			log_message('error', var_export($this->upload->display_errors(), true));
		}
	}
	
	function set_photo($upload_data, $post_data) {
		
		if ( ! isset($this->load->_ci_classes) OR  ! isset($this->load->_ci_classes['image_lib']))
		{
			$this->load->library('image_lib');
		}
		$config = $this->load->config();
		
		/**
		 * verify extention call exec for convert to jpeg if file has tiff, raw extention
		 */
		$to_convert = array(".tif", ".tiff", ".jp2", ".pcx", ".tif", ".psd", "cpt"); 
		
		if(in_array(strtolower($upload_data['file_ext']), $to_convert)){
			log_message('debug', 'this file is TIFF.');
			$file_path_in = $upload_data['full_path'];
			$file_path_out = $upload_data['file_path'].$upload_data['raw_name'].".jpg";
			
			$result = $this->image_lib->convert_to_jpeg($file_path_in, $file_path_out);
			if($result) {
				unset($file_path_in);
				
				$file_out_name = $upload_data['raw_name'].".jpg";
				
				$upload_data['file_name'] = $file_out_name;
				$upload_data['file_type'] = "image/jpeg";
				$upload_data['full_path'] = $upload_data['file_path'].$file_out_name;
				$upload_data['orig_name'] = $file_path_out;
				$upload_data['file_ext'] = ".jpg";											
			}
		}
		
		$this->load->model('photo_mdl','photo');
						
		$photo_id = $this->photo->add_photo($post_data['title'], $post_data['user_id']);
		
		if ($photo_id) {
			
			$img_size = $this->config->item('img_size');
			
			$original_path = $upload_data['file_path'].$upload_data['raw_name'].$upload_data['file_ext'];			
			$new_path_lg = $upload_data['file_path'].$photo_id.'-lg'.$upload_data['file_ext'];
			
			if(!rename($original_path, $new_path_lg)) {
				log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded file hasn\'t been renamed!');
			}
			
			$config['image_library'] = 'gd2';
			$config['source_image'] = $new_path_lg;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = FALSE;
			$config['width'] = $img_size['head']['width'];
			$config['height'] = $img_size['head']['height'];
												
			list($src['width'], $src['height']) = getimagesize($new_path_lg);
			$y_axis = round(($src['height']) /2) - $img_size['head']['height']/2;
			$x_axis = round(($src['width']) /2) - $img_size['head']['width']/2;
			
			$flag_res = 0;
			if ($src['height'] < $img_size['head']['height'])
			{						
				$config['height'] = $img_size['head']['height'];	
				$flag_res = 1;					
			}				
			if ($src['width'] < $img_size['head']['width'])
			{						
				$config['width'] = $img_size['head']['width'];	
				$flag_res = 1;					
			}
								
			if ($flag_res == 1)
			{
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				
				$original_path = $upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];
				$new_path_h = $upload_data['file_path'].$photo_id.'-head'.$upload_data['file_ext'];
				
				if(!rename($original_path, $new_path_h)){
					log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to head size!');												
				}
				
				list($src['width'], $src['height']) = getimagesize($new_path_h);
				$y_axis = round(($src['height']) /2) - $img_size['head']['height']/2;
				$x_axis = round(($src['width']) /2) - $img_size['head']['width']/2;					
				
				$config['source_image'] = $new_path_h;
				
				$original_path = $upload_data['file_path'].$photo_id.'-head_thumb'.$upload_data['file_ext'];					
				
			}
			else //if image is bigger then head-size-suppozed
			{															
				$original_path = $upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];														
			}
				
			$config['y_axis'] = $y_axis;
			$config['x_axis'] = $x_axis;
			$config['height'] = $img_size['head']['height'];
			$config['width']  = $img_size['head']['width'];
			
			$this->image_lib->initialize($config);
			
			$this->image_lib->crop();	
								
			$new_path_h = $upload_data['file_path'].$photo_id.'-head'.$upload_data['file_ext'];
			
			if(!rename($original_path, $new_path_h)) {
				log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to head size!');
			}
			
			$this->image_lib->clear();
			
			/*$config['source_image'] = $new_path_lg;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = FALSE;
			$config['width'] = $img_size['middle']['width'];
			$config['height'] = $img_size['middle']['height'];
			
			list($src['width'], $src['height']) = getimagesize($new_path_lg);
			
			if (($src['height']< $config['height'])&& ($src['width']< $config['width']))
			{
				$config['height'] = $src['height'];
				$config['width'] = $src['width'];
			}
			
			$this->image_lib->initialize($config);
			$this->image_lib->resize();

			$original_path = $upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];
			$new_path_md = $upload_data['file_path'].$photo_id.'-md'.$upload_data['file_ext'];
			
			if(!rename($original_path, $new_path_md)) {
				log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to middle size!');
			}*/
			// middle
			$config['image_library'] = 'gd2';
			$config['source_image'] = $new_path_lg;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
								
			$config['width'] = $img_size['middle']['width'];
			$config['height'] = $img_size['middle']['height'];
			
			list($src['width'], $src['height']) = getimagesize($new_path_lg);
			//in case of small image we dont resize 
			if (($src['height']< $config['height'])&& ($src['width']< $config['width']))
			{
				$config['height'] = $src['height'];
				$config['width'] = $src['width'];
			}
			
			$this->image_lib->initialize($config);
			$this->image_lib->resize();

			$original_path = $upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];
			$new_path_md = $upload_data['file_path'].$photo_id.'-md'.$upload_data['file_ext'];
			$res = rename($original_path, $new_path_md);
			if ( ! $res)
				log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to middle size!');
		
			$this->image_lib->clear();
			
			$config['image_library'] 	= 'gd2';
			$config['source_image'] = $new_path_lg;
			
			list($src['width'], $src['height']) = getimagesize($new_path_lg);
												
			if ($src['width'] >= $src['height']*$img_size['thumbnail']['width'] / $img_size['thumbnail']['height'] )
				$config['width'] = $img_size['thumbnail']['width'];
			else
				$config['height'] = $img_size['thumbnail']['height'];
			
			$config['maintain_ratio'] = TRUE;
						
			//in case of small image we dont resize 
			if (($src['height']< $img_size['thumbnail']['height'])&& ($src['width']< $img_size['thumbnail']['width']))
			{
				$config['height'] = $src['height'];
				$config['width'] = $src['width'];
			}
			
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			
			$new_path_sm = $upload_data['file_path'].$photo_id.'-sm'.$upload_data['file_ext'];
			
			if(!rename($original_path, $new_path_sm)) {
				log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo hasn\'t been resized to small size!');
				
			} else {
				//Aply Unsharp Mask
				$real_size = getimagesize($new_path_sm);
				log_message('debug', '$real_size '.var_export($real_size, true));						
				$usm = imagecreatetruecolor($real_size[0], $real_size[1]);
				$sm_img = imagecreatefromstring(file_get_contents($new_path_sm));
				imagecopy($usm, $sm_img, 0, 0, 0, 0, $real_size[0], $real_size[1]);
				imagedestroy($sm_img);
				$this->load->helper('unsharp');
				$usm = $this->UnsharpMask($usm, 125, 0.75, 0.25);
				switch($real_size[2]) {
					case '1'; // It GIF
						imagegif($usm, $new_path_sm);
						break;
					case '2'; // It JPG
						imagejpeg($usm, $new_path_sm);
						break;
					case '6'; // It BMP
						imagewbmp($usm, $new_path_sm);
						break;
					default: // Will be PNG
						imagepng($usm, $new_path_sm);
						break;
				}
				imagedestroy($usm);
			}

			$md = getimagesize($new_path_md);
			$sm = getimagesize($new_path_sm);
			$exif = $post_data['exif'];
			
			$photo_extras = array(
				'photo_id'           => $photo_id,
				'lg_width'           => $upload_data['image_width'],
				'lg_height'          => $upload_data['image_height'],
				'size'	             =>	$upload_data['file_size'],
				'extension'	         =>	$upload_data['file_ext'],
				'md_width'           => $md[0],
				'md_height'          => $md[1],
				'sm_width'           => $sm[0],
				'sm_height'          => $sm[1],
				'exif_camera'        => (isset($exif['Model'])?$exif['Model']:NULL),
				'exif_shooting_date' => (isset($exif['DateTime'])?$exif['DateTime']:NULL),
				'exif_focal_length'  => (isset($exif['COMPUTED']['ApertureFNumber'])?$exif['COMPUTED']['ApertureFNumber']:NULL),
				'exif_exposure_time' => (isset($exif['ExposureTime'])?$exif['ExposureTime']:NULL),
				'exif_aperture'      => (isset($exif['ApertureValue'])?$exif['ApertureValue']:NULL),
				'exif_focus_dist'    => (isset($exif['FocalLength'])?$exif['FocalLength']:NULL),
				'description'        => $post_data['description'],
				'view_allowed'       => $post_data['view_allowed'],
				'erotic_p'           => $post_data['erotic_p'],
				'date_modified'      => date("Y-m-d H:i:s")
			);

			if  ($post_data['view_allowed'] == 0) {
				$photo_extras['view_password'] = $post_data['pwd'];
			}
			
			if(!$this->photo->update_photo($photo_id, $photo_extras)){
				log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo data hasn\'t been updated in DB!');
			}
			
			$this->_last_insert_photo = $photo_extras;
		
			if (!empty($post_data['comp']))
	        	modules::run('competition_mod/competition_ctr/add_work_to_competition2', $photo_id, $post_data['comp']);
	
			return $photo_id;
			
		} else {
			unlink($upload_data['full_path']);
			log_message("error", "error_data_saving");
			set_error('error_data_saving');
		}
	}
	
	function add_album($info, $categories) {
		$this->load->helper(array('form', 'url'));
		$this->load->library('validation');

		$rules['album_title'] = "required";
		$rules['album_description'] = "required";
		$rules['album_cat'] = "required";
		$rules['view_allowed'] = "required";

		$this->validation->set_rules($rules);

		$fields['album_title'] = lang('album_title');
		$fields['album_description'] = lang('album_desc');
		$fields['album_cat'] = lang('album_cat');
		$fields['view_allowed'] = lang('album_rule');

		$this->validation->set_fields($fields);

		$this->load->model('album_mdl','album');

		$user_id = $this->db_session->userdata('user_id');
		if(!$user_id) {
			$this->validation->_error_array['logged_in'] =  "You should be logged in";
		}
		if ($this->validation->run() == FALSE) {
			$data['categories'] = $categories;
			$data['albums'] = $this->album->get_albums('all');
			$this->load->view('album_add', $data);
		} else {
			$res = $this->album->add($info, $user_id);
			if($res) {
				$cat = $_REQUEST['album_cat'];
				redirect('catalog/category/'.$cat);
			} else {
				set_error($this->album->getError());
				$data['categories'] = $categories;
				$data['albums'] = $this->album->get_albums('all');
				
				$this->load->view('album_add', $data);
			}
		}
	}

	function edit_album($categories, $album_id, $password = null){
		if (isset($_REQUEST['submitted'])) {
			$this->load->model('album_mdl','album');

			$user_id = $this->db_session->userdata('user_id');
			$album = $this->album->edit($_REQUEST, $album_id, $user_id);

			redirect('catalog/category/'.$_POST['album_cat']);
		} else {
			$catalog = $this->load->model('album_mdl','album');

			$data['categories'] = $categories;
			$album = $this->album->get_album($album_id);
			$data['album'] = $album;
			if(!empty($album->view_password)) {
				if(empty($password)) {
					$data['password'] = false;
				} else {
					$password = md5($password);
					if($album->view_password == $password) {
						$data['password'] = true;
					} else {
						$data['password'] = false;
					}
				}
			}
			$this->load->view('album_edit', $data);
		}
	}

	function edit_photo() {
		if (isset($_POST)) {
			$is_erotic = false;
			if(isset($_POST['erotic_p']) && ($_POST['erotic_p'] == 'on')) $is_erotic = true;

			$password = null;
			if(!empty($_POST['photo_password'])) {
				$password = $_POST['photo_password'];
				$password = md5($password);
			}
			if(!empty($_POST['photo_desc'])) {
				$description = $_POST['photo_desc'];
			}

			$photo_extras = array(
				'photo_id'         => $_POST['photo_id'],
				'title'            => $_POST['photo_name'],
				'moderation_state' => $_POST['photo_mod_status'],
				'erotic_p'         => $is_erotic,
				'view_password'	   => $password,
				'description'      => $description
			);
			$this->load->model('photo_mdl','photo');
			$res = $this->photo->update($photo_extras);
//			if ( ! $res) log_message('error',__FILE__.', line '.__LINE__.', ERROR: uploaded photo data hasn\'t been updated in DB!');
			if ($res) {
				$this->_last_insert_photo = $_POST;
				$this->_last_insert_photo['photo_id'] = $_POST['photo_id'];
				unset($_POST);
				return TRUE;
			} else {
				set_error('error_data_saving');
				return FALSE;
			}
		} // if (isset($_FILES['photo']))
		return FALSE;
	}

	function delete_photo($photo_id){
		$this->load->model('photo_mdl','photo');
		$photo = $this->photo->get($photo_id, 'all');
		$user_id = $this->db_session->userdata('user_id');
		$res = $this->photo->delete($photo_id, $user_id);
	}

	function remove_photo($photo_id){
		$this->load->model('photo_mdl','photo');
		return $this->photo->remove_photo($photo_id);
	}
	
	function remove_album($album_id){
		$this->load->model('album_mdl','album');
		return $this->album->remove_album($album_id);
	}
	
	function decline_album($album_id, $user_id) {
		$this->load->model('album_mdl','album');
		$this->load->model('photo_mdl','photo');

		$cat = $this->album->get_album_category($album_id);
		$res = $this->album->decline($album_id, $user_id);

		redirect('catalog/category/'.$cat[0]->id);
	}

	function undecline_album($album_id, $user_id) {
		$this->load->model('album_mdl','album');
		$this->load->model('photo_mdl','photo');

		$cat = $this->album->get_album_category($album_id);

		$res = $this->album->undecline($album_id, $user_id);
		$album_photos = $this->album->get_album_photos($album_id, 0, 1, 'all');

		if(!empty($album_photos)) {
			foreach ($album_photos as $photo) {
				$this->photo->undecline($photo->photo_id, $user_id);
			}
		}
		redirect('catalog/category/'.$cat[0]->id);
	}

	function delete_album($album_id, $user_id) {
		$this->load->model('album_mdl','album');
		$this->load->model('photo_mdl','photo');
		$album_photos = $this->album->get_album_photos($album_id, 0, 'all', $user_id);
		if(!empty($album_photos)) {
			foreach ($album_photos as $photo) {
				$this->photo->delete($photo->photo_id, $user_id);
			}
		}

		$res = $this->album->delete($album_id, $user_id);
	}

	function update_prop_photo()
    {
        $action_str="!";
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
        $action_str=lang('photo_updated');//"Фото изменено!";
        if (!empty($_POST['photo_competition'])){
			$user_id = $this->db_session->userdata('user_id');
			$competition_info = array(
				'comp_work_submit' => TRUE,
				'title'            => $_POST['photo_name'],
				'description'      => $_POST['photo_desc'],
				'user_id'          => $user_id,
				'competition_id'   => $_POST['photo_competition']
			);
			$res = modules::run('competition_mod/competition_ctr/add_work_to_competition', $_POST['photo_id'], $competition_info);
			if($res) {
				$action_str.=lang("photo_in_comp");				  
			} else {
				$action_str.=lang("photo_notin_comp").lang("photo_attan_mes");
			}            
		}
		if (isset($_POST['photo_main_page']) && ($_POST['photo_main_page'] = "on")){
			modules::run('gallery_mod/gallery_ctr/set_album_main_photo', $_POST['photo_album'], $_POST['photo_id']);
            $action_str.=lang("photo_in_main");//"Фото выставленно главным!";
		} else {
			modules::run('gallery_mod/gallery_ctr/clear_album_main_photo', $_POST['photo_album'], $_POST['photo_id']);
		}
       	return $action_str;
    }
	
    function prop_action() { //--new functionality
      	if ($this->input->post("action") == "photo_prop") {
           $on_what_id=$this->input->post("photo_name");               	
           $action_str = $this->update_prop_photo();
           $res_str="{'res':1,'mes':'".$action_str."','id':'".$on_what_id."', 'tm':'3000'}";
		   $this->output->set_output($res_str);
      	}
    }
	
    function vote_action() {
		if ($this->input->post("action") == "voting") {

            $on_what="foto";
			$on_what_id=$this->input->post("on_what_id");
			$res =$this->voting->save_vote($on_what,$on_what_id);
			if ($res[0]==TRUE) {
				$bal=$this->input->post('bal');
				$votes_arr=$this->input->post("votes");
				$vote=$votes_arr[0];
				$this->rating->addAction('vote',$on_what_id,$vote);
			} else {
				$res[0] = -1;
			}
			$rating_balls=$this->rating->getBalls($on_what,$on_what_id);
            $votes_num =$this->voting->getVotes("foto",$on_what_id);
			$res_str="{'res':".$res[0].",'mes':'".$res[1]."','on_what_id':".$on_what_id.",'rating_balls':".$rating_balls.",'votes_num':".$votes_num ."}";
			$this->output->set_output($res_str);
		}
       	if ($this->input->post("action") == "placing") {
            $on_what="place";
            $foto_place=$this->input->post("jury_place");

			$on_what_id=$this->input->post("on_what_id");
           	$res=$this->voting->save_place($on_what,$on_what_id);
         	$res_str="{'res':'".$res[0]."','mes':'".$res[1]."','on_what_id':".$on_what_id.",'place':'".$foto_place."'}";
            //pr("\n vote_action!!! res_str=".$res_str);
            $this->output->set_output($res_str);
       	}
        if ($this->input->post("action") == "clear_places") {
            $photo_id = $this->input->post("photo_id");
            $res=$this->voting->clear_places($photo_id);
            $res_str="{'mes':'Места обнулены!'}";
            $this->output->set_output($res_str);
        }
	}
	function crop_action() {
       	if ($this->input->post("action") == "crop_img") {

           $photo_id=$this->input->post("photo_id");
           $photo = $this->get_photo($photo_id,'lg',MODERATION_STATE);
           if (!empty($photo)) {
               $jpeg_quality = 99;
               $src    = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;//"D:/and_1/myweb/localhost/newphh/uploads/photos/05/".$photo_id."-md.jpg";
               $src_md = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
               $dst    = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-head'.$photo->extension;//"d:/img-head.jpg";
               $img_r = imagecreatefromjpeg($src);
               $dst_r = imagecreatefromjpeg($dst);
               $targ_w = 975;
               $targ_h = 298;
               $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
               list($lg_w, $lg_h) = getimagesize($src);
               list($md_w, $md_h) = getimagesize($src_md);
               $kw=$lg_w/$md_w;
               $kh=$lg_h/$md_h;
               $x_new=$_POST['cx']*$kw;
               $y_new=$_POST['cy']*$kh;
               $w_new=$_POST['cw']*$kw;
               $h_new=$_POST['ch']*$kh;
               imagecopyresampled($dst_r,$img_r,0,0,$x_new,$y_new,$targ_w,$targ_h,$w_new,$h_new);
               imagejpeg ( $dst_r, $dst , 99 );
               $url_head=photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-head'.$photo->extension;
               $action_str="Изобажение подготовлено!";//.$photo->photo_id;
               $res='1';
           } else {
               $action_str="Ошибка фото!".$photo->photo_id;
               $res='0';
           }
           $res_str="{'res':'".$res."','mes':'".$action_str."','id':'".$photo_id."', 'dst':'".$url_head."'}";
		   $this->output->set_output($res_str);
       	}
	}
	function search_photos($keywords, $categories = null, $comments_per_page = 0, $page = 0,  $sort_type=1, $sort_order='', $moderation_state=1) {
		if(empty($keywords)) {
			set_error("error_empty_request");
			return false;
		}
		$this->load->model('photo_mdl','photo');
		$result = $this->photo->search_photos($keywords, $categories, $comments_per_page, $page,  $sort_type, $sort_order, $moderation_state, $registered, $erotic);
		return $result;
	}

	function update_photo($properties) {
		$this->load->model('photo_mdl','photo');
		return $this->photo->update($properties);
	}

	function update_album($properties) {
		$this->load->model('album_mdl','album');
		return $this->album->update($properties);
	}

	function update_photo_album($photo_id, $category_id, $album_id){
		$this->load->model('photo_mdl','photo');
		return  $this->photo->add_to_album($photo_id, $category_id, $album_id);
	}

	function set_album_main_photo($album_id, $photo_id){
		$this->load->model('album_mdl','album');
		return $this->album->set_album_main_photo($album_id, $photo_id);
	}

	function clear_album_main_photo($album_id, $photo_id){
		$this->load->model('album_mdl','album');
		return $this->album->clear_album_main_photo($album_id, $photo_id);
	}

	function album_edit($user_id, $data = array()){
		if (empty($data)) return false;
		$id = $data['album_id'];
		$erotic_p = (isset($data['erotic_p']) && ($data['erotic_p'] == "on"))? 1: 0;
		$this->load->model('album_mdl','album');
		if ($id) {
			$info = array(
				'erotic_p'          => $erotic_p,
				'album_title'       => $data['title'],
				'album_description' => $data['short_description'],
				'view_allowed'      => $data['view_allowed'],
				'album_cat'         => $data['category_id']
			);
			if (isset($data['password']) && !empty($data['password'])){
				$info['album_password'] = $data['password'];
			}
			
			return $this->album->edit($info, $id, $user_id);
		} else {
			$info = array(
				'album_title'       => $data['title'],
				'erotic_p'          => $erotic_p,
				'album_description' => $data['short_description'],
				'view_allowed'      => $data['view_allowed'],
				'album_cat'         => $data['category_id']
			);
			if (isset($data['password']) && !empty($data['password'])){
				$info['view_password'] = $data['password'];
			}
			return $this->album->add($info, $user_id);
		}
	}

	function revert_image($image_id, $user_id) {
		$this->load->model('photo_mdl','photo');
		$this->photo->revert_image($image_id, $user_id);
	}

	function revert_album($album_id, $user_id) {
		$this->load->model('album_mdl','album');
		$this->album->revert_album($album_id, $user_id);
	}
	 
    function check_registration_photo($photo, $erotic, $registered = 0)
    {
    //checking for registration to view some photos
    if (($photo->view_allowed==1) && ($registered == 0) )
       {
               set_error(lang('error_view_register'));
               return -1;
       }
    if (($photo->erotic_p==1) && ($erotic == -1) && ($photo->user_id != $this->db_session->userdata('user_id')))
       {
               set_error(lang('error_view_erotic'));
                 return -1;
        }
    }

    function check_access_photo($photo,$moderation_state)
    {
    if (($photo->user_id != $this->db_session->userdata('user_id')) && ($photo->moderation_state < $moderation_state))
        {
            switch ($photo->moderation_state)
            {
            case -2:  set_error(lang('photo_deleted'));
                      return -1;
            case -1:  set_error(lang("photo_declined"));
                      return -1;
            case 0:   set_error(lang("photo_not_approved"));
                      return -1;
            case 1:   set_error(lang("photo_not_good"));
                      return -1;
            }
        }
    }
   
    function photo_like($photo_id){
    	$this->load->model('photo_mdl','photo');
		$photo = $this->photo->get($photo_id, 'all');
		if($photo) {
			if(is_array($photo)) $photo = $photo[0];
			$photo->score++;
			$photo = (array)$photo;
			unset($photo['albums']);
			return $this->photo->update_photo($photo_id, $photo);
		}
    }
	
	function UnsharpMask($img, $amount, $radius, $threshold)    { 

////////////////////////////////////////////////////////////////////////////////////////////////  
////  
////                  Unsharp Mask for PHP - version 2.1.1  
////  
////    Unsharp mask algorithm by Torstein H?nsi 2003-07.  
////             thoensi_at_netcom_dot_no.  
////               Please leave this notice.  
////  
///////////////////////////////////////////////////////////////////////////////////////////////  



    // $img is an image that is already created within php using 
    // imgcreatetruecolor. No url! $img must be a truecolor image. 

    // Attempt to calibrate the parameters to Photoshop: 
    if ($amount > 500)    $amount = 500; 
    $amount = $amount * 0.016; 
    if ($radius > 50)    $radius = 50; 
    $radius = $radius * 2; 
    if ($threshold > 255)    $threshold = 255; 
     
    $radius = abs(round($radius));     // Only integers make sense. 
    if ($radius == 0) { 
        return $img; imagedestroy($img); break;        } 
    $w = imagesx($img); $h = imagesy($img); 
    $imgCanvas = imagecreatetruecolor($w, $h); 
    $imgBlur = imagecreatetruecolor($w, $h); 
     

    // Gaussian blur matrix: 
    //                         
    //    1    2    1         
    //    2    4    2         
    //    1    2    1         
    //                         
    ////////////////////////////////////////////////// 
         

    if (function_exists('imageconvolution')) { // PHP >= 5.1  
            $matrix = array(  
            array( 1, 2, 1 ),  
            array( 2, 4, 2 ),  
            array( 1, 2, 1 )  
        );  
        imagecopy ($imgBlur, $img, 0, 0, 0, 0, $w, $h); 
        imageconvolution($imgBlur, $matrix, 16, 0);  
    }  
    else {  

    // Move copies of the image around one pixel at the time and merge them with weight 
    // according to the matrix. The same matrix is simply repeated for higher radii. 
        for ($i = 0; $i < $radius; $i++)    { 
            imagecopy ($imgBlur, $img, 0, 0, 1, 0, $w - 1, $h); // left 
            imagecopymerge ($imgBlur, $img, 1, 0, 0, 0, $w, $h, 50); // right 
            imagecopymerge ($imgBlur, $img, 0, 0, 0, 0, $w, $h, 50); // center 
            imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h); 

            imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 33.33333 ); // up 
            imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 25); // down 
        } 
    } 

    if($threshold>0){ 
        // Calculate the difference between the blurred pixels and the original 
        // and set the pixels 
        for ($x = 0; $x < $w-1; $x++)    { // each row
            for ($y = 0; $y < $h; $y++)    { // each pixel 
                     
                $rgbOrig = ImageColorAt($img, $x, $y); 
                $rOrig = (($rgbOrig >> 16) & 0xFF); 
                $gOrig = (($rgbOrig >> 8) & 0xFF); 
                $bOrig = ($rgbOrig & 0xFF); 
                 
                $rgbBlur = ImageColorAt($imgBlur, $x, $y); 
                 
                $rBlur = (($rgbBlur >> 16) & 0xFF); 
                $gBlur = (($rgbBlur >> 8) & 0xFF); 
                $bBlur = ($rgbBlur & 0xFF); 
                 
                // When the masked pixels differ less from the original 
                // than the threshold specifies, they are set to their original value. 
                $rNew = (abs($rOrig - $rBlur) >= $threshold)  
                    ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))  
                    : $rOrig; 
                $gNew = (abs($gOrig - $gBlur) >= $threshold)  
                    ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))  
                    : $gOrig; 
                $bNew = (abs($bOrig - $bBlur) >= $threshold)  
                    ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))  
                    : $bOrig; 
                 
                 
                             
                if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) { 
                        $pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew); 
                        ImageSetPixel($img, $x, $y, $pixCol); 
                    } 
            } 
        } 
    } 
    else{ 
        for ($x = 0; $x < $w; $x++)    { // each row 
            for ($y = 0; $y < $h; $y++)    { // each pixel 
                $rgbOrig = ImageColorAt($img, $x, $y); 
                $rOrig = (($rgbOrig >> 16) & 0xFF); 
                $gOrig = (($rgbOrig >> 8) & 0xFF); 
                $bOrig = ($rgbOrig & 0xFF); 
                 
                $rgbBlur = ImageColorAt($imgBlur, $x, $y); 
                 
                $rBlur = (($rgbBlur >> 16) & 0xFF); 
                $gBlur = (($rgbBlur >> 8) & 0xFF); 
                $bBlur = ($rgbBlur & 0xFF); 
                 
                $rNew = ($amount * ($rOrig - $rBlur)) + $rOrig; 
                    if($rNew>255){$rNew=255;} 
                    elseif($rNew<0){$rNew=0;} 
                $gNew = ($amount * ($gOrig - $gBlur)) + $gOrig; 
                    if($gNew>255){$gNew=255;} 
                    elseif($gNew<0){$gNew=0;} 
                $bNew = ($amount * ($bOrig - $bBlur)) + $bOrig; 
                    if($bNew>255){$bNew=255;} 
                    elseif($bNew<0){$bNew=0;} 
                $rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew; 
                    ImageSetPixel($img, $x, $y, $rgbNew); 
            } 
        } 
    } 
    imagedestroy($imgCanvas); 
    imagedestroy($imgBlur); 
     
    return $img; 

} 

	function ajax_actions()
    {
		$action = $this->input->post('action');
		$data = '';
		switch ($action ) {
			case "albumedit":
				$data = $this->album_edit_new (); //it's album_id
				//log_message ('debug', "create album ajax if = " . $data);
				break;
			case "deletephoto":
				echo "deletephoto";
				$photo_id = $this->input->post('photo_id');
				if ($this->delete_photo($photo_id))
					$data = 0;// error
				else
					$data = 1; 	
				echo $data;
				break;						
		}
		
		$this->output->set_output($data);
    }
    
	function album_edit_new ()
	{
		$user_id = $this->db_session->userdata('user_id');
		$this->load->model('album_mdl','album');

		$info = array(
				'album_title'       => $this->input->post('title'),
				'erotic_p'          => $this->input->post('erotic_p'),
				'album_description' => $this->input->post('short_description'),
				'view_allowed'      => $this->input->post('view_allowed'),
				'album_cat'         => $this->input->post('category_id')
		);
		
		return $this->album->add($info, $user_id);
	}
/*-------------------------------------scripts block-------------------------------------------------------*/	

	//this function sets white margins of thumbs preview
	function small_img_prepare ($photos, $pattern_width = 144, $pattern_height = 144)
	{		
	  if (!empty ($photos))
	  {
		foreach ($photos as $photo) 
		{
				if (!$this->check_private($photo))         
	              {
	              	  $photo->urlImg = photo_location()."/lock.jpg";       
                  	  $photo->margin_left = 0.5 * ($pattern_width - 111);
                  	  $photo->margin_right = $photo->margin_left;
                  	  $photo->margin_top = 0.5 * ($pattern_height - 111);
		              $photo->margin_bottom =  $photo->margin_top;
		              $photo->img_width  =111;
                      $photo->img_height =111;
	              }
	              else
	              {  //$photo->title = $this->check_private($photo);
	                 $photo->urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension;           
					 if (($photo->sm_height < $pattern_height ) &&  ($photo->sm_width < $pattern_width ) )
					 {
					 	$photo->margin_top    = 0.5 * ($pattern_height - $photo->sm_height);
					 	$photo->margin_bottom = $photo->margin_top;
					 	$photo->margin_left  = 0.5 * ($pattern_width - $photo->sm_width);
					 	$photo->margin_right = $photo->margin_left;

                        $photo->img_width  =$photo->sm_width;
                        $photo->img_height =$photo->sm_height;
					 }
					 else // if !(($photo->sm_height < 138 ) &&  ($photo->sm_width < 145 ) )
					 {
		                 if ($photo->land )
		              	  {
		              	  	$photo->margin_top    = 0.5 * ($pattern_height - $photo->sm_height);
		              	  	$photo->margin_bottom = 0.5 * ($pattern_height - $photo->sm_height);
		              	  	$photo->margin_left = 4;//0;
						 	$photo->margin_right = $photo->margin_left;
                            $photo->img_width  = 136;
                            $photo->img_height = $photo->sm_height;
		              	  }
		               	  else
		              	  {
		              	  	$photo->margin_left  = 0.5 * ($pattern_width - $photo->sm_width);;//0.25 * ($pattern_width - $photo->sm_width);
		              	  	$photo->margin_right =0.5 * ($pattern_width - $photo->sm_width);//0.75 * ($pattern_width - $photo->sm_width);
		              	  	$photo->margin_top    = 4;//0.5*($pattern_height - $photo->sm_height);
		              	  	$photo->margin_bottom = $photo->margin_top;//0;
                            $photo->img_width  = $photo->sm_width;//98;
                            $photo->img_height = 136;//$photo->sm_height;
		              	  }
					 } // if !(($photo->sm_height < 138 ) &&  ($photo->sm_width < 145 ) )
	          } //if ((!$my)&&(!empty ($photo->view_password)))
		} //foreach ($photos as $photo) 
		return $photos;
	  }
	  else
	  	return 0;
	}
	
	//if the photo is private, but user has already passed correct pwd, we display this photo at each page
	function check_private($photo)
	{	
		if ($photo->view_allowed != 0)
			return 10;

		if ( $this->db_session->userdata('user_id') ) 
			if ($photo->user_id == $this->db_session->userdata('user_id') )
		 		return 20;
		 	
		if (empty($photo->view_password)) 
			return 0;
			
		if (!empty($photo->view_password)) 
		{
		 	$password = null;
			$view_photo = null;
			
			$view_photo = $this->db_session->userdata('view_photo');
			if($view_photo) 
			{
				if(isset($view_photo[$photo->photo_id])) 
					$password = $view_photo[$photo->photo_id];
			}
		
			if(empty($password)) 
					return 0;
			else 
			{
				if ($photo->view_password == $password) 
					return 30;
				else 							
					return 0;			
			}	
					
		 } //if (!empty($photo->view_password)) 
		return 0; 
	}
		 
	//resize of all thumbs in dirrectory $dirnum
	function read_thmb_dir($dirnum)
	{
		
		$this->load->library('image_lib');
		$config = $this->load->config();
	
		$url = $this->config->item('photo_upload_dir')."/".$dirnum;
		echo $url ; 
		
		if (is_dir($url)) 
		{
			if ($dir = opendir($url)) 
			{
				while (false !== ($file = readdir($dir)))
				{
					if ($file != "." && $file != ".." && is_file($url."/".$file)) 
					{
						$path_info = pathinfo($url."/".$file);
							
						if (strpos ( $path_info['basename'], "-sm")	)				
						{
												
							echo $url."/".$file;
							list($src['width'], $src['height']) = getimagesize($url."/".$file);
							$y = $src['height'];
							$x = $src['width'];
							if ($x >= $y*145/138 )
							{
								$config['width'] = 145;
								//$config['height'] = null;
							}
							else
							{	
								//$config['width'] = null;	
								$config['height'] = 138;
							}
							//$config['create_thumb'] = TRUE;
							
							$config['image_library'] 	= 'gd2';
							$config['source_image'] 	= $url . "/" .$file;
							$config['maintain_ratio'] 	= TRUE;
							$this->image_lib->initialize($config);
							
							
							print_r ($config); 
							echo "<br>";
							echo $x .'process ' . $y .'<br>';	
															
							
							$this->image_lib->resize();
							$this->image_lib->clear();
						}
					}
				}
			}
		}
	}	
	
	//get new head sized photos from large in case of some errors
	function create_nice_head($dirnum)
	{
		
		$this->load->library('image_lib');
		$config = $this->load->config();
	
		$url = $this->config->item('photo_upload_dir')."/".$dirnum;
		echo $url ; 
		$img_size = $this->config->item('img_size');
		
		if (is_dir($url)) 
		{
			if ($dir = opendir($url)) 
			{
				while (false !== ($file = readdir($dir)))
				{
					if ($file != "." && $file != ".." && is_file($url."/".$file)) 
					{
						$path_info = pathinfo($url."/".$file);
						$ext =  $path_info['extension'];	
						
						$name = substr($file, 0, 3);
							if ($name[2] == '-')
								$name = substr ($name, 0, 2); 

								
						if (strpos ( $path_info['basename'], "-lg")	)				
						{
							$new_path_lg = $url . "/" .$file;
							echo $new_path_lg . "<br>";
							$config['source_image'] 	= $url . "/" .$file;
							$config['new_image'] 		= $url . "/" .$name . "-head." . $ext ;
							
							
							$config['image_library'] = 'gd2';
					
				
					
					list($src['width'], $src['height']) = getimagesize($new_path_lg);
					$y_axis = round(($src['height']) /2) - $img_size['head']['height']/2;
					$x_axis = round(($src['width']) /2) - $img_size['head']['width']/2;
					
		
					
					$config['maintain_ratio'] = FALSE;
					$config['width'] = $img_size['head']['width'];
					$config['height'] = $img_size['head']['height'];	
					$flag_res = 0;
					if ($src['height'] < $img_size['head']['height'])
					{						
						$config['height'] = $img_size['head']['height'];	
						$flag_res = 1;					
					}				
					if ($src['width'] < $img_size['head']['width'])
					{						
						$config['width'] = $img_size['head']['width'];	
						$flag_res = 1;					
					}
					
					if ($flag_res == 1)
					{					
						$this->image_lib->initialize($config);
						$this->image_lib->resize();
						
						$original_path = $config['new_image'];//$upload_data['file_path'].$photo_id.'-lg_thumb'.$upload_data['file_ext'];
						$new_path_h = $config['new_image']; //$upload_data['file_path'].$photo_id.'-head'.$upload_data['file_ext'];
						
							
						list($src['width'], $src['height']) = getimagesize($new_path_h);
						$y_axis = round(($src['height']) /2) - $img_size['head']['height']/2;
						$x_axis = round(($src['width']) /2) - $img_size['head']['width']/2;					
						
						$config['source_image'] = $new_path_h;
						 $config['new_image'] = $url . "/" .$name . "-head." . $ext ;
						
					}
					
					$config['y_axis'] = $y_axis;
					$config['x_axis'] = $x_axis;
					$config['height'] = $img_size['head']['height'];
					$config['width']  = $img_size['head']['width'];

					$this->image_lib->initialize($config);							
					if ( ! $this->image_lib->crop())
					{
					   echo $this->image_lib->display_errors();
					}	
										
						$this->image_lib->clear();
					}
					}
				}
			}
		}
	}	

	//in case of some problems after resise we call this
	function update_db_photos($dirnum)
	{
		
		$this->load->library('image_lib');
		$config = $this->load->config();
		$this->load->model('photo_mdl','photo');
		
		$url = $this->config->item('photo_upload_dir')."/".$dirnum;
		echo $url ; 
		
		if (is_dir($url)) 
		{
			if ($dir = opendir($url)) 
			{
				while (false !== ($file = readdir($dir)))
				{
					if ($file != "." && $file != ".." && is_file($url."/".$file)) 
					{
						$path_info = pathinfo($url."/".$file);
							
						if (strpos ( $path_info['basename'], "-sm")	)				
						{

							list($src['width'], $src['height']) = getimagesize($url."/".$file);
			
							
							$name = substr($file, 0, 3);
							if ($name[2] == '-')
								$name = substr ($name, 0, 2); 
					 		$data['photo_id'] = $name;
					 		$data ['sm_width'] = $src['width'];
					 		$data ['sm_height'] = $src['height'];
					 		
							$result = $this->photo->update($data);

							echo $file . "name = " . $name .'; width = '. $src['width'] . ';  height = '. $src['height'] .'<br>';	

			
						}
					}
				}
			}
		}
	}	
    function get_places_list() {
       $data['table_rez'] =$this->voting->get_places_list();
       $result = $this->load->view('place_list', $data);
       return $result;
    }
	/*----------------------------------old funcs-------------------------------------------------*/
	
	function regulate_search_photos($id_array, $categories = null, $comments_per_page = 0, $page = 0,  $sort_type=1, $sort_order='', $moderation_state=1,  $registered=0, $erotic=-1) {
		$this->load->model('photo_mdl','photo');
		$result = $this->photo->regulate_search_photos($id_array, $categories, $comments_per_page, $page,  $sort_type, $sort_order, $moderation_state, $registered, $erotic);
		return $result;
	}
	
	}