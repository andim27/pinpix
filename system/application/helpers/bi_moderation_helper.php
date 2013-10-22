<?php
function set_moderation_state($object_table, $object_id_field, $object_id, $mod_state)
{
	static $ci;
	$ci = &get_instance();
	$states = $ci->config->item('mod_states');	
	
	if( empty($object_table) || empty($object_id) || ! in_array($mod_state, $states)) return FALSE;

	if (!is_array($object_id)) {
		$query = "select * from ".$object_table." where id=".$object_id;
		$result = $ci->db->query($query);
		if ( ! $result) return FALSE;
		$comment = $result->row();
		if(!$comment) return FALSE;
		
		$where_condition =  " WHERE $object_id_field=".clean($object_id);
	}
	else
	{
		$count_ids = count($object_id)-1;
		
		$query = "";
		foreach ($object_id as $index=>$object_id_one){
			
			$query = "select * from ".$object_table." where id=".$object_id_one;
			$result = $ci->db->query($query);
			if ( ! $result) return FALSE;
			$comment = $result->row();			
			
			$query = "UPDATE $object_table SET comment_date = '". $comment->comment_date ."', moderation_state=".clean($mod_state). " WHERE ".$object_id_field."=".clean($object_id_one);
			$ci->db->query($query);
		}
	}

	if (!is_array($object_id)) {
		$query = "UPDATE $object_table SET comment_date = '". $comment->comment_date ."', moderation_state=".clean($mod_state).$where_condition;
		
		add_worklog_entry($object_table, $object_id, $mod_state);
		
		return $ci->db->query($query);
	}
}

function get_moderation_state_list()
{
	static $ci;
	$ci = &get_instance();
	$states = $ci->config->item('mod_states');
	$mod_lang = $ci->config->item('mod_lang');
	$ci->_lng = $ci->db_session->userdata('user_lang');
		
    $ci->load->language($mod_lang, $ci->_lng);
         
	return array(
				MOD_NEW => lang('mod_state_new'), 
				MOD_APPROVED => lang('mod_state_approved'), 
				//MOD_FEATURED => lang('mod_state_featured'), 
				MOD_DECLINED => lang('mod_state_declined')
			);	
}

function get_moderation_state_list_redused()
{
	static $ci;
	$ci = &get_instance();
	$states = $ci->config->item('mod_states');
	$mod_lang = $ci->config->item('mod_lang');
	$ci->_lng = $ci->db_session->userdata('user_lang');
		
    $ci->load->language($mod_lang, $ci->_lng);
         
	return array(
				MOD_APPROVED => lang('mod_state_approved'), 			
				MOD_DECLINED => lang('mod_state_declined')
			);	
}

function add_worklog_entry($object, $object_id, $mod_state)
{
    static $ci;
    $ci = &get_instance();
    if (is_array ($object_id))
    	$object_id = implode(",", $object_id); 
    if($ci->config->item('enable_worklog'))
    {
        $query = "INSERT INTO worklog (user_id, object_type, object_id, action_type, action_date) 
                  VALUES (
                  ".clean($ci->db_session->userdata('user_id')).",
                  ".clean($object).", 
                  ".clean($object_id).", 
                  ".clean($mod_state).", 
                  NOW())";
        $ci->db->query($query);
    }
}
?>