<?php
class Users_ctr extends Controller {

	private $last_upload_avatar_ext = "";
	var $_lng = '';
	var  $user_id=0;

	function Users_ctr() {
		parent::Controller();
	
		$this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('users',$this->_lng);

		$data = get_app_vars();
		$this->user_id=isset($data['user_id'])?$data['user_id']:0;
		$this->fl->base=base_url();
	}

	function register_() {
		$data = get_app_vars();
		if (!isset($_POST['username'])) {
			$this->load->view('register_form',$data);
		} else {
			$this->register_step2();
		}
	}

	function register(){
		$this->load->view('register_form');
	}

	function login_in_use_validation($str) {
		$this->load->model('user_mdl','user');
		$username = $this->input->xss_clean($str);
		if (($username == 'guest') || $this->user->get_user_id_by_login($username)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function email_in_use_validation($str) {
		$this->load->model('user_mdl','user');
		$email = $this->input->xss_clean($str);
		if ($this->user->get_user_id_by_email($email)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function register_step2_() {
		$data = array(); //get_app_vars();

		$this->load->library('form_validation');
		$config = $this->load->config('form_validation');
		$cfg = $this->config->item('users/register_step2');
		$this->form_validation->set_rules($cfg);

		if ($this->form_validation->run() == TRUE) {
			$this->load->model('user_mdl','user');

			$login = $_POST['username'];
			$email = $_POST['email'];
			$lng = empty($this->_lng)?$this->config->item('language'):$this->_lng;
			$activation_code = md5(microtime());

			$user_id = $this->user->pre_activate($login, $email, $lng, $activation_code);
			if(!empty($user_id)) {
				$activation_url = url('activation_url',
					array(
						'user_id'=>$user_id,
						'act_code'=>$activation_code
					)
				);
				$activation_url = trim(base_url(),'/').$activation_url;

				$this->lang->load('email_activation',$this->_lng);

				$subject = lang('activation_subject');
				$message = lang('activation_message');
				$message = str_replace("%login%",$login,$message);
				$message = str_replace("%activation_url%",$activation_url,$message);
				
				if ($this->send_email($email, $subject, $message)) {
					$this->load->view('activation_sent', $data);
					return;
				}
			}
		}
		$this->load->view('register_form', $data);
	}

	//we don't use it any more
	function register_step2($login, $email) {
		$this->_lng = $this->db_session->userdata('user_lang');		
		$this->load->language('form_validation',$this->_lng);
		$this->load->library('form_validation');
		$config = $this->load->config('form_validation');
		
		$cfg = $this->config->item('users/register_step2');
		
	    $this->form_validation->set_rules($cfg);
            
		if ($this->form_validation->run() == TRUE)
		{
			$this->load->model('user_mdl','user');
			$this->lang->load('phh',$this->_lng);
							
			$activation_code = md5(microtime());
			
			$user_id = $this->user->pre_activate($login, $email, $this->_lng, $activation_code);
			if(!empty($user_id)){
				
				$activation_url = url('activation_url',
										array(
											'user_id'=>$user_id,
											'act_code'=>$activation_code
											)
										);
				$activation_url = $activation_url;
								
				$this->lang->load('email_activation',$this->_lng);
				
				$subject = lang('activation_subject');
				$message = lang('activation_message');
				
				$message = str_replace("%login%",$login,$message);
				$message = str_replace("%activation_url%",$activation_url,$message);
								
				if ($this->send_email($email, $subject, $message)){
					$data = "{'status' : '1'}";
				}
				else {
					$this->db->delete('users', array('user_id' => $user_id));
					$data = "{'status' : '-1', 'email_err': '',  'login_err':'".lang('error_mailing')."'}";
					
				}
			}
			else  
			{
				$data = "{'status' : '-1', 'email_err': '', 'login_err':'".lang('error_data_saving')."'}";
			}
			
		}
		else 
		{
			$data = "{'status' : '-1', 
					  'login_err':'".$this->form_validation->error('username','<span>','</span>')."',
					  'email_err':'".$this->form_validation->error('email','<span>','</span>')."'
					  }";
		}
		return $data;
	}
	
	function remember($email) {
	
		$this->load->library('form_validation');
		$config = $this->load->config('form_validation');

		$cfg = $this->config->item('users/remember');
		
	    $this->form_validation->set_rules($cfg);
            
		if ($this->form_validation->run() == TRUE)
		{
			$this->load->model('user_mdl','user');
			
			$lng = empty($this->_lng)?$this->config->item('language'):$this->_lng;					
			
			$user_id = $this->user->get_user_id_by_email($email);
			if (!empty($user_id))
			{
				$this->lang->load('email_activation',$this->_lng);				
				$this->load->helper('string');				
				$new_pass  = random_string('alnum', 8);
				
				$this->user->update_pass($user_id,$new_pass);
				
				$this->lang->load('email_activation',$this->_lng);
				
				$subject = lang('remember_subject');
				$message = lang('remember_message');
				
				$message = str_replace("%login%", $this->user->get_name($user_id),$message);
				$message = str_replace("%password%",$new_pass,$message);
								
				if ($this->send_email($email, $subject, $message)){
					$data = "{'status' : '1'}";
				}
				else {
					$data = "{'status' : '-1', 'email_err':'".lang('error_mailing')."'}";
				}
			}
			else 
			{
				$data = "{'status' : '-1', 'email_err':'".lang('error_user_not_found')."'}";
			}			
		}
		else 
		{
			$data = "{'status' : '-1', 
					  'email_err':'".$this->form_validation->error('email','<span>','</span>')."'
					  }";
		}
		return $data;
	}
	
	function send_email($email, $subject, $message, $config=array()) {	
	
		$this->load->library('email');
		$this->config->load('email');		
		$this->load->helper('email');
		
		return send_email($email, $subject, $message);
	}

	function _mail_encode($text, $encoding) {
		$result = "=?".$encoding."?b?".base64_encode($text)."?=";
		return $result;
	}
		
	//validation of usersdata. if ok - call registration_full with writing to DB
	function register_full($login, $email, $pass1, $pass2, $birthdate, $country_id, $region_id, $city_id, $userinfo)   {

        //errors massiv - mb we wount need it
        $json_data = array ('status'=>-1,
                    'login_err'=>'','email_err'=>'', 'pass1_err'=>'', 'pass2_err'=>'' );

        //validation
        $this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('form_validation',$this->_lng);

        $this->load->library('form_validation');
		$config = $this->load->config('form_validation');
		$cfg = $this->config->item('users/register_full');
		foreach ($cfg as &$c) {
			if($c['field'] == 'username') $c['label'] = "&quot;".$login."&quot;";
			elseif($c['field'] == 'email') $c['label'] = "&quot;".$email."&quot;";
		}
	    $this->form_validation->set_rules($cfg);

		if ($this->form_validation->run() == TRUE)
		{
            $user_id = $this->registration_full($login, $email, $pass1, $birthdate, $country_id, $region_id, $city_id, $userinfo) ;
            //problem with writing in database
            if ($user_id == 0  )  {
                $json_data['login_err'] = lang('error_data_saving');
                $data = json_encode($json_data);
            }
           else    //everithyng's ok
           	{           		
           		$data = "{'status' : '1'}";

				$this->load->library('khacl');
				$this->khacl->aro->create($login,'Registered');

				$this->lang->load('email_registration_success',$this->_lng);
				
				$subject = lang('reg_success_subject');
				$subject = "=?utf-8?b?".base64_encode($subject)."?=";
				
				$message = lang('reg_success_message');
				$message = str_replace("%login%",$login,$message);
				$message = str_replace("%password%",$pass1,$message);

//				$this->load->helper('email');
//				$result = send_email($email, $subject, $message);
				
				$this->load->library('email');
				
				$this->email->clear();
				$this->email->from($this->config->item('admin_email'), $this->config->item('site_name'));
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($message, "utf-8");
				
				if ($this->email->send())
				{
					$data = "{'status' : '1'}";
				}
				else 
				{
					$this->db->delete('users', array('user_id' => $user_id));
					$data = "{'status' : '-1', 'email_err': '',  'login_err':'".lang('error_mailing')."'}";
				}				
			}
		}
		else
		{   //form validation failed
			$this->form_validation->set_error_delimiters("", "");
			$data = "{'status' : '-1',
    					  'login_err':'".$this->form_validation->error('username')."',
    					  'email_err':'".$this->form_validation->error('email')."',
                          'pass1_err':'".$this->form_validation->error('pass1')."',
                          'pass2_err':'".$this->form_validation->error('pass2')."'                          
    					  }";
		}
		return $data;
	}
	
	//adding valid user info to database. Returns flag to ajax function
    function registration_full($login, $email, $pass1, $birthdate, $country_id, $region_id, $city_id, $userinfo) {

        $this->load->model('user_mdl','user');
        $group_id = $this->user->get_group_id_by_name('reg_users');
    	$lng = empty($this->_lng)?$this->config->item('language'):$this->_lng;
        $user_id = $this->user->registration ($login, $email, $pass1, $lng, $group_id, $birthdate, $country_id, $region_id, $city_id, $userinfo);
        if (!$user_id)
            return 0;

        $this->db_session->set_userdata('user_id', $user_id);
		$this->db_session->set_userdata('user_login',$login);
		$this->db_session->set_userdata('user_group',$group_id);
		$this->db_session->set_userdata('user_lang',$lng);

		$n = date('Y-m-d');
		$age = $n - $birthdate;
		if ($age>=18) {$erotic = 1;} else {$erotic = -1;}
			$this->db_session->set_userdata('erotic_allow', $erotic);

       	return $user_id;

    }
    
    //we don't use it any more
	function activate(){
		$data = array(); //get_app_vars();
		
		$this->load->model('user_mdl','user');
		
		$activation_user_id = $this->input->xss_clean($this->uri->segment(3));
		$activation_code = $this->input->xss_clean($this->uri->segment(4));

		if (!$activation_user_id or !$activation_code)
		{
			set_error('error_activation_link');
           	return 0;
		} else {
			$userdata = $this->user->get_userdata_by_activation_info($activation_user_id, $activation_code);
			if ( ! $userdata)
			{
				set_error('error_activation_info');
				return 0;
			}
			$new_password = $this->user->activate($activation_user_id, $activation_code);
			if ( ! $new_password) 
			{
				set_error('error_activation_process');  
				return 0;
			}
			if ($userdata && $new_password)
			{
				$this->load->library('khacl');
				$this->khacl->aro->create($userdata['login'],'Registered');
				
				$this->lang->load('email_registration_success',$this->_lng);
				
				$subject = lang('reg_success_subject');
				$message = lang('reg_success_message');
				
				$message = str_replace("%login%",$userdata['login'],$message);
				$message = str_replace("%password%",$new_password,$message);
								
				if ($this->send_email($userdata['email'], $subject, $message)){
                    $data['user']   =$userdata;
                    $data['login']  =$userdata['login'];
                    $this->user_id  =$userdata['user_id'];
                    //$this->load->view('activation_success', $data);


					return $this->user_id;
				}
				else {
					set_error('error_email_sending');
					return 0;
				}
			}
			set_error('error_activation_process');
            return 0;
		}
        //$this->load->view('activation_sent', $data);
	}
	
	function auth_form(){
		$this->load->view('login');
	}
	
	function authorize($login, $password, $rem_me = 0) {
		$this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('users',$this->_lng);

		$this->load->language('form_validation',$this->_lng);

		$this->load->library('form_validation');
		$this->load->config('form_validation');
		$cfg = $this->config->item('users/authorize');
		$this->form_validation->set_rules($cfg);

		if ($this->form_validation->run() == TRUE) {
			$this->load->model('user_mdl','user');
			$auth_user = $this->user->authorize($login, $password);

			if ($auth_user) {
                error_reporting(0);
				//if user checked 'remember me' we use function, that sets cookie lifetime to 100 days
				if ($rem_me == 1)
					 $this->db_session->remember_me($login, $password, 8640000);
                else $this->db_session->remember_me($login, $password, 86400); 
				$this->db_session->set_userdata('user_id',$auth_user->user_id);
				$this->db_session->set_userdata('user_login',$auth_user->user_login);
				$this->db_session->set_userdata('user_group',$auth_user->user_group);
				@$this->db_session->set_userdata('user_lang',$auth_user->language);

				$t = $auth_user->user_age;
				$n = date('Y-m-d');
				$age = $n - $t;
				if ($age>=18) {$erotic = 1;} else {$erotic = -1;}
				$this->db_session->set_userdata('erotic_allow', $erotic);
       			    		
	       		$this->load->config('users_config');
				$auth_success_path = $this->config->item('auth_success_path');

				$data = "{'status' : '1', 'auth_success_path':'".$auth_success_path."' }";

                error_reporting(E_ALL);
			} else {
				$data = "{'status' : '0', 'login_err':'', 'password_err':'' }";
			}
		} else {
			$data = "{'status' : '-1', 
						  'login_err':'".$this->form_validation->error('login','<span>','</span>')."',
						  'password_err':'".$this->form_validation->error('password','<span>','</span>')."'
						  }";
		}
		return $data;
	}

