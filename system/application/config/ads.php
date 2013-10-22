<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*
in this config we set which ads_blocks would be displaied at each page.
we set id of block as item of array. 
example:
$config['main'] = array( 'ads_block_1', 'ads_block_2', 'ads_block_3');
Типы банеров: обычные и сквозные.
ads_block_1 - сквозной, ставятся на всех, кроме страниц "Мой профиль", "Мои фото", "Добавить фото".
ads_block_2 - обычный, ставится только на главной странице
*/
/*$config['main'] = array( 'ads_block_1');
$config['category'] = array( 'ads_block_1');
$config['album'] = 'no';
$config['competition'] = 'no';
$config['photo'] = 'no';
$config['profile'] = 'no';
$config['search'] = array( 'ads_block_1');
$config['all_albums'] = array( 'ads_block_1');
$config['p_upload'] = 'no';*/

/*"ads_block_top_1" => 'top-1', 
"ads_block_top_2" => 'top-2', 
"ads_block_top_3" => 'top-3',	
"ads_block_right_1" => 'right-1', 
"ads_block_right_2" => 'right-2', 
"ads_block_right_3" => 'right-3'*/
	
$config = array(
	'main' => array('ads_block_top_1', 'ads_block_right_1'),
	'profile_view' => array('ads_block_top_2'),
	'profile_edit' => array('ads_block_top_2'),
	'competition' => array('ads_block_top_2', 'ads_block_right_2'),
	'photo_add' => array('ads_block_top_2', 'ads_block_right_2'),
	'photo_view' => array('ads_block_top_2', 'ads_block_right_2'),
	'album_view' => array('ads_block_top_2', 'ads_block_right_2'),
	'category' => array('ads_block_top_2', 'ads_block_right_2'),
	'search' => array('ads_block_top_2', 'ads_block_right_2')
);

/* End of file */ 