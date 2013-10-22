<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$config = array(
           'comments_ctr/add_comment' => array(
                                    array(
                                            'field' => 'comment_body',
                                            'label' => lang('comment_body_field'),
                                            'rules' => 'required'
                                         )
                                    )
               );

/* End of file */ 