	function authorize_from_cookie($login, $password)
	{
		$this->load->model('user_mdl','user');
		$auth_user = $this->user->authorize_from_cookie($login, $password, 1);
		if ($auth_user) 
		{
			$this->db_session->set_userdata('user_id',$auth_user->user_id);
			$this->db_session->set_userdata('user_login',$auth_user->user_login);
			$this->db_session->set_userdata('user_group',$auth_user->user_group);
			$this->db_session->set_userdata('user_lang',$auth_user->language);

			$t = $auth_user->user_age;
			$n = date('Y-m-d');
			$age = $n - $t;
			if ($age>=18) {$erotic = 1;} else {$erotic = -1;}
				$this->db_session->set_userdata('erotic_allow', $erotic);
				
			return TRUE;
		}
		else
			return FALSE;
	}

	function logout() {
		if ($this->db_session) {
			$_user = $this->db_session->userdata('user_id');
			if ($_user != false) {
				$this->db_session->unset_userdata('user_id');
				$this->db_session->unset_userdata('user_login');
			}

			$view_album = $this->db_session->userdata('view_album');
			$view_photo = $this->db_session->userdata('view_photo');
			if($view_album != false) {
				$this->db_session->unset_userdata('view_album');
			}
			if($view_photo != false) {
				$this->db_session->unset_userdata('view_photo');
			}
			$user_group = $this->db_session->userdata('user_group');
			if($user_group != false) {
				$this->db_session->unset_userdata('user_group');
			}
			
			$this->db_session->del_auth_cookies();
		}
		else {
			$this->load->view('login');
		}
	
	}

