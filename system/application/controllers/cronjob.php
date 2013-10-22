<?php
class Cronjob extends Controller {
	
	function Cronjob() {
		parent::Controller();
	}
	
	function index(){}
	
	function delete_del_photo(){	
		// get all photos with status '-2'
		// check their date
		// delete if it current
		// $photo_id=null, $user_id=null, $moderation_state, $registered=0, $erotic=-1, $per_page=0, $page=1, $with_count=false, $order_by='date_added DESC', $period='', $order_in=""
		$photos = modules::run('gallery_mod/gallery_ctr/get_photos_new', null, null, -2, 0, -1, 0, 1, false, 'date_added DESC', 30);
		if(!empty($photos)) {
			log_message('error', 'CRON. DEBUG. delete_del_photo. START');
			log_message('error', 'CRON. DEBUG. delete_del_photo. photos count: '.count($photos));
			foreach ($photos as $photo) {
				$result = modules::run('gallery_mod/gallery_ctr/remove_photo', $photo->photo_id);
				log_message('error', 'CRON. DEBUG. delete_del_photo. Photo: '.$photo->photo_id." delete status: ".$result);
			}
		}
	}	
}

/* end of file */