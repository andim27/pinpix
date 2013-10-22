<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['img_types'] = 'bmp|gif|jpg|png|jpeg|swf|BMP|GIF|JPG|PNG|JPEG|SWF';
$config['file_max_size'] = 5000000;
$config['banners_upload_dir'] = dirname(BASEPATH).'/uploads/banners/';
$config['ads_block_ids'] = array(
	"ads_block_top_1" => 'top-1', 
	"ads_block_top_2" => 'top-2', 
	"ads_block_top_3" => 'top-3',	
	"ads_block_right_1" => 'right-1', 
	"ads_block_right_2" => 'right-2', 
	"ads_block_right_3" => 'right-3'
);
/* End of file */