	function index(){}
	
	function profile_get($user_id=null) {
		$user_id = empty($user_id) ? $this->user_id : $user_id;
		$this->load->model('user_mdl','user');
		//$this->load->model('residence_mdl','residence');
		return $this->user->get_user($user_id);
	}

	function profile_view($user_id=null)
    {
    	$user_id = empty($user_id) ? $this->user_id : $user_id; 
		$this->load->model('residence_mdl','residence');
    	$data['user']          = $this->profile_get($user_id);
        $data['user_residence']= $this->residence->get_residence_user($user_id);
        //$data['photo_featched_file']  = $this->photo_featched_file;
	    $this->load->view('profile_view',$data);
    }

	function get_user_avatar($user_id) {
		$this->load->model('user_mdl','user');
		$this->load->config('users_config');
		return $this->user->get_avatar_src($user_id);
	}
	
    function get_user_city_place($user_id)
    {
       if (empty($user_id)) return array();
       $this->load->model('user_mdl', 'user');
       $this->load->model('residence_mdl', 'residence');
       $this->load->config('users_config');
       $user = $this->user->get_user($this->user_id);
       $data['user'] = $user;
       $data['countries'] = $this->residence->get_countries();
       $data['regions'] = $this->residence->get_regions($user->country_id);
       $data['cities'] = $this->residence->get_cities($user->region_id);
       return $data;
    }
    function profile_edit($wellcom=false,$user_var_id=0)
    {
        $data['wellcom']=$wellcom;
        if (!empty($user_var_id) ) 
        {
            $this->user_id=$user_var_id;
           	$this->db_session->set_userdata('user_id',$this->user_id);
			//$this->db_session->set_userdata('user_login',$auth_user->user_login);
            $this->_lng = $this->db_session->userdata('user_lang');
		    $this->load->language('users',$this->_lng);
        } 
        else 
        {
            $this->user_id=$this->db_session->userdata('user_id');
        }
        $this->load->model('user_mdl', 'user');
        $this->load->model('residence_mdl', 'residence');

        $this->load->config('users_config');

        $user = $this->user->get_user($this->user_id);

        $user->birth_year 	= strtok($user->birthdate, '-');
        $user->birth_month 	= strtok('-');
        $user->birth_day 	= strtok('-');

        $user->avatar_src = $this->user->get_avatar_src($this->user_id);

        $data['user'] = $user;
        $data['countries'] = $this->residence->get_countries();
        $data['regions'] = $this->residence->get_regions($user->country_id);
        $data['cities'] = $this->residence->get_cities($user->region_id);

        $data['save_link'] 					= url('profile_edit_url')."/".$this->user_id;//"javascript:ajax_save_user_attribute('user_form_attribute_id')";
        $data['avatar_src'] 				= $user->avatar_src;
        $data['avatar_personal_form'] 		= array ('id'=>'personal_form_id');
        $data['avatar_predef_form'] 		= array ('id'=>'predef_form_id');
        $data['avatar_personal_save_url'] 	= "javascript:ajax_save_avatar('".$data['avatar_personal_form']['id']."','div_avatar_personal_message_id');";
        $data['avatar_predef_save_url'] 	= "javascript:ajax_save_avatar('".$data['avatar_predef_form']['id']."','div_avatar_predef_message_id');";
        $this->load->helper( array ('form', 'url'));
    	$data['errors'] = $this->db_session->flashdata('errors');
        $this->load->view('profile_edit_new', $data);
  }
    
