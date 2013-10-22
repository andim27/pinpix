<?php
	/**
	 * Class Test
	 *
	 * test
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Test.class.php
	 * @created  Tue Jun 01 11:56:10 EEST 2010
	 */
	class Test extends Controller 
	{
		/**
		 * Constructor of Test
		 *
		 * @access  public
		 */
		function Test() {
			parent::Controller();
		}
	
		function index(){
			$photos = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, null, -2, 0, -1, 0, 1, false, 'date_added DESC', 30);
			echo "<pre>";
				print_r($photos);
			echo "</pre>";exit;
		}
		
		function mailtest(){
			$this->load->library('email');
			$this->config->load('email');
			
			$this->email->clear();
			$this->email->from('support.pinpix@pinpix.kz', 'pinpix.kz');
			$this->email->to("monah40@gmail.com");
			$this->email->subject("Subject");
			$this->email->message("message1", "utf-8");
			
			$result = $this->email->send();
			echo "test 1: ";
			echo "<pre>";
				print_r($result);
			echo "</pre>";
			$result = mail("monah40@gmail.com", "Subject", "message2");
			echo "test 2: ";
			echo "<pre>";
				print_r($result);
			echo "</pre>";
			
			$this->load->helper('email');
			$result = send_email("monah40@gmail.com", "Subject", "message3");
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}
		
		/**
		 * Destructor of Test 
		 *
		 * @access  public
		 */
		function _Test() {
		 	
		 }		
	}
?>