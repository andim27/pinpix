<?php
class Tags_ctr extends Controller {
	
	var $_lng = '';
	var $_maps = array();
	var $_list_maps = array();
		
    function Tags_ctr()
    {
         parent::Controller();
		 
         $this->_lng = $this->db_session->userdata('user_lang');
         $this->load->language('tags',$this->_lng);
         
         $this->load->config('tag_maps');
         $this->_maps = $this->config->item('maps');
         $this->_list_maps = $this->config->item('list_maps');
         
         $this->load->model('tag_mdl','tag');
         
         $this->tag->set_maps($this->_maps,$this->_list_maps);
    }
    
    function get_tags_input_block($object_type=null, $object_id=null)
    {
    	$tags_value = ''; 
    	if ( ! empty($object_type) && ! empty($object_id)) 
    	{
    		$limit=0;
    		$moderation_state=array(); 
    		$moderation_state_negative=FALSE;
    		
    		$tags = $this->tag->get_object_tags($object_type, $object_id, $limit, $moderation_state, $moderation_state_negative);
    		if ( $tags )   
    		{ 
				foreach ( $tags as $tag )
				{
					if( ! empty($tags_value)) $tags_value .= ", ";
    				$tags_value .= $tag->tag;
				}
    		}
    	}
    	$config = $this->load->config('tag_maps');
    	$tags_input_block = $this->config->item('tags_input_block');
    	if( ! empty($tags_value)) $tags_input_block = str_replace('value=""','value="'.$tags_value.'"',$tags_input_block);
    	return $tags_input_block;
    }
    
	function _clean_tags($tags)
    {
        //$tags = '@@qWa RR,yuuu   @#+  123 - vv   ,   KK    sd%%dd#$';
		//echo $tags.'<br/>';
		$search = array("'[^-,[:alnum:]\s]'","'\s{2,}'","'\s*,\s*'");
		$replace = array(""," ",","); 
		$tags = preg_replace($search, $replace ,strtolower($tags));
		//echo $tags.'<br/>';
		$tags = explode(',',trim($tags,','));
		//print_r($tags);
    	return $tags;
    }
	
	function save_object_tags($object_type, $object_id, $tags)
    {
		$res = $this->tag->save($object_type, $object_id, $this->_clean_tags($tags));
		if ( ! $res) {
			set_error('error_data_saving');
			return FALSE;
		}
		return TRUE;
    }
    
	function get_object_tags($object_type, $object_id, $limit=0, $moderation_state=array(), $moderation_state_negative=FALSE)
    {
         return $this->tag->get_object_tags($object_type, $object_id, $limit, $moderation_state, $moderation_state_negative);     
    }
    
	function view_object_tags($object_type, $object_id, $limit=0, $moderation_state=array(), $moderation_state_negative=FALSE)
    {
         $data['tags'] = $this->get_object_tags($object_type, $object_id, $limit, $moderation_state, $moderation_state_negative);
         $this->load->view('tags_cloud',$data);
    }
    
	function get_object_list_tags($list_id, $limit=10, $moderation_state=array(), $moderation_state_negative=FALSE)
    {
        return $this->tag->get_object_list_tags($list_id, $limit, $moderation_state, $moderation_state_negative);
    }
    
	function view_object_list_tags($list_id, $limit=10, $moderation_state=array(), $moderation_state_negative=FALSE)
    {	
         $data['tags'] = $this->get_object_list_tags($list_id, $limit, $moderation_state, $moderation_state_negative);
         $this->load->view('tags_cloud',$data);
    }
    
	function ajax_actions()
    {
		$action = $this->input->post('action');
      	
		$page = $this->db_session->userdata('tags_page');
    	$per_page = $this->db_session->userdata('tags_per_page');
		
		$data = '';
		//log_message('debug','AJAX Actions # action =  '.$action);
		switch ($action ) {
			case "update_moderation_state":
				$tag_id = $this->input->post("tag_id");
				$change_state = $this->input->post("change_state");
				$filter = $this->input->post("filter");
				
				$this->update_moderation_state($tag_id,$change_state);
				$data = $this->view_tag_list($filter, $page, $per_page);
				break;
			case "filter_tags":
				$filter = $this->input->post("filter");
				
				$page = 1;
				$data = $this->view_tag_list($filter, $page, $per_page);
				break;	
			case "delete_tag":
				$tag_id = $this->input->post("tag_id");
				$filter = $this->input->post("filter");
				
				$data = $this->tag->delete_tag($tag_id);
				$data = $this->view_tag_list($filter, $page, $per_page);
				break;	
			case "search_tag":
				$search_string = $this->input->post("text_search",TRUE);
				
				$filter = '%';
				$page = 1;
				$data = $this->view_tag_list($filter, $page, $per_page, $search_string);
				break;		
		}

		//log_message('debug','AJAX Actions Response = '.$data);
		if ($data) $this->output->set_output($data);
    }
    
    /*
    * This function, required to get the list of all tags.
    */
    function view_tags_moderation($filter='%', $page=1, $per_page=0)
    {	
    	$this->db_session->set_userdata('tags_page',$page);
    	$this->db_session->set_userdata('tags_per_page',$per_page);
    	
    	$sess_filter = $this->db_session->userdata('tags_filter');
    	$sess_search_string = $this->db_session->userdata('tags_search_string');
    	
    	$filter = empty($sess_filter) ? $filter : $sess_filter;
		    	
    	if (empty($sess_search_string)) 
    	{
    		$tag_list = $this->get_tag_list($filter, $page, $per_page);
    	}
    	else 
    	{
    		$tag_list = $this->tag->search_tag($sess_search_string, $page, $per_page);
    	}
    	
    	$data['list'] = $tag_list;
    	
    	$this->load->helper('BI_moderation');
    	$data['states'] = get_moderation_state_list();
    	
    	$data['mod_filter'] = $filter;
    	$data['search_string'] = $sess_search_string;
    	
    	$data['paginate_args'] = array(
			'total_rows' => $this->tag->found_rows(), 
			'per_page' => $per_page
			);
    	
    	$this->load->view('tags_moderation',$data);
    }
    
	function get_tag_list($filter='%', $page=1, $per_page=0)
    {	
    	return $this->tag->get_tags($filter, $page, $per_page);
    }
    
	function view_tag_list($filter='%', $page=1, $per_page=0, $search_string='')
    {	
    	$this->db_session->set_userdata('tags_filter',$filter);
    	$this->db_session->set_userdata('tags_search_string',$search_string);
		
    	if (empty($search_string)) 
    	{
    		$tag_list = $this->get_tag_list($filter, $page, $per_page);
    	}
    	else 
    	{
    		$tag_list = $this->tag->search_tag($search_string, $page, $per_page);
    	}
    	
    	$data['list'] = $tag_list;
    	
    	$this->load->helper('BI_moderation');
    	$data['states'] = get_moderation_state_list();
    	
    	$data['mod_filter'] = $filter;
    	
    	$data['paginate_args'] = array(
			'total_rows' => $this->tag->found_rows(), 
			'per_page' => $per_page,
			'base_url' => url('admin_tags_url'),
			'cur_page' => $page
			);
    	
    	$this->load->view('tag_list', $data);
    }
    
    /*
    * This function updates moderation state.
    */
    function update_moderation_state($tag_id,$change_state)
    {
    	$this->load->helper('BI_moderation');
		
		$object_table = 'tags';
		$object_id_field = 'tag_id';
		
		return set_moderation_state($object_table, $object_id_field, $tag_id, $change_state);
    }
    
}
/* end of file */