	function profile_save() {
		$user_id = $this->user_id;
		if ($user_id != $this->input->post('user_id')) return FALSE;
		//---------	set_error($this->upload->display_errors());
		///////////////////////////////////////////
		$psw_new     = $this->input->post('new_psw');
		$psw_confirm = $this->input->post('confirm_psw');
        $psw_cur     = $this->input->post('cur_psw');
		if (! empty($psw_new )) {
		    $this->load->model('user_mdl', 'user');
 		    $psw_cur_base = $this->user->get_user_psw($user_id);
            $psw_cur      = md5($psw_cur);
            $login = $this->db_session->userdata('user_login');
			$this->profile_change_psw($login,$psw_new,$psw_confirm,$psw_cur,$psw_cur_base);
			if (isset_errors())
				return;
		}
		
        $comment_can=$this->input->post('comment_can');
        if (empty($comment_can) == true) {
            $comment_can=0;
        } else {
            $comment_can=1;
        }
		$data = array(
			'user_id'    => $user_id,
			'about'      => $this->input->post('about'),
			'birthdate'  => $this->input->post('birth_year').'-'.$this->input->post('birth_month').'-'.$this->input->post('birth_day'),
			'country_id' => $this->input->post('country'),
			'region_id'  => $this->input->post('region'),
			'city_id'    => $this->input->post('city'),
			'interests'  => $this->input->post('interests'),
           	'comment_can' => $comment_can
		);
		

		$t = $this->input->post('birth_year').'-'.$this->input->post('birth_month').'-'.$this->input->post('birth_day');
		$n = date('Y-m-d');
		$age = $n - $t;
		if ($age>=18) {$erotic = 1;} else {$erotic = -1;}
			$this->db_session->set_userdata('erotic_allow', $erotic);
				
		$this->load->model('user_mdl','user');
		return $this->user->update($data);
	}

