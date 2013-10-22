<?php
 if (!defined('BASEPATH')) exit('Нет доступа к скрипту');
/*
 * Libraries GLlib
 *
 * @package		GLlib
 * @subpackage	Libraries
 * @category	Fl,swf
 * @author		Andrey Makarevich
*/
class GLlib {
  var $user_id;
  var $base;
  var $base_static;
  var $photo_static;
  var $fl_ver;
  var $file_name;
  var $file_swf;
  var $file_xml;
  var $path_file_xml;
  var $aID;
  var $phID;
  var $lst;
  var $code_link;
  var $block_out;
  var $w_out;
  var $h_out;
  var $photo_title;
  function GLlib () {
    $this->CI =& get_instance();
    $this->user_id=$this->CI->db_session->userdata('user_id');
    $this->user_id=$this->user_id==""?0:$this->user_id;
    $this->initVars();
  }
  function initVars() {
    $this->fl_var=9;
    $this->base=base_url();
    $this->base_static  =static_url();
    $this->photo_static =photo_location();
    $this->path_file_swf=$this->photo_static;
    $this->file_swf     ="to_show.swf";
    //$this->file_swf     ="agat.swf";
    $this->path_file_xml=$this->photo_static+"data/xml/";
    $this->file_xml     ="data.xml";
  }
  function make_data($items) {
    $out_str='';
    $back_to_gallaries    ='www.back_to_gallary.php';
    $link_nravitco        ='www.cool.ru';
    $link_for_coment      ='www.coment.php';
    $link_for_show_coments='www.show_coments.php';
    $text_0_link_galary   ='http://images.google.ru/imglanding?imgurl=http://www.restmebel.ru/t_nl';
    $text_1_link_galary   ='Министр обороны США Роберт Гейтс выразил удивление';

    $out_str.='<datas back_to_gallaries = "'.$back_to_gallaries.'" link_nravitco = "'.$link_nravitco.'" link_for_coment = "'.$link_for_coment.'" link_for_show_coments = "'.$link_for_show_coments.'"  text_0_link_galary = "'.$text_0_link_galary.'" text_1_link_galary = "'.$text_1_link_galary.'">';
    foreach ($items as $item){
     $id_photo  ='123';
     $small_name='05/140-md.JPG';
     $big_name  ='05/140-lg.JPG';
     $title     ='Название фото';
     $autor     ='Автор фото';
     $link_autor_photo= 'www.link0.ru';
     $coments_cnt='10';
     $out_str.='<img id = "'.$id_photo.'" prew = "'.$small_name.'" big = "'.$big_name.'" title = "'.$title.'" autor = "'.$autor.'" link = "'.$link_autor_photo.'" coments = "'.$coments_cnt.'"></img>';
	}
    $out_str.='</datas>';
    $f=fopen($this->path_file_xml.$this->file_xml,"w");
    flock($f,LOCK_EX);
    $res=fwrite($f,$out_str);
    flock($f,LOCK_UN);
    fclose($f);
    return $res;
  }
  function get_gl_js($block_out,$w_out,$h_out) {
    $out_str="";
    $out_str='<div id="fl_need" style="display:none"><br/><span class="zag_big_txt">'.lang('no_flash').'</span></div>';
    $out_str.='<script type="text/javascript">';
    $out_str.='$(document).ready(function() { ';
    $out_str.='f_v=swfobject.ua.pv[0];';
    //$out_str.='alert (f_v); ';
    $out_str.='if (f_v < 9) { ';
    //$out_str.='document.write(\''.'<div id="fl_need" style="display:none"><br/><span class="zag_big_txt">'.lang('no_flash').'</span></div>\');';
    $out_str.="$('#fl_need').show();";
    $out_str.='} else {';
    $out_str.='var flashvars = {}; ';
    $out_str.='var params = {}; ';
    $out_str.='var attributes = {}; ';
    $out_str.='params.wmode = "transparent"; ';
    $out_str.='params.AllowScriptAccess = "always"; ';
    $out_str.='var attributes = {}; ';
    //$out_str.='swfobject.embedSWF("'.$this->path_file_swf.$this->file_swf.'?'.strval(rand(1,1000)).'","'.$block_out.'","'.$w_out.'","'.$h_out.'","'.$this->fl_var.'", ';
    $out_str.='swfobject.embedSWF("'.$this->path_file_swf.$this->file_swf.'?'.strval(rand(1,1000)).'","'.$block_out.'","'.$w_out.'","'.$h_out.'","'.$this->fl_var.'", ';
    $out_str.='false, flashvars, params, attributes); ';
    $out_str.='}';
    $out_str.='});';
    $out_str.='//AndMak code:e';
    $out_str.='</script>';
    return $out_str;
  }

}
?>