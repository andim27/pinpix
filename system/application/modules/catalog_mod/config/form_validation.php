<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$config = array(
           'catalog_ctr/edit_category' => array(
                                    array(
                                            'field' => 'cat_name',
                                            'label' => lang('cat_name_field'),
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'cat_desc',
                                            'label' => lang('cat_desc_field'),
                                            'rules' => 'required'
                                         )
                                    )
               );

/* End of file */ 