	function profile_change_psw($login,$psw_new,$psw_confirm,$cur_psw,$psw_cur_base) {
		if ($psw_new != $psw_confirm) {
			set_error(lang('js_label_PwsConfDifferent'));
			return false;
		}

        if ($psw_cur_base != $cur_psw) {
            set_error(lang('js_label_PwsConfDifferent_cur'));
			return false;
        }
		$data_out=lang("change_psw_good");
		//show_error('profile_change_psw'.$psw_new.' confirm='.$psw_confirm);
		//---------- Validate psw------------------------
		$this->_lng = $this->db_session->userdata('user_lang');		
		$this->load->language('form_validation',$this->_lng);

		$this->load->library('form_validation');
		$config = $this->load->config('form_validation');
		$cfg    = $this->config->item('users/profile_password');
		$this->form_validation->set_rules($cfg);
		if ($this->form_validation->run() == FALSE) {
			$data_out=$this->form_validation->error_string();
			set_error($data_out);
			return $data_out;
		}
		/* */
		$data['user_id']=$this->user_id;
		$data['password']=md5($psw_new);
		$this->load->model('user_mdl','user');

		$error_user=$this->user->save_userdata($data);
		if (! $error_user) {
			$data_out=$error_user;
			set_error($data_out);
		}
        $this->db_session->set_auth_cookies($login, $psw_new, 8640000);
		return $data_out;
	}

