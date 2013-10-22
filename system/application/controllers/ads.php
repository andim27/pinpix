<?php
class Ads extends Controller {

	function Ads() {
		parent::Controller();
		$lng = $this->db_session->userdata('user_lang');
		$this->lang->load('phh',$lng);
	}

	function index(){
		$this->view();
	}

	function view($ads_block_ids = null)
	{
		$data['ads_content'] = modules::run('ads_mod/ads_ctr/get_ads_content', $ads_block_ids);
		$this->load->view('ads', $data);				
	}
	
}

/* end of file */