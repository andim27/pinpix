<?php
class Up extends Controller {

	//this is just test controller for upload the photo

	function Up() {
		parent::Controller();

		$lng = $this->db_session->userdata('user_lang');
		$this->lang->load('phh',$lng);
	}
    
	function index()
	{
		$this->view();
	}
	
	function view()
	{
			$this->load->view('up');
	}
	}
?>