	function ajax_actions()
    {
		$action = $this->input->post('action');
      	
		$this->load->model('residence_mdl','residence');

		$data = '';
        $selected="";
		switch ($action ) {
			case "authorize":
				$login = $this->input->post('login');
				$password = $this->input->post('password');
				$rem_me = $this->input->post('rem_me');
				$data = $this->authorize($login, $password, $rem_me);
				break;
			case "register":
				$login = $this->input->post('username');
				$email = $this->input->post('email');
				$data = $this->register_without_activation($login, $email);
				break;
			case "remember":
				$email = $this->input->post('email');
				$data = $this->remember($email);
				break;
			//new registration form with birthdate and some user info. Password required. No mail info
			case "register_full":
				$login = $this->input->post('username');
				$email = $this->input->post('email');
                $pass1 = $this->input->post('pass1');
                $pass2 = $this->input->post('pass2');
                $birthdate  =  $this->input->post('birth_year').'-'.$this->input->post('birth_month').'-'.$this->input->post('birth_day');	
                $country_id = $this->input->post('country_id');
                $region_id    = $this->input->post('region_id');
				$city_id    = $this->input->post('city_id');				
				$userinfo  = $this->input->post('userinfo');
                $data =  $this->register_full($login, $email, $pass1, $pass2, $birthdate, $country_id, $region_id, $city_id, $userinfo);
				break;
				
			case "get_regions":
				$country_id = $this->input->post('country_id');
               	$region_id  = $this->input->post('region_id');
				$regions    = $this->residence->get_regions($country_id);

				$data .= '<select  class="s3" name="region" id="region_list" maxlength="30" onchange="javascript:ajax_fill_city(this.value);">
								<option value=0>'.lang('choose_region').'</option>';
				if (is_array($regions)) {
					foreach ($regions as $region) {
                        if ((! empty ($region_id)) && ($region->id == $region_id)) {
                             $selected="selected";
					    } else {
                             $selected="";
					    }
						$data .= "<option ".$selected." value=".$region->id.">".$region->region."</option>";
					}
				}
				$data .= '</select>';
				break;
			
			case "get_regions_list":
				$country_id = $this->input->post('country_id');

               	$data = array();
               	$options_str = "";
               	
               	if(!empty($country_id)) {
           			$regions = $this->residence->get_regions($country_id);
           			
           			if(!empty($regions)) {
           				
           				$options_str .= '<option value="0">'.lang('chouse_region').'</option>';
           				foreach ($regions as $region) {
           					$options_str .= "<option value=".$region->id.">".$region->region."</option>";
           				}
           			}
               	}
               	$data = $options_str;
               	
				break;
				
			case "get_cities_list":
				$region_id = $this->input->post('region_id');

               	$data = array();
               	$options_str = "";
               	
               	if(!empty($region_id)) {
               		$cities = $this->residence->get_cities($region_id);
           			if(!empty($cities)) {
           				$options_str .= '<option value="0">'.lang('chouse_cities').'</option>';
           				foreach ($cities as $city) {
           					$options_str .= "<option value=".$city->id.">".$city->city."</option>";
           				}
           			}
               	}
               	$data = $options_str;
               	
				break;
				
			case "get_cities":
				$region_id = $this->input->post('region_id');
               	$city_id   = $this->input->post('city_id');

				if (!empty ($region_id) )
					$cities = $this->residence->get_cities($region_id);
				else
				{
					$country_id = $this->input->post('country_id');
					$cities = $this->residence->get_country_cities($country_id);
				}
				//class="select_area_sp"
				$data .= '<select id="city_list" class="s3" name="city"  maxlength="30" ><option value="0">'.lang('chouse_city').'</option>';

				if (is_array($cities)) {
					foreach ($cities as $city) {
					    if ((! empty ($city_id)) && ($city->id == $city_id)) {
                             $selected="selected";
					    } else {
                             $selected="";
					    }
						$data .= "<option ".$selected." value=".$city->id.">".$city->city."</option>";
					}
				}
				$data .= '</select>';
			break;
				
            case "save_city":
                $region_id = $this->input->post('region_id');
                $city_id   = $this->input->post('city_id');
                $city_name = $this->input->post('city_name');
                $state     = $this->input->post('state');
                if ($state == "add") {
                    $res = $this->residence->add_city($city_id,$city_name,$region_id);
                } else {
                    $res = $this->residence->save_city($city_id,$city_name);
                }
                if ($res == 0) {
                    $data.="{'err':'1','mes':'".lang("error_heading")."'}";
                } else {
                    $data.="{'err':'0','mes':'Сохранено!'}";
                }
            	break;
            case "del_city":
                $city_id   = $this->input->post('city_id');
                $res = $this->residence->del_city($city_id);
                if ($res == 0) {
                    $data.="{'err':'1','mes':'".lang("error_heading")."'}";
                } else {
                    $data.="{'err':'0','mes':'Удалено!'}";
                }
                break;
            case "del_region":
                $region_id   = $this->input->post('region_id');
                $res = $this->residence->del_region($region_id);
                if ($res == 0) {
                    $data.="{'err':'1','mes':'".lang("error_heading")."'}";
                } else {
                    $data.="{'err':'0','mes':'Удалено!'}";
                }
                break;
            case "save_region":
                $region_id   = $this->input->post('region_id');
                $country_id  = $this->input->post('country_id');
                $region_name = $this->input->post('region_name');
                $state     = $this->input->post('state');
                if ($state == "add") {
                    $res = $this->residence->add_region($region_id,$region_name,$country_id);
                } else {
                    $res = $this->residence->save_region($region_id,$region_name);
                }
                if ($res == 0) {
                    $data.="{'err':'1','mes':'".lang("error_heading")."'}";
                } else {
                    $data.="{'err':'0','mes':'Сохранено!'}";
                }
            	break;
			case "predefavatar":
				//$data="AVATAR";
				$avatar_choice=$this->input->post('predefavatars');
				//$avatar_choice=$avatar_choice[0];
				$avatar_choice=$this->input->post('av_predef');
				// mylogwrite("\navatar_choice=".$avatar_choice);

				$this->load->model('user_mdl','user');
				$res=$this->user->set_predef_avatar($avatar_choice);
				$a_name=$this->user->get_avatar_src($this->user_id);
				if ($res==0) {
					$mes=lang('choice_avatar_befor');
				}
				if ($res==1) {
					$mes=lang('change_avatar_good');
				}
				if ($res==2) {
					$mes=lang('change_avatar_error');
				}
				$data="{'res':".$res.",'mes':'".$mes."','a_name':'".$a_name."','type':'predef'}";
				break;
			case "personalavatar":
				$res=$this->uploadAvatar();
                if ($res != 2) {
    				$this->load->model('user_mdl','user');
	    			$res=$this->user->set_personal_avatar($this->user_id, $this->last_upload_avatar_ext);
                }
				break;
			case 'setlang':
				$lang = strtolower($this->input->post('language'));
				if (($lang != 'en') && ($lang != 'kz')) $lang = "ru";
				$this->db_session->set_userdata('user_lang', $lang);

				$user_id = $this->input->post('user_id');
				if (($user_id == $this->db_session->userdata('user_id')) && (intval($user_id) > 0)) {
						$this->load->model('user_mdl','user');
						$this->user->update(array('user_id'=>$user_id, 'language'=>$lang));
				}
				break;
			case "deletephoto":
				$photo_id = $this->input->post('photo_id');
				if ( modules::run('gallery_mod/gallery_ctr/delete_photo', $photo_id))
				//if ($this->delete_photo($photo_id))
					$data = 0; // error
				else
					$data = 1; 			
				break;
		}
		$this->output->set_output($data);
	}

	function ajax_uploads() {
        $a_name="";
        $res=$this->uploadAvatar();
        if ($res == 2) {  //---size error ???
           $mes=lang('change_avatar_error');
        } else {
        		$this->load->model('user_mdl','user');
		        $res=$this->user->set_personal_avatar($this->user_id, $this->last_upload_avatar_ext);
                $a_name=$this->user->get_avatar_src($this->user_id);
		        $mes=lang('change_avatar_process');
        		$a_name=$a_name."?".rand(5,1000);
		        if ($res) {
        			$res=1;
		        	$mes=lang('change_avatar_good');
        		} else {
		        	$res=2;
        			$mes=lang('change_avatar_error');
		        }
        }
		//$mes=lang('change_avatar_process');
		$data="{'res':".$res.",'mes':'".$mes."','a_name':'".$a_name."','type':'personal'}";
        //pr("\najax_uploads:data =".$data);
        $this->output->set_output($data);
	}

