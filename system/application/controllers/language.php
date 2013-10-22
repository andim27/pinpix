<?php
class Language extends Controller {
	    
    function set(){
    	$lng = $this->uri->segment(3);
    	if (empty ($lng))
			redirect (base_url());
    	$this->db_session->set_userdata('user_lang',$lng);
    	
    	header("Location: ".$_SERVER['HTTP_REFERER'], TRUE, 302);
    }
    
}
?>