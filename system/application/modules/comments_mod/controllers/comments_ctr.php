<?php
class Comments_ctr extends Controller {

	var $_lng = '';
	
    function Comments_ctr()
    {
         parent::Controller();
		 
         $this->_lng = $this->db_session->userdata('user_lang');
         $this->load->language('comments',$this->_lng);

         $this->load->model('comment_mdl','comment');
    }
    
    function get_object_comments_admin($commented_object_type='', $commented_object_id=null, $per_page=0, $page=1, $filters=array(), $sort_ord = "", $moderation_state=""){
		return $this->comment->get_object_comments_admin($commented_object_type, $commented_object_id, $per_page, $page, $filters, $sort_ord, $moderation_state);
    }
    
	function get_object_comments($commented_object_type='', $commented_object_id='', $per_page=0, $page=1, $filters=array(), $sort_ord = "", $moderation_state=null, $filter_jury=true){
		return $this->comment->get_object_comments($commented_object_type, $commented_object_id, $per_page, $page, $filters, $sort_ord, $moderation_state, $filter_jury);
    }
    
   	function get_object_comments_count(){
    	return $this->comment->found_rows();
    }

    //edited
    function get_object_comments_count_by_id($commented_object_type, $commented_object_id){
    	return $this->comment->get_object_comments_count_by_id($commented_object_type, $commented_object_id);
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
    
    function notification($photo_id){
    	$this->load->library('email');
		$this->config->load('email');
		
		$reply = false;
		if(isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id'])) $reply = true;
		
			
    	$user_id = $this->db_session->userdata('user_id');
    	$user_login = $this->db_session->userdata('user_login');
    	
    	$juries = $this->config->load('../modules/gallery_mod/config/config');
		$jury_logins = $this->config->item('jury_logins');
    	
    	$photo_author = modules::run('gallery_mod/gallery_ctr/get_user',$photo_id);
    	
    	if(!empty($photo_author)) {
    		if(is_array($photo_author)) $photo_author = $photo_author[0];
    		if($user_id == $photo_author->user_id) return;
            if (empty($photo_author->comment_can) == true ) return;
            if(in_array($user_login, $jury_logins)) return;
    		
	    	$val = array();
	    	$val['photo_author_login'] = $photo_author->login;
	    	$val['comented_user_login'] = $user_login;
	    	$val['photo_id'] = $photo_id;
	    		    	
    		if($reply) {
    			$subject = $user_login." ответил на Ваш комментарий...";
    			$message = $this->load->view('notification_reply', $val, true);
    			
    		} else {
    			$subject = $user_login." прокомментировал вашу фотографию...";
    			$message = $this->load->view('notification', $val, true);
    		}
	    	
	    	$this->load->helper('email');
			$result = send_email($photo_author->email, $subject, $message);
	    	
	    	log_message('error', 'comment notification: '.var_export($result, true));			
    	}
    	return true;
    }

	function view_object_comments($commented_object_type, $commented_object_id, $user_avatar_path='', $per_page=0, $page=1, $moderation_state = 0, $filters=array(), $jury_logins=true){
		if (isset($_POST['comment_body'])) {
			$saved = $this->add_comment($commented_object_type, $commented_object_id);
			if (!$saved) {
				set_error('error_data_saving');
			} else {
				// send confirm email
				$this->notification($commented_object_id);
				redirect(current_url());
			}
		}
		if (isset($_POST['removed_lft'])) {
			$removed = $this->comment->remove($_POST['removed_lft'], $_POST['removed_rgt'], $commented_object_type);
			if($removed) {
				unset($_POST);
			}
			else {
				set_error('error_data_saving');
			}
		}		
		$data['comments'] = $this->get_object_comments($commented_object_type, $commented_object_id, $per_page, $page, $filters, null, $moderation_state, $jury_logins); //only accepted comments
		$data['user_avatar_path'] = $user_avatar_path;
		$data['paginate_args'] = array(
			'total_rows' =>  $this->get_object_comments_count(),
			'per_page' => $per_page
		);	
		$this->load->view('comments_new',$data);
	}
    
	function add_comment($object_type, $object_id){
		if ( empty($object_type) || empty($object_id) ) {
			return FALSE;
		}
		
		$user_id = $this->db_session->userdata('user_id');
		if ($user_id) 
		{
			$this->load->library('form_validation');
			$this->load->language('form_validation',$this->_lng);
			$config = $this->load->config('form_validation');
			$cfg = $this->config->item('comments_ctr/add_comment');
        	$this->form_validation->set_rules($cfg); 
        	
			if ($this->form_validation->run() == TRUE)
			{
				$comment_id = $this->comment->add($object_type, $object_id, $user_id, '', $_POST['comment_body'],$_POST['parent_id']);
				if($comment_id) {
					unset($_POST);
                    $this->rating->addAction('comment',$object_id);//--add rating to foto not for user
					return TRUE;
				}				
			}
		}
		return FALSE;
    }
    
    function add_comment_photo_gallery($photo_id, $comment_body){
    	
    	$user_id = $this->db_session->userdata('user_id');
    	if ($user_id) {    	
	    	$comment_id = $this->comment->add("photo", $photo_id, $user_id, '', $comment_body);
			if($comment_id) {
				$this->rating->addAction('comment',$photo_id);
				return TRUE;
			}
			
    	} return false;
    }
    
    function moderate_comments($per_page, $page)
    {
    	$data['comments'] = $this->get_object_comments( null, null, $per_page, $page );
		$data['paginate_args'] = array(
			'total_rows' =>  $this->get_object_comments_count(),
			'per_page' => $per_page
			);
			
    	$this->load->view('comments_moderation',$data);
    }
        
	function ajax_actions()
    {
		$action = $this->input->post('action');
      	
		$page = $this->db_session->userdata('comments_page');
    	$per_page = $this->db_session->userdata('comments_per_page');
		
		$data = '';
		
		switch ($action ) {
			
			case "update_moderation_state":
				$comments = (object)unserialize($this->input->post('comments'));
					
				if(!empty($comments) && isset($comments->chb) && !empty($comments->chb)) {
					$comments_chb = $comments->chb;
					
					foreach ($comments_chb as $chb) {
						
						$comment_id = $chb['comment_id'];
						$moderation_state = $chb['ms'];
						
						$this->update_moderation_state($comment_id, $moderation_state);
					}
					$data = 1;
					
				} elseif (!empty($comments)) {
					
					$comment_id = $comments->comment_id;
					$moderation_state = $comments->moderation_state;
					
					$data = $this->update_moderation_state($comment_id, $moderation_state);				
				}
				
			break;
				
			case "filter":				
				$filter = $this->input->post("filters"); 				
				$page = 1;
				$per_page = 20;
				$result = $this->view_comment_list($filter, $page, $per_page, ' comment_date desc ');
				$result = (Object)$result;
				$data = json_encode($result);
				
			break;
			
			case "delete":
				$lft = $this->input->post("lft");
				$rgt = $this->input->post("rgt");
				$commented_object_type = $this->input->post("commented_object_type");				
				$data = $this->comment->remove($lft, $rgt, $commented_object_type);
				
			break;		
		}

		//log_message('debug','AJAX Actions Response = '.$data);
		if ($data) $this->output->set_output($data);
    }
    
    function view_comments_moderation($filters=array(), $page=1, $per_page=0, $sort_ord = "", $filter_jury=false)
    {
    	$this->db_session->set_userdata('comments_page',$page);
    	$this->db_session->set_userdata('comments_per_page',$per_page);

    	$this->load->helper('BI_moderation');

    	$data['list'] = $this->get_comment_list($filters, $page, $per_page, $sort_ord, null, $filter_jury);
    	
    	$data['states'] = get_moderation_state_list();
    	if(in_array('Новые', $data['states'])) {
    		$key = array_search('Новые', $data['states']);
    		unset($data['states'][$key]);
    	}
    	
    	$data['filters'] = $filters;

    	$data['paginate_args'] = array(
			'total_rows' => $this->comment->found_rows(),
			'per_page' => $per_page,
			'cur_page' => $page,
			'prev_link' => '&lt;',
			'next_link' => '&gt;',
			'first_link' => '&lt;&lt;',
			'last_link' => '&gt;&gt;',
			'base_url' => base_url().'admin/comments/page/'
		);
		
    	$this->load->view('comments_moderation',$data);
    }
    
	function get_comment_list($filters=array(), $page=1, $per_page=0, $sort_ord="", $moderation_state=null, $filter_jury=false)
    {
    	return $this->get_object_comments( null, null, $per_page, $page, $filters, $sort_ord, $moderation_state, $filter_jury);
    }
    
	function view_comment_list($filters=array(), $page=1, $per_page=0, $sort_ord = "")
    {    	
		$sess_filters = $this->db_session->userdata('comments_filter');
    	
    	$filters = empty($filters) ? $sess_filters : $filters;
    	
    	if ( ! empty($filters)) $this->db_session->set_flashdata('comments_filter',$filters);
    	
    	$data['comments'] = $this->get_comment_list($filters, $page, $per_page, $sort_ord);
    	
    	$this->load->helper('BI_moderation');
    	
    	$data['states'] = get_moderation_state_list();
    	if(in_array('Новые', $data['states'])) {
    		$key = array_search('Новые', $data['states']);
    		unset($data['states'][$key]);
    	}  	
    	
    	$paginate_args = array(
			'total_rows' => $this->comment->found_rows(), 
			'per_page' => $per_page,
			'base_url' => url('admin_comments_url'),
			'cur_page' => $page,
			'prev_link' => '&lt;',
			'next_link' => '&gt;',
			'first_link' => '&lt;&lt;',
			'last_link' => '&gt;&gt;'
		);
    	
		$template = $this->load->view('_comments_list_block', $data, true);
		
		return array('template' => $template, 'paginate_args' => paginate($paginate_args));		 
    }
	    
    function update_moderation_state($id, $change_state)
    {
    	$this->load->helper('BI_moderation');
		
		$object_table = 'comments_tree';
		$object_id_field = 'id';
		
		return set_moderation_state($object_table, $object_id_field, $id, $change_state);
    }

	function get_objects_comments_count($commented_objects_type='', $commented_object_ids = null){
		return $this->comment->get_objects_comments_count($commented_objects_type, $commented_object_ids);
	}

	function get_comments_count_per_user($user_id){
		return $this->comment->get_comments_count_per_user($user_id);
	}
	
    function get_body_comments($commented_object_id=null,$user_id=null){
        return $this->comment->get_body_comments($commented_object_id,$user_id);
    }
}
/* end of file */