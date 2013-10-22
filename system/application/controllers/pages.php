<?php
class Pages extends Controller {
	public $lng = "";
	public $default_lang = "";
	public $render_data = array();

	function Pages(){
		parent::Controller();
		$this->lng = $this->db_session->userdata('user_lang');

		$this->default_lang = $this->config->item('language');
		$this->lang->load('phh',$this->lng);
 		$this->lang->load('bibb',$this->lng);

	}

	function _loadPageData($page = null){
		if (empty($page))
			 set_error(lang('page_not_found'));
		$PAGE_PATH = STATIC_PATH.'pages/';
		if (is_file($PAGE_PATH.$this->lng.'/'.$page.'.php')) {
			$this->render_data = include($PAGE_PATH.$this->lng.'/'.$page.'.php');
		} elseif (is_file($PAGE_PATH.$this->default_lang.'/'.$page.'.php')) {
			$this->render_data = include($PAGE_PATH.$this->default_lang.'/'.$page.'.php');
		} else {
			set_error(lang("page_not_found"));
		}
	}

	function index(){
		$this->view();
	}

	function view($page = null){
//		$page = $this->uri->segment(3);
		if (empty($page)) {
			set_error(lang("page_not_found"));
		} else {
			switch($page){
				case 'help': $this->_loadPageData('help');  break;
				case 'faq': $this->_loadPageData('faq');  break;
				case 'jury1': $this->_loadPageData('jury1');  break;
				case 'jury2': $this->_loadPageData('jury2');  break;
				case 'jury3': $this->_loadPageData('jury3');  break;
				case 'contacts': $this->_loadPageData('contacts');  break;
				case 'conditions': $this->_loadPageData('competition_conditions');  break;
				case 'agreement': $this->_loadPageData('agreement');  break;
				default: set_error(lang("page_not_found")); break; 
			}
		}
		if (($page == 'conditions')||($page == 'jury1'))
		{
			$this->load->view('_pages_3', $this->render_data);				
		}
		else
			$this->load->view('_pages', $this->render_data);
	}
}
/* EOF */