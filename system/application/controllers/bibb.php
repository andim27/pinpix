<?php
	/**
	 * Class Bibb
	 *
	 * competition controller class
	 *
	 * @access   public
	 * @package  Bibb.class.php
	 * @created  Thu Feb 05 16:07:05 EET 2009
	 */
	class Bibb extends Controller {

		/**
		 * Constructor of Competition
		 *
		 * @access  public
		 */
		function __construct() {
			parent::Controller();
		 
	         $lng = $this->db_session->userdata('user_lang');
	         if (empty($lng))
	         {
	         	$this->db_session->set_userdata('user_lang','ru');
	         }
	         
	         $this->lang->load('bibb',$lng);
		}

		function index() {
			$this->view();
		}

		function view($mask = 100000) {

			/*
			this way we can choose whot blocks to display
			$_cond  = 100000;
			$_enter  = 10000;
			$_reg   = 1000;		
			$_succ1 = 100;
			$_succ2 = 10;
			$_upl   = 1;
			*/
			
			//-------�������� ��������� ��� ����� ��������
			$_user_id = $this->db_session->userdata('user_id');
			if (!empty($_user_id))
			{
				$data['user'] = modules::run('users_mod/users_ctr/profile_get',$_user_id);
	
	        	if (!empty ($data['user']))
	       		{
	    			$this->config->load('../modules/gallery_mod/config/config');
	    			$data['allowed_types'] = explode('|', $this->config->item('img_types'));
	    			$data['allowed_types'] = '*.'.implode(';*.', $data['allowed_types']).';';
	    			$data['file_max_size'] = $this->config->item('file_max_size');				
	    		} 
			}
        	//-----------------
			else
				$data = modules::run('users_mod/users_ctr/fill_reg_form');
		    $data['_user_id'] = $_user_id; 
		    
		    //check for mask validation - escaping smart users
		    $num  = intval($mask);
		    $sum = 0;  $kol =0;

		    while ($num>=1)
			{
			  $sum += ($num % 10);       
			  $kol++;                  
			  $num /= 10;
			}
		
		    if ((($kol<6) && ($sum == 1 ))||($mask == 100010) ||($mask == 100001))
				$data['mask'] = $mask;	
			else
				$data['mask'] = 100000;
				
			// end check
			$data['from_where'] = 'bibb';
			$data['lng']		= $this->db_session->userdata('user_lang');
			
			$this->load->view('_competition_new', $data);
		}
		
	}
?>