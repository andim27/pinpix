<?php
function url($alias='', $params=array()){
	
	$url = '';
	
	if (empty($alias)) return $url;
	
	static $ci;
    
    if (!is_object($ci)) $ci = &get_instance();
	
    $ci->load->library('Properties');
	$props = new Properties;
	
	$props->load(file_get_contents('url.properties'));
	//print_r($props); die();
	$map = $props->toArray();
	
	if (!empty($alias)) {
		if (array_key_exists($alias, $map)) {
		    $url = $map[$alias];
		} 
	}
	
	if (is_array($params)){
		foreach ($params as $key=>$value)	{
			$url = str_replace("%$key%",$value,$url);
		}
	}
	elseif (is_string($params) || is_int($params)){ 
		$url = preg_replace("(\%.*\%)",$params,$url);
	}
	
	$url = trim(preg_replace('/\[.*\]/','',$url), '/');

	return trim(site_url($url),','); //trim(base_url(),'/').$url;
}

function url_access($site_url=''){
	
	if (empty($site_url)) return TRUE;
	
	static $ci;

    if (!is_object($ci)) $ci = &get_instance();
	
    $ci->load->library('Properties');
	$props = new Properties;
	
	$props->load(file_get_contents('url.properties'));

	$map = $props->toArray();

	foreach ($map as $alias=>$url)
	{
        $url = trim(preg_replace('/\[.*\]/','',$url), '/');
		$url_base = trim(preg_replace('/\%.*\%/','',$url), '/');
		$site_url = trim($site_url, '/');

        if ($url_base!="") //no value in file url.properties
		if( (strpos($site_url,$url_base)!==FALSE) && (count($site_url)==count($url)) )
		{
        $access_group = '';
			$match_count = preg_match('/\[(.*)\]/',$map[$alias],$matches);
			if ($match_count>0) $access_group = $matches[1];

			static $ci;
			if (!is_object($ci)) $ci = &get_instance();
			$user_id = $ci->db_session->userdata('user_id');

			//echo '<br/><br/>'.$access_group.'<br/><br/>';
			if (empty($access_group)) return TRUE;
			elseif ( ! $user_id) return FALSE;
			
			//if ( ($access_group=='authorized') && ! $user_id ) return FALSE;
			
			if ( $access_group=='authorized' || $ci->db_session->userdata('user_group')==$access_group) {
				return TRUE;
	        }

	        return FALSE;
		}
	}

	return TRUE;
}

function redirect($uri = '', $method = 'location', $http_response_code = 302)
{
	if (strpos($uri,trim(base_url()))===false) $uri = site_url($uri); 
	switch($method)
	{
		case 'refresh'	: header("Refresh:0;url=".$uri);
			break;
		default			: header("Location: ".$uri, TRUE, $http_response_code);
			break;
	}
	exit;
}

function uri_segment($index){
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
	return $ci->uri->segment($index);
}

function get_app_vars(){
	$data = array();
	
	$data['site_title'] = 'pinpix.kz';
	
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
		$data['user_id'] = $ci->db_session->userdata('user_id');
	
	//echo ($ci->input->user_agent()); 

	if (empty ($data['user_id']))
		if ($ci->db_session->check_auth_cookies())
		{
			$lng = $ci->db_session->userdata('user_lang');
			$ci->lang->load('phh',$lng);
		}	
	$data['user_id'] = $ci->db_session->userdata('user_id');	
	$data['user_login'] = $ci->db_session->userdata('user_login');
	$data['user_group'] = $ci->db_session->userdata('user_group');
	
	return $data;
	
}

function clean($str){
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
	$str = $ci->input->xss_clean($str);
	$str = $ci->db->escape($str);
	return $str;
}

function paginate($args){
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
	if (empty($args['base_url']))
	{
		$url = uri_string();
	}
	else 
	{
		$url = $args['base_url'];
	}
	
	$page_part = strstr($url,'/page/'); 
	if ($page_part) $url = str_replace($page_part,'',$url);
		
	$args['base_url'] = $url.'/page/';
	
	$url = trim($url,'/');
	$url = explode('/',$url);

	$args['uri_segment'] = count($url)+2;

	$args['cur_page'] = empty($args['cur_page']) ? '-' : $args['cur_page'];

	$ci->load->library('pagination');
	$ci->pagination->initialize($args);

	return $ci->pagination->create_page_links();
}

function paginate_ajax($args){
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
	if (empty($args['base_url']))
	{
		$url = uri_string();
	}
	else 
	{
		$url = $args['base_url'];
	}
	
	$page_part = strstr($url,'/page/'); 
	if ($page_part) $url = str_replace($page_part,'',$url);
		
	$args['base_url'] = $url.'/page/';
	
	$url = trim($url,'/');
	$url = explode('/',$url);
	
	$args['uri_segment'] = count($url)+2;
	
	$args['cur_page'] = empty($args['cur_page']) ? '-' : $args['cur_page'];

	if (isset($ci->load->_ci_classes['pagination']) && ($ci->load->_ci_classes['pagination'] == 'pagination')) {
		
	} else {
		$ci->load->library('pagination');
	}
	$ci->pagination->initialize($args);
	return $ci->pagination->create_page_links_ajax();
}

function set_error($error_lang_alias='error_unknown')
{
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();

	$error_lang_alias = empty($error_lang_alias) ? 'error_unknown' : $error_lang_alias;
	$error_lang_alias = ( ! is_array($error_lang_alias)) ? array($error_lang_alias) : $error_lang_alias;
	
	foreach ($error_lang_alias as $error_lang_alias) 
	{
		$ci->errors[] = $error_lang_alias;
	}
}

function display_errors()
{
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
	if (!empty($ci->errors)) error($ci->errors);
	unset($ci->errors); 	
}

function error($error_lang_alias)
{
	$error =& load_class('Exceptions');

	$error_heading = lang('error_heading');
	$error_heading = empty($error_heading)?'An Error Was Encountered':$error_heading;

	$error_message = array();
	$error_lang_alias = ( ! is_array($error_lang_alias)) ? array($error_lang_alias) : $error_lang_alias;
	foreach ($error_lang_alias as $error_lang_alias){
		$message = lang($error_lang_alias);
		$error_message[] = empty($message) ? $error_lang_alias : $message;
	}
	echo $error->show_error($error_heading, $error_message);
}

/**
 * @author Tsapenko Serghey
 * @access public
 * @since 15.05.2009 (dd.mm.yyyy) 
 * @return boolean 
 */
function isset_errors()
{
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
	return !empty($ci->errors);
}
/**
 * @author Tsapenko Serghey
 * @access public
 * @since 15.05.2009 (dd.mm.yyyy)
 * @return array
 */
function get_errors()
{
	static $ci;
	if (!is_object($ci)) $ci = &get_instance();
	if (!empty($ci->errors)) {
		return $ci->errors;
	} else {
		return array();
	}
}

function browser_access()
{
    $CI =& get_instance();
    $CI->load->library('user_agent');
    if($CI->agent->is_robot())
    {
        return TRUE;
    }   
    else  
    {
        $browser = $CI->agent->browser();     
        $browser_version = $CI->agent->version();
        //$os = $CI->agent->platform();
        if (($browser == "Internet Explorer")&& ($browser_version == "6.0"))
        {
        	return FALSE;        	      		
        }
        return TRUE;
    }
}