	function get_username_by_id($user_id) {
		$this->load->model('user_mdl','user');
		return $this->user->get_user($user_id)->login;
	}
    function checkFileAvatar($f) {
       $f_max_v = $this->config->item('avatar_max_v');
       $f_v =filesize($f)/1024;
       if ($f_v > $f_max_v ) {
           return 2;
       } else {
           $f_max_w = $this->config->item('avatar_max_w');
           $f_max_h = $this->config->item('avatar_max_h');
           list($w, $h, $type, $attr) = getimagesize($f);
       }
       if (($w < $f_max_w)&&($h < $f_max_h)) {
          $res=1;
       } else {
          $res= 2;
       }
       //pr("\ncheckFileAvatar w=".$w." h=".$h);
       return $res;
    }
	function uploadAvatar() {
		///////////////////////////////////////////
		//log_message('debug', 'uploadAvatar:Work');
        $this->user_id=$_REQUEST['user_id'];
		$this->load->config('users_config');
		//$this->load->library('upload');
		$this->load->library('image_lib');
		$res=2;

		if ( ! empty($_FILES['Filedata']['name'])) {
            $fileName=$_FILES['Filedata']['name'];

            $ext = substr($fileName, strrpos($fileName, '.') + 1);
           	$this->last_upload_avatar_ext = $ext;
            $user_avatar_name=$this->user_id."_ico.".$ext;
            $res = $this->checkFileAvatar($_FILES['Filedata']['tmp_name']);
            if ($res == 2) {
               return $res;//--error--
            }
            if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $this->config->item('user_avatars_dir')."/". $user_avatar_name)) {
               $uploadedfullfile=$this->config->item('user_avatars_dir')."/". $user_avatar_name;
               /*
                $config_upload['upload_path'] =  $this->config->item('user_avatars_dir');
                $config_upload['allowed_types'] = 'gif|jpg|png';
                $config_upload['max_size']   =  '100';
                $config_upload['max_width']  = '640';
                $config_upload['max_height'] = '480';
                $config_upload['file_name']  = $_FILES['Filedata']['name'];
                //$this->load->library('upload', $config_upload);
                $this->load->library('upload');

                $this->upload->initialize($config_upload);
                 	$upload_data = $this->upload->data();
                    pr("\nupload_data=".var_export($upload_data));
                    if (($upload_data['image_width'] > $config_upload['max_width'] ) && ($upload_data['image_height'] > $config_upload['max_height'])) {
                        return 2;
                    }
                    if ($upload_data['file_size'] > $config_upload['max_size'] ) {
                        return 2;
                    }
                    pr("\nupload_data['file_size']=".$upload_data['file_size']." upload_data['image_width']=".$upload_data['image_width']);
*/
                    $config['image_library'] = 'gd2';
    				$config['maintain_ratio']= TRUE;
    				$config['source_image'] = $uploadedfullfile;//$upload_data['full_path'];
    				$config['width']  = $this->config->item('avatar_width');
    				$config['height'] = $this->config->item('avatar_height');
    				$this->image_lib->initialize($config);
    				$this->image_lib->resize();
                 	$res=1;
             } else {
                 $res=2;
           }
		  }

		return $res;
	}

	function get_user_avatar_path() {
		$this->load->config('users_config');
		return trim($this->config->item('rel_avatars_dir'), '/');
	}

	function get_users($page = 1, $per_page = 0, $search_criteria=array()){
		$this->load->model('user_mdl', 'db_user');
		return $this->db_user->get_all_users_list($page, $per_page, $search_criteria);
	}
	function get_user_info($user_id) {
 	    $this->load->model('user_mdl', 'db_user');
        return $this->db_user->get_user($user_id);
	}
	function get_all_users_list() {
		
		$this->db_session->keep_flashdata('user_filter');
		define("USR_PER_PAGE", 30);
			
		$this->load->model('user_mdl', 'db_user');

		if (isset($_POST['action']) ) {
			switch ($_POST['action']) {
				case 'save': $this->db_user->save_user_details ();
					break;
				case 'delete': $this->db_user->delete_user ();
					break;
				default : break;
			}
		}
		//$this->load->config('users_config');

		$this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('users_regform',$this->_lng);
		
		$cpage = intval($this->uri->segment(4));
    	if (empty($cpage))
    		$cpage = 1;

		$data['user_filter'] = $this->db_session->flashdata('user_filter');
		if (empty($data['user_filter'])) {
			$data['user_filter'] = array(
				'login'      => '',
				'user_name'  => '',
				'user_sname' => '',
				'user_email' => '',
				'date'       => array(
					'start' => '',
					'end'   => ''
				),
				'moderation' => -999
			);
		}
		
		//print_r ($data['user_filter']);
		
		$data['table_rez'] = $this->get_users($cpage, USR_PER_PAGE, $data['user_filter']);
		$cnt = $data['table_rez']['cnt'];
		$data['table_rez'] = $data['table_rez']['photos'];
		$this->load->helper('form');
		$data['moder_state'] = array( "0" => "new", "1" => "approved", "2" => "featured", "-1" => "declined" );
		
		// Show pagination
		$data['paginate_args'] = array(
    				'total_rows' => $cnt,
    				'per_page' => USR_PER_PAGE,
    				'num_links' => 25,
    				'cur_page' => $cpage,
    				'uri_segment' => 3,
    				'prev_link' => '&lt;',
					'next_link' => '&gt;',
					'first_link' => '&lt;&lt;',
					'last_link' => '&gt;&gt;',
    				'base_url' => base_url() . '/admin/users'
    			);	
    	
		
		$this->load->view('all_users_list', $data);

	}

	function get_avatar_src($user_id) {
		$this->load->model('user_mdl','user');
		return  ($this->user->get_avatar_src($user_id));
	}

	function get_user_details() {
		//$this->load->config('users_config');
		$this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('users_regform',$this->_lng);

		$this->load->model('user_mdl', 'db_user');
		$this->load->helper('form');

		// load Group List
		$data['user_groups'] = $this->db_user->get_groups_list ();

		// set moderation_state List
		$data['moder_state'] = array( "0" => "new", "1" => "approved", "2" => "featured", "-1" => "declined" );

		if ( $_POST['action'] == 'edit' ) {
			$curr_id = $_POST['cid'][0];
			$data['user_info_'] = $this->db_user->get_user ($curr_id);
		}

		$this->load->view('user_details', $data);
	}

	function get_age($user_id) {
		if (empty ($user_id)) {
			return -1;
		} else {
			$this->load->model('user_mdl','user');
			return $this->user->get_age($user_id);
		}
	}

	function get_name_by_id($user_id) {
		if (empty ($user_id)) {
			return "no name";
		} else {
			$this->load->model('user_mdl','user');
			return $this->user->get_name($user_id);
		}
	}
	
	function get_user_email($photo_id) {
		if (empty ($photo_id)) {
			return false;
		} else {
			$this->load->model('user_mdl','user');
			return $this->user->get_user_email($photo_id);
		}
	}
	
	function get_user_email_album($album_id) {
		if (empty ($album_id)) {
			return false;
		} else {
			$this->load->model('user_mdl','user');
			return $this->user->get_user_email_album($album_id);
		}
	}
	//get list of countries and cities to fill the regform 
    function fill_reg_form() {

    $this->load->model('residence_mdl', 'residence');
    $data['countries'] = $this->residence->get_countries();
    if ((isset ($user))&& (!empty ($user)))
    {
    	$data['regions'] = $this->residence->get_regions($user->country_id);
    	$data['cities'] = $this->residence->get_cities($user->region_id);
    }
    return $data;
  }

	function register_without_activation($login, $email) {
		
		$this->load->model('user_mdl','user');	
		$this->_lng = $this->db_session->userdata('user_lang');
		$this->lang->load('phh',$this->_lng);			
		$this->load->language('form_validation',$this->_lng);
		$this->load->library('form_validation');
		$config = $this->load->config('form_validation');
		
		$cfg = $this->config->item('users/register_step2');
		
	    $this->form_validation->set_rules($cfg);
            
		if ($this->form_validation->run() == TRUE)
		{						
			$pass1 = $this->user->create_random_password();	
			$group_id = $this->user->get_group_id_by_name('reg_users');
    		$lng = empty($this->_lng)?$this->config->item('language'):$this->_lng;
       		$user_id = $this->user->registration ($login, $email, $pass1, $lng, $group_id, null, null, null, null);
        
			if(!empty($user_id))
			{			
				$this->load->library('khacl');
				$this->khacl->aro->create($login,'Registered');
				
				$this->lang->load('email_registration_success',$this->_lng);
				
				$subject = lang('reg_success_subject');
				$message = lang('reg_success_message');
				
				$message = str_replace("%login%",$login,$message);
				$message = str_replace("%password%",$pass1,$message);
				
				if ($this->send_email($email, $subject, $message))
				{
					$data = "{'status' : '1'}";
					$this->db_session->set_userdata('user_id', $user_id);
					$this->db_session->set_userdata('user_login',$login);
					$this->db_session->set_userdata('user_group',$group_id);
					$this->db_session->set_userdata('user_lang',$lng);
					
					$this->db_session->set_userdata('erotic_allow', -1);
				
				}
				else 
				{
					$this->db->delete('users', array('user_id' => $user_id));
					$data = "{'status' : '-1', 'email_err': '',  'login_err':'".lang('error_mailing')."'}";					
				}				
			}
			else
			{
                $data = "{'status' : '-1', 
					  'login_err':'".lang('error_data_saving')."',
					  'email_err':''}";
			}			
		}
		else 
		{
			$data = "{'status' : '-1', 
					  'login_err':'".$this->form_validation->error('username','<span>','</span>')."',
					  'email_err':'".$this->form_validation->error('email','<span>','</span>')."'
					  }";
		}
		return $data;
	}	
}

/* end of file */