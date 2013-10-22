<?php
class Register extends Controller {
	    
    function Register()
    {
         parent::Controller();
		 
         $lng = $this->db_session->userdata('user_lang');

         $this->lang->load('phh',$lng);
         
    }
    
	function index()
	{
		$data = modules::run('users_mod/users_ctr/fill_reg_form');	
		$data['from_where'] = 'register';    
		$this->load->view('registration', $data);
	}
	
	function activate()
	{
        //$data['user_activate_block'] = modules::run('users_mod/users_ctr/activate');
		//$this->load->view('_user_activate_new', $data);
        $saved = FALSE;
        $user_id = modules::run('users_mod/users_ctr/activate');
        if (! empty($user_id)){
            $data['profile_edit_block'] = modules::run('users_mod/users_ctr/profile_edit',true,$user_id);
	    	$this->load->view('_user_profile_edit_new', $data);
        }else {
            $lng = $this->db_session->userdata('phh_lang');
            $this->lang->load('phh',$lng);
            set_error(lang("error_activation_process"));
        }
	}
	
	function step2($rlink = null)
	{
		if (empty($rlink)){
			show_404(lang('page_not_found'));		
		}
		$data['rlink'] = $rlink;
		$this->load->view('registration_step2', $data );
	}
	
	
}

/* end of file */