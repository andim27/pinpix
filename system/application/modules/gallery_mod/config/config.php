<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['img_types'] = 'bmp|gif|jpg|png|jpeg|tiff|BMP|GIF|JPG|PNG|JPEG|TIFF';
$config['img_size'] = array('full' => 1280, 'head' => array('width' => 975, 'height' => 300), 'middle' => array('width' => 625, 'height' => 592), 'thumbnail' => array('width' => 145, 'height' => 138));
$config['img_upload_dir'] = dirname(BASEPATH).'/uploads/';
$config['rel_photo_upload_dir'] = '/uploads/photos';
$config['photo_upload_dir'] = dirname(BASEPATH).$config['rel_photo_upload_dir'];
$config['file_max_size'] = 50000000;
$config['h_min'] = 592;
$config['w_min'] = 622;
$config['path_to_convert'] = "/usr/local/bin/convert";
//$config['jury_logins'] = array("oksana","andmak","musik");
/*$config['jury_logins'] = array(
	"Karina Abdullina",
	"Bulat Syzdykov",
	"Bulat Syzdykov",
	"Jorge Skorobogatov",
	"Ali Amanbayev",
	"Timur B.",
	"Susanne Kuester"
);*/
$config['jury_logins'] = array();

/* End of file */

