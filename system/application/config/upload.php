<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$config['upload_path'] = dirname(BASEPATH).'/uploads/';
$config['allowed_types'] = 'bmp|gif|jpg|png|jpeg|tiff|BMP|GIF|JPG|PNG|JPEG|TIFF|zip|rar|ZIP|RAR';
$config['max_size'] = 50000000;
$config['remove_spaces'] = TRUE;
$config['overwrite'] = TRUE;

/* End of file */ 