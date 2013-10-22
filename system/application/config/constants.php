<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('MOD_NEW', 0);
define('MOD_APPROVED', 1);
define('MOD_FEATURED', 2);
define('MOD_FEATURED_MAIN', 22); 
define('MOD_DECLINED', -1);
define('MOD_DELETED', -2);

//define('NEW_PHOTOS', 100);
define('MODERATION_STATE', 1);
define('LAST_PHOTOS_PERIOD',50);
define('MAX_UPLOAD_FILES',5);

// competitions constant
define('CMP_ESTIMATED',11);
//user constant
define('USER_COMMON',1);
define('USER_REG',2);
define('USER_MODER',3);
define('USER_HMODER',4);
define('USER_ADMIN',5);
define('USER_JURY',6);
/* End of file constants.php */
/* Location: ./system/application/config/constants.php */