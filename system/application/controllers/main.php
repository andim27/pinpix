<?php
class Main extends Controller {

	private $per_page = 20;
	private $page = 1;
	private $num_links = 15;
	
	function Main()
	{
		parent::Controller();
		$lng = $this->db_session->userdata('user_lang');

		$this->lang->load('phh',$lng);
		$this->rating->ratingControl();        
	}

	function index()
	{
		$this->view();
	}

	function photos_sort_order(){
		
		$sort_order = $this->uri->segment(3);
		$sort_as = $this->uri->segment(4);
		$page_number = $this->uri->segment(6);
		
		$sort_array = array(
			'date' => array('sort_by' => false, 'sort_as' => 'desc'),
			'title' => array('sort_by' => false, 'sort_as' => 'asc'),
			'popular' => array('sort_by' => false, 'sort_as' => 'desc')
		);
		
		$order_by = "";
		$_sort_order = 'date';
		$_sort_as = 'asc';
		
		if(!empty($sort_order)) {
			if($sort_order == 'date') {
				$order_by = 'photos.date_added';
				$_sort_order = 'date';				
				
				if(!empty($sort_as)) {
					if($sort_as == 'asc'){
						$sort_array['date']['sort_by'] = true;
						$sort_array['date']['sort_as'] = 'desc';
						$_sort_as = 'asc';
					}
					elseif ($sort_as == 'desc') {
						$sort_array['date']['sort_by'] = true;
						$sort_array['date']['sort_as'] = 'asc';
						$_sort_as = 'desc';
					}
				}
				
			} elseif ($sort_order == 'title') {
				$order_by = 'photos.title';
				$_sort_order = 'title';
				
				if(!empty($sort_as)) {
					if($sort_as == 'asc'){
						$sort_array['title']['sort_by'] = true;
						$sort_array['title']['sort_as'] = 'desc';
						$_sort_as = 'asc';
					}
					elseif ($sort_as == 'desc') {
						$sort_array['title']['sort_by'] = true;
						$sort_array['title']['sort_as'] = 'asc';
						$_sort_as = 'desc';
					}
				}
				
			} elseif ($sort_order == 'popular') {
				$order_by = 'see_cnt';
				$_sort_order = 'popular';
				
				if(!empty($sort_as)) {
					if($sort_as == 'asc'){
						$sort_array['popular']['sort_by'] = true;
						$sort_array['popular']['sort_as'] = 'desc';
						$_sort_as = 'asc';
					}
					elseif ($sort_as == 'desc') {
						$sort_array['popular']['sort_by'] = true;
						$sort_array['popular']['sort_as'] = 'asc';
						$_sort_as = 'desc';
					}
				}
			}
			if(!empty($sort_as) && !empty($order_by)) {
				if($sort_as == 'asc' || $sort_as == 'desc') {
					$order_by .= ' '.$sort_as;
				}
			}
		} else {
			$order_by = 'photos.date_added desc';
			$sort_array['date']['sort_by'] = true;
			$sort_array['date']['sort_as'] = 'asc';
			$_sort_order = 'date';
			$_sort_as = 'desc';
		}
		
		$sort_array['order_by'] = $order_by;
		$sort_array['sort_order'] = $_sort_order;
		$sort_array['sort_as'] = $_sort_as;
		
		return $sort_array;
	}
	
	function view($sort_type = '', $sort_order='')
	{
		$this->config->load('ads');
		$cfg = $this->config->item('main');
		
		$registered = $this->db_session->userdata('user_id');
		if (empty($registered)) $registered = 0;
		if  ($registered == 0)
			$erotic = -1;
		else  {
			$erotic = $this->db_session->userdata('erotic_allow');
			if (!$erotic)
				$erotic = modules::run('users_mod/users_ctr/get_age', $registered);
		}
		
		$page_number = $this->uri->segment(6);
		$this->page = empty($page_number) ? 1 : $page_number;
		if($this->page >= 1 && $this->page <= 5) $this->num_links = 25;
		elseif($this->page > 5 && $this->page <= 10) $this->num_links = 19;
		elseif($this->page > 10 && $this->page <= 14) $this->num_links = 15;
		elseif($this->page > 14 && $this->page <= 25) $this->num_links = 13;
		elseif ($this->page > 25 && $this->page <= 94) $this->num_links = 12;
		elseif ($this->page > 94 && $this->page <= 102) $this->num_links = 11;
		elseif ($this->page > 102) $this->num_links = 10;
		
		$sort_array = $this->photos_sort_order();
		$order_by = $sort_array['order_by'];
		
		$photos = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, null, MODERATION_STATE, $registered, $erotic, $this->per_page, $this->page, true, $order_by);
		$count = $photos['count'];
		unset($photos['count']);
		
		$data = array();
		
		$data['flashbox_block'] = true;
		$data['sort_array'] = $sort_array;
		$data['register_block'] = modules::run('users_mod/users_ctr/register');		
		$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);       
		$data['photos'] = modules::run('gallery_mod/gallery_ctr/small_img_prepare', $photos);
		$data['paginate_args'] = array(
			'total_rows'  => $count,
			'per_page'    => $this->per_page,
			'cur_page'    => $this->page,
			'num_links' => $this->num_links,
			'base_url'    => base_url().'main/view/'.$sort_array['sort_order'].'/'.$sort_array['sort_as'].'/page/',
			'uri_segment' => 4
		);
        
		$this->load->view('_main_new',$data);
	}
	
	function fl()
	{
		$this->fl->set_slide_list();
	}

	function logout()
	{
		modules::run('users_mod/users_ctr/logout');
		redirect('', 'location');
	}
	
	//special function for bibb in order to redirect to bibb main page when user logs out.
	function logout_3()
	{
		modules::run('users_mod/users_ctr/logout');
		redirect(base_url().'bibb', 'location');
	}
}

/* end of file */
