<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$config['branch_before'] = '<ul class="level_{level}">';
$config['branch_after'] = '</ul>';
$config['item_before'] = '<li>';
$config['item_text'] = '<a href="'.url('category_base_url').'/{id}" id="cat_{id}">
							<img alt="" src="'.url('category_icon_url','{icon}').'" />
							{name}
						</a>';
$config['item_after'] = '</li>';

/* End of file */