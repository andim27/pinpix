<?php
class Ads_ctr extends Controller {

	private $last_upload_avatar_ext = "";
	var $_lng = '';
	var  $user_id=0;

	function Ads_ctr() {
		parent::Controller();
	
		$this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('userslang',$this->_lng);

		$data = get_app_vars();
		$this->user_id= $this->db_session->userdata('user_id');
		$this->fl->base=base_url();
		
	}

	function get_banner_details() {
		$this->load->config('ads_config');
		$this->_lng = $this->db_session->userdata('user_lang');
		$this->load->language('ads',$this->_lng);

		$this->load->model('ads_mdl', 'ads');
		$this->load->helper('form');

		//$data['max_size'] = $this->config->item('file_max_size');

		$data['ads_block_ids'] = $this->config->item('ads_block_ids'); //array( "0" => "ads_block_1", "1" => "ads_block_2", "2" => "ads_block_3");

		if ( $_POST['action'] == 'edit' ) {
			$curr_id = $_POST['cid'][0];
			$data['banner_info_'] = $this->ads->get_banner($curr_id);
		}

		$this->load->view('banner_details', $data);
		}
	
	function get_all_banners_list() 
	{
		$this->load->model('ads_mdl', 'ads');
		$this->_lng = $this->db_session->userdata('user_lang');		
		$this->load->language('ads',$this->_lng);
		$this->load->config('ads_config');
		
		if (isset($_POST['action']) ) {
			switch ($_POST['action']) {
				case 'save': $this->ads->save_banner_details();
					break;
				case 'delete': $this->ads->delete_banner ();
					break;
				default : break;
			}
		}
		
		$data['table_rez'] = $this->ads->get_all_banners_list ();
		
		$this->load->helper('form');
		$this->load->language('users_regform',$this->_lng);
		$data['moder_state'] = array( "0" => "new", "1" => "approved", "2" => "featured", "-1" => "declined" );
		
		$this->load->view('all_banners_list', $data);

		// Show pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url() . '/admin/ads';
		$config['total_rows'] = $this->ads->get_count_all_banners();
		$config['per_page'] = '20'; // Pagination count
		$this->pagination->initialize($config);
		
		echo $this->pagination->create_links();
	}

	function get_ads_content($ads_block_ids = null)
	{
		if ($ads_block_ids == 'no') return;
		
		$this->load->model('ads_mdl', 'ads');
		
		if (empty ($ads_block_ids))
		{
			$this->load->config('ads_config');		
			$this->load->language('ads',$this->_lng);
			$ads_block_ids = $this->config->item('ads_block_ids');
		}
			
		$i = 0;
		$content_top = array();
		$content_right = array();
				
		foreach ($ads_block_ids as $key => $value)
		{
			$type = split("_", $value);
			if(isset($type[2])) $type = $type[2];			
			
			$content_row = $this->ads->get_divs_banner($value);
			
			$block_str = "";			
			if ($content_row)
			{
				if ($content_row->file_type == "string") {
					$block_str = $content_row->file_url;
				}
				else {
					if (!empty ($content_row->file_url)){
						$block_str = $this->build_content($content_row);
					} else {
						$block_str = "";
					}
				}
			}
			else {
				$block_str = "";
			}
			
			if($type == 'top') $content_top[$i] = $block_str;
			elseif($type == 'right') $content_right[$i] = $block_str;
			
			$i++;
		}
		$data = array();
		$data['top']['ads_content'] = $content_top;
		$data['right']['ads_content'] = $content_right;		
		
		$content = array(
			'top_ads' => $this->load->view('top_ads', $data['top'], true),
			'right_ads' => $this->load->view('right_ads', $data['right'], true)
		);
		return $content;
		
		/*$data['ads_content'] = $content;
		$this->load->view('ads', $data);*/
	}	
	
	function build_content($row)
	{
		return  ("<a style='border:0;' onfocus='this.blur()' href = '".$row->onclick_url."' target='_blank'> <img  alt= '".$row->alt_text."' border='0' title = '".$row->title."' src= '". base_url() . 'uploads/banners/' . $row->file_url . "'> </a>");		
	}
}
/* end of file */