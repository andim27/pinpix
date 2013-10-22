<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$config['tags_input_block'] = '
		<br /><br />
		<label for="photo_tags">'.lang('keywords').'</label>
		<input type="text" name="photo_tags" id="photo_tags" value="" />
		';

$config['maps'] = array(
					'photo'=>array(
								'table'=>'photo_tag_map',
								'tag_id_field'=>'tag_id',
								'object_id_field'=>'photo_id'
								),
					'album'=>array(
								'table'=>'album_tag_map',
								'tag_id_field'=>'tag_id',
								'object_id_field'=>'album_id'
								)
				  );
$config['list_maps'] = array(
					'photo'=>array(
								'table'=>'photo_category_map',
								'list_id_field'=>'category_id',
								'object_id_field'=>'photo_id'
								),
					'album'=>array(
								'table'=>'album_category_map',
								'list_id_field'=>'category_id',
								'object_id_field'=>'album_id'
								)
				  );
/* End of file */ 