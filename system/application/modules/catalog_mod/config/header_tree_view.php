<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['header'] = array(
	'2' => array(
				'branch_before' => '',
				'branch_after' => '',
				'item_before' => '<ul>',
				'item_text' => '<a class="head_b_link" href="'.url('category_base_url').'/{id}" id="cat_{id}">
								{name}
							    </a><br />',
				'item_after' => '</ul>'
			),
	'3' => array(
				'branch_before' => '',
				'branch_after' => '',
				'item_before' => '',
				'item_text' => '<li><a class="razd_m" href="'.url('category_base_url').'/{id}" id="cat_{id}">
								{name}
							    </a></li>',
				'item_after' => ''
			),
	'default' => array(
				'branch_before' => '',
				'branch_after' => '',
				'item_before' => '',
				'item_text' => '<li><a class="razd_m" href="'.url('category_base_url').'/{id}" id="cat_{id}">
								{name}
							    </a></li>',
				'item_after' => ''
			)
);

/* End of file */