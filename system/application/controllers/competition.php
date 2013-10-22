<?php
	/**
	 * Class Competition
	 *
	 * competition controller class
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Competition.class.php
	 * @created  Thu Feb 05 16:07:05 EET 2009
	 */
	class Competition extends Controller {
		
		private $comp_per_page = 3;
		private $comp_page = 1;
		private $photo_per_page = 12;
		private $photo_page = 1;
		
		function __construct() {
			
			parent::Controller();
		 
	        $lng = $this->db_session->userdata('user_lang');	         
	        $this->lang->load('phh',$lng);
		}

		function index(){
			$this->view();
		}
		
		function view(){
			$filter = $this->uri->segment(3);			
//			if(empty($filter) || ($filter != "open" && $filter != "close")) $filter = "open";
			$page = intval($this->uri->segment(5));
			
			if(!empty($page)) $this->comp_page = $page;
		
			$concurs_mod_state = 1;
			
			$this->config->load('ads');
			$cfg = $this->config->item('competition');
			$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);				
			$data['competition_blok'] = modules::run('competition_mod/competition_ctr/view_competition', null, $this->comp_per_page, $this->comp_page, $filter, true, $concurs_mod_state);

			$this->load->view('_competition', $data);
		}
		
		function details(){
			$data = array();
			
			$this->config->load('ads');
			$cfg = $this->config->item('competition');	
								
			$competition_id = intval($this->uri->segment(3));
			$filter = $this->uri->segment(4);
//			if(empty($filter) || ($filter != "all" && $filter != "winners")) $filter = "all";
			$page = intval($this->uri->segment(6));
			
			if(!empty($page)) $this->photo_page = $page;
			
			$concurs_mod_state = 1;
						
			$data['competition_works'] = modules::run('competition_mod/competition_ctr/view_competition_works', $competition_id, $this->photo_per_page, $this->photo_page, $filter, true, $concurs_mod_state);
			$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);
			$this->load->view('competition_works', $data);
		}
			
		
		function add() {
			
			$title = $this->input->post('title');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$description = $this->input->post('description');
			$comp_id = $this->input->post('comp_id');
			
			$user_group = @$this->db_session->userdata('user_group');
			
			$log_data = array(
				'post_data: ' => $_POST,
				'user_group' => $user_group
			);
			log_message('error', '$log_data: '.var_export($log_data, true));
			
			if (($user_group !== FALSE) && (($user_group == 'admins') || $user_group == 'moderators')) {
				if (empty($start_date) || empty($end_date) || empty($description) || empty($title)) {
					show_404(lang('page_not_found'));
				} else {
					$data = array(
						'title'             => $title,
						'start_date'        => date("Y-m-d i:s", strtotime($start_date)),
						'end_date'          => date("Y-m-d i:s", strtotime($end_date)),
						'description'       => $description,
						'short_description' => ((strlen($description) > 255) ? substr($description, 0, 252)."...": $description)
					);
					if (empty($comp_id))
						modules::run('competition_mod/competition_ctr/simple_add', $data);
					else
						modules::run('competition_mod/competition_ctr/simple_update', $comp_id, $data);			
					
					redirect(url('admin_comps_url'));
				}
			} else {
				show_404(lang('page_not_found'));
			}
		}
		
		function __destruct() {}
	}
?>