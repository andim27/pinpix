<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['img_types'] = 'bmp|gif|jpg|png|jpeg';
$config['img_size'] = array('full' => 1280, 'middle' => 600, 'thumbnail' => 150);
$config['img_upload_dir'] = dirname(BASEPATH).'/uploads/';
$config['rel_photo_upload_dir'] = '/uploads/photos';
$config['photo_upload_dir'] = dirname(BASEPATH).$config['rel_photo_upload_dir'];
$config['file_max_size'] = 50000000;
$config['h_min'] = 592;
$config['w_min'] = 622;
/* End of file */