<?php

class Ads_mdl extends Model {

	function get_all_banners_list()
	{
		$query = $this->db->get('banners');
		if ($query->num_rows() > 0) 
			return $query->result();
	}
	
	function delete_banner() 
	{
		$id = $this->input->post('cid');
		if ( count($id) == 1 ) 
		{
			$this->db->where('id', $id[0]);			
			$query = $this->db->get('banners');
			if ($query->num_rows() > 0) 
			{
				$rs = $query->result();
				if ($rs[0]->file_type!='string')
					if (file_exists ($rs[0]->file_url))
						unlink($rs[0]->file_url);				
			}	
			$this->db->delete('banners', array('id' => $id[0]));			
		} 
		else 
		{
			foreach ($id as $value ) 
			{
				$this->db->where('id', $value);			
				$query = $this->db->get('banners');
				if ($query->num_rows() > 0) 
				{
					$rs = $query->result();
					if ($rs[0]->file_type!='string')
						if (file_exists ($rs[0]->file_url))
							unlink($rs[0]->file_url);				
				}	
				$this->db->delete('banners', array('id' => $value));
		
			}
		}
	}
	
	function get_banner($ban_id) 
	{
		$this->db->where('id', $ban_id);			
		$query = $this->db->get('banners');
		if ($query->num_rows() > 0) 
		{
			$rs = $query->result();
			return $rs[0];
		}
		else 
			return false;		
	}
	
	function get_divs_banner($div_id) 
	{
		$this->db->where('block_id', $div_id);		
		$this->db->where('active_state', 1);	
		$query = $this->db->get('banners');
		if ($query->num_rows() > 0) 
		{
			$rs = $query->result();
			$r = rand(0, $query->num_rows()-1);
			return $rs[$r];
		}
		else 
			return false;		
	}
	
	function get_count_all_banners() {
		return $this->db->count_all('banners');
	}
	
	function parse_ads_form() {
		$data = array();
		
		$way = $this->input->post('way');
		$file_url = $this->input->post('file_url');
		$active_state = $this->input->post('active_state');
		$block_id = $this->input->post('block_id');
		$description = $this->input->post('description');
		
		if (empty($way))
		{			
			if ( !empty($_FILES['userfile']['name']) ) 
			{
				$config['upload_path'] = $this->config->item('banners_upload_dir');//dirname(BASEPATH).'/uploads/banners/';
				$config['allowed_types'] =$this->config->item('img_types'); // 'bmp|gif|jpg|png|jpeg|swf|BMP|GIF|JPG|PNG|JPEG|SWF';		
				$config['max_size']	= 100000000;//$this->config->item('file_max_size'); 
				$this->load->language('upload',$this->db_session->userdata('user_lang'));
				$this->load->library('upload', $config);
		
				if ( ! $this->upload->do_upload())
				{
					$error = array('error' => $this->upload->display_errors());		
					echo $error['error'];
					return 0;
				}	
				else
				{
					$info = array('upload_data' => $this->upload->data());
					
					$file_url = $info['upload_data']['file_name'];
					$file_type = $info['upload_data']['image_type'];
					
					if($info['upload_data']['file_ext'] == ".swf") {
						$file_url = '<object width="240" height="400" type="application/x-shockwave-flash" 
							data="'.base_url().'uploads/banners/'.$info['upload_data']['file_name'].'">
							<param value="true" name="menu">
							<param value="high" name="quality">
							<param value="transparent" name="wmode">
							</object>';
						$file_type = "string";
					}
					$data['file_url'] = $file_url;
					$data['file_type'] = $file_type;
				}
			}
			$data['onclick_url'] = $this->input->post('onclick_url');
			$data['alt_text'] = $this->input->post('alt_text');
			$data['title'] = $this->input->post('title');	
		}
		
		if ($way == 1)
		{
			if (!empty($file_url))
			{
				$data['file_url'] = $file_url;		
				$data['file_type'] = "string";	
			}			
		}
					
		if (!empty($active_state)) {
			$data['active_state'] = 1;
				
		} else {
			$data['active_state'] = 0;								
		}
		
		if (!empty($block_id)){
			$data['block_id'] = $block_id;
		}
		if (!empty($description)){
			$data['description'] = $description;
		}

		return $data;
	}
	
	function create_banner() {
		$data = $this->parse_ads_form();
		if ($data != 0)
			$this->db->insert('banners',$data);
	}

	function save_banner_details() {
		if ( isset($_POST['banner_id']) &&  $_POST['banner_id'] === "") {
			// INSERT
			$this->create_banner();
			return;
		}
		
		// UPDATE
		if ( isset($_POST['banner_id']) )
			$ban_id = $this->input->post('banner_id');

		$data = $this->parse_ads_form();
		if ($data != 0)
		{
			$this->db->where(array("id" => $ban_id));
			$this->db->update('banners',$data);
		}
	}
}

/* End of file test.php */
/* Location: ./models/test.php */