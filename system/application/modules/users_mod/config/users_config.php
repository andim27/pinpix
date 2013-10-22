<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['auth_success_path'] = '';//base_url();

$config['rel_avatars_dir'] = static_url().'images/user_avatars';
$config['avatars_dir'] =  dirname(BASEPATH).'/static/images/';
$config['user_avatars_dir']   = dirname(BASEPATH).'/static/images/user_avatars';
$config['predef_avatars_dir'] = dirname(BASEPATH).'/static/images/predef_avatars';
$config['user_avatars_http']   = static_url().'images/user_avatars';
$config['predef_avatars_http'] = static_url().'images/predef_avatars';
$config['avatar_width']  = 50;
$config['avatar_height'] = 50;
$config['avatar_max_v']  = 100;//Kb
$config['avatar_max_w']  = 640;//px
$config['avatar_max_h']  = 480;//px

/* End of file */