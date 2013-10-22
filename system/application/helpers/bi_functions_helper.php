<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * dateHuman_str converts date to human string
 *
 * @package		CodeIgniter
 * @author		AndMak
 * @copyright	Copyright (c) 2010
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
if ( ! function_exists('dateHuman_str'))
{
    function dateHuman_str($d){
      $CI =& get_instance();
      $dt_arr = getdate(strtotime($d));
      $day    = $dt_arr['mday'];
      $month  = $dt_arr['mon'];
      $year   = $dt_arr['year'];
      $month_str="<span id='month'>".lang("month_".$month)."</span>";
      $out_str=" ".$day." ".$month_str." ".$year;
    return $out_str;
}
}
function cache_clear() {
      Header("Expires: Mon, 26 Jul 1990 05:00:00 GMT"); //Дата в прошлом
      Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
      Header("Pragma: no-cache"); // HTTP/1.1
      Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
}
function pr($v){
     $f=fopen("d:/mylog.txt","a+");
     fwrite($f,$v);
     fclose($f);
}
/**
 * CodeIgniter
 *
 * lang_translate translate word to foreign language
 *
 * @package		CodeIgniter
 * @author		AndMak
 * @copyright	Copyright (c) 2010
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @param       word - input word for translate
 * @param ext   lang_arr - conversion table
 * @param       lang_str - language string value
 * @filesource
 */
if ( ! function_exists('lang_translate'))
{
   function lang_translate($word,$lang_str='kz'){
       $word_out=$word;
       $CI =& get_instance();
       //pr("\n".$word." _lng=".$CI->_lng);
       if (! isset($CI->_lng)) {return $word_out;}
       if ($CI->_lng == $lang_str) {
             if (empty($lang_arr)) {
                 $CI->config->load('lang_table');
                 $lang_arr=$CI->config->item('lang_arr');
             }
             if (!empty($lang_arr)) {  //--for future version
                   foreach ($lang_arr as $w=>$v) {
                    $word_out=eregi_replace($w,$v[$lang_str],$word_out);
                   }
             }
       }
       return $word_out;
   }
}
