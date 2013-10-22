<?php
	function send_email($email, $subject, $message, $config=array()) {
		static $ci;
		if (!is_object($ci)) $ci = &get_instance();

		if (isset($ci->load->_ci_classes['email']) && ($ci->load->_ci_classes['email'] == 'email')) {
			$ci->email->initialize($config);
		} else {
			$ci->load->library('email', $config);
		}
		
		$ci->email->clear(TRUE);
		$ci->email->from($ci->config->item('admin_email'), $ci->config->item('site_name'));
		$ci->email->to($email);
		$ci->email->subject(_mail_encode($subject, "utf-8"));
		$ci->email->message($message, "utf-8");

		return $ci->email->send();
	}

	function _mail_encode($text, $encoding) {
		$result = "=?".$encoding."?b?".base64_encode($text)."?=";
		return $result;
	}