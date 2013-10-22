<?php
if (!defined('BASEPATH')) exit('Нет доступа к скрипту');
/*
 * Libraries Fl
 *
 * @package		Fl
 * @subpackage	Libraries
 * @category	Fl,swf
 * @author		Andrey Makarevich
*/
/**
* myLog
*
* myLog class
*
* @author  (c)Andrey Makarevich
* @class
* @access  public
* @param
*/
class myLog {
var $path;
var $filename;
function __construct() {
$this->path="d:/";
$this->filename="mylog.txt";
}
function write($msg) {
 $f=fopen($this->path.$this->filename,"a+");
 fwrite($f,"\n".$msg);
 fclose($f);
}
}

class Fl {
  var $user_id;
  var $base;
  var $base_static;
  var $fl_ver;
  var $file_name;
  var $file_swf;
  var $aID;
  var $phID;
  var $lst;
  var $code_link;
  var $block_out;
  var $w_out;
  var $h_out;
  var $photo_title;
  function Fl ()
  {
    $this->CI =& get_instance();
    $this->user_id=$this->CI->db_session->userdata('user_id');
    $this->user_id=$this->user_id==""?0:$this->user_id;
    $this->initFlVars();
  }
  function initFlVars() {
     $this->base=base_url();
     $this->base_static =static_url();
     $this->photo_static=photo_location();
     $this->fl_var=9;
     $this->aID=0;
     $this->phID=0;
     $this->lst="?";
     $this->block_out="fl_container";
     $this->w_out="100%";
     $this->h_out="100%";
     $this->file_name= $this->base_static."images/pinpix_top.jpg";
     $this->file_swf ="image_phh_head.swf";
     $this->code_link=$this->base;
     $this->photo_title="PinPix photo";
  }
  function get_control_js($file_cur="",$w="",$h="",$block_cur="") {
    if (!Empty($file_cur)) {
        $this->file_name=$file_cur;
    }
    if (!Empty($w)) {
        $this->w_out=$w;
    }
    if (!Empty($h)) {
        $this->h_out=$h;
    }
    if (!Empty($block_cur)) {
        $this->block_out=$block_cur;
    }
    if ($this->block_out=="fl_container") {
        $photo_title_str=";flashvars.titleVar='".str_replace(array("'",'"',";")," ",$this->photo_title)."'";
        $this->file_swf="image_phh_head.swf";
        $this->base=base_url();
    } else {
        $photo_title_str="";
        $this->photo_title="";
        $this->file_swf="image_phh.swf";
        $this->base=$this->photo_static;
    }
    //$out_str_obj='<object id="fl_container" height="100%" width="100%" type="application/x-shockwave-flash" data="http://local.phh/images/image_phh.swf?42" style="visibility: visible;"><param name="wmode" value="transparent"/><param name="flashvars" value="imageURL=http://local.phh/uploads/photos/02/8-lg.jpg"/></object>';
    $out_fl_need='<br/><span class="zag_big_txt">!This content requires the last Adobe Flash Player<br/>and a browser with JavaScript enabled<br/><a href="http://www.adobe.com/go/getflash/">Get Flash</a></span>';
    $out_str="";
    $out_str.='<script type="text/javascript">';
    $out_str.='$(document).ready(function() { ';
    // $out_str.="$('#fl_container').html('".$out_str_obj."');".chr(13);
    //$out_str.='$(window).resize(function(){$("#flash_box").css("height",parseInt(500*($(window).width()/1900))+"px");});';
    $out_str.='$("#flash_box").css("height",parseInt(500*($(window).width()/1900))+"px");';
    $out_str.='if (fl_v()[0] < 9) { ';
    $out_str.="$('#fl_need').show();";
    $out_str.='} else {';
    $out_str.='var flashvars = {}; ';

    $out_str.='flashvars.baseURL = "'.$this->base.'";';
    $out_str.='flashvars.aID = "'.$this->aID.'";';
    $out_str.='flashvars.phID = "'.$this->phID.'";';

    //$out_str.='flashvars.imageURL = "'.$this->base.$this->file_name.'"'.$photo_title_str.'; ';
    $out_str.='flashvars.imageURL = "'.$this->file_name.'"'.$photo_title_str.'; ';
    $out_str.='var params = {}; ';
    $out_str.='params.wmode = "opaque"; ';  //--opaque --  transparent
    $out_str.='params.quality  = "high"; '; //--autohigh --best
    $out_str.='params.AllowScriptAccess = "always"; ';
    $out_str.='var attributes = {}; ';
    $out_str.='swfobject.embedSWF("'.$this->photo_static.$this->file_swf.'?'.strval(rand(1,1000)).'","'.$this->block_out.'","'.$this->w_out.'","'.$this->h_out.'","'.$this->fl_var.'", ';
    $out_str.='false, flashvars, params, attributes); ';
    $out_str.='}';
    $out_str.='});';
    $out_str.='//AndMak code:e';
    $out_str.='</script>';
    //$this->code_link=$out_str;
    //mylogwrite("$out_str:".$out_str);
    return $out_str;
  }
  function get_block_html($file_cur="",$w="",$h="",$block_cur="") {
    if (!Empty($file_cur)) {
        $this->file_name=$file_cur;
    }
    if (!Empty($w)) {
        $this->w_out=$w;
    }
    if (!Empty($h)) {
        $this->h_out=$h;
    }
    if (!Empty($block_cur)) {
        $this->block_out=$block_cur;
    }
    $out_str="";
    $out_str.="<div id='".$this->block_out."' style='cursor: pointer; cursor: hand;' >";
    //$out_str.='<div id="fl_need" style="display:none"><br/><span class="zag_big_txt">This content requires the  Adobe Flash Player<br/>and a browser with JavaScript enabled<br/><a href="http://www.adobe.com/go/getflash/">Get Flash</a></span></div>';
    $out_str.='<div id="fl_need" style="display:none"><br/><span class="zag_big_txt">'.lang('no_flash').'</span></div>';
    $out_str.="</div>";
    return $out_str;
  }
  function setImg($file_cur,$block_cur) {
   if (!empty($file_cur)){
     $this->file_name=$file_cur;
   }
   //$this->file_name=str_replace ( "-md", "-head" ,$this->file_name );
   if (eregi($this->base,$file_cur)) { //--competit
     $this->base="";
   }
   $this->block_out=$block_cur;
   echo $this->get_control_js();
  }

  function setImgHead($file_cur="",$w="",$h="",$block_cur=""){
    echo $this->get_block_html($file_cur,$w,$h,$block_cur);
    if ($this->neededUrl()) {//required
      $this->file_name=$this->get_needed_file();
      echo $this->get_control_js();
    }
  }
  function neededUrl(){
    $res=TRUE;
    //if (eregi("photo|competition",$_SERVER["REQUEST_URI"])) {
    if (eregi("photo",$_SERVER["REQUEST_URI"])) {
      //$res=FALSE;
    }
    return $res;
  }
  function get_needed_file(){
     if (eregi("competition",$_SERVER["REQUEST_URI"])) {
         $q_str="SELECT distinct cp.competition_id,p.photo_id,p.title,p.extension,p.date_added,u.user_id,u.login
                FROM	photos p,competition_photos cp, competitions c,users u
                WHERE   c.competition_id = cp.competition_id AND cp.photo_id = p.photo_id  ORDER BY RAND() LIMIT 4";
     }else {
          $q_str="SELECT ph.photo_id,ph.title,ph.extension,ph.date_added,u.user_id,u.login FROM photos ph,users u WHERE (ph.moderation_state=".MOD_FEATURED.") and (ph.user_id=u.user_id)  ORDER BY RAND() ";
     }
     $query = $this->CI->db->query($q_str);
     $photo=$query->result();
     if (!empty($photo[0])){
       $photo=$photo[0];
       $this->photo_title="<b>".$photo->login.":</b>".$photo->title.".".date("d.m.Y",strtotime($photo->date_added));
       $this->aID=$photo->user_id;
       $this->phID=$photo->photo_id;
       $urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-head".$photo->extension;
       if (! file_exists( $urlImg)) {
         //$urlImg =photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-lg".$photo->extension;
       }
       return  $urlImg;
     }else {
       return $this->file_name;
     }
  }
  function get_random_file() {
    $urlImg="";
    $photos =$this->get_feat_list();
    if (! empty($photos)){
        foreach ($photos as $photo) {
            $urlImg =date("m", strtotime($photo->date_added))."/".$photo->photo_id."-head".$photo->extension;
            break;
        }
    }
    return  $urlImg;
  }
  function set_fl_code($from_block,$to_block){
    $out_str="";
    $out_str.="<script type='text/javascript'>";
    $out_str.="var tmid;";
    $out_str.="function selectThis(el) {el.focus(); el.select();}";
    $out_str.=" function set_fl_code() { ";
    $out_str.=" window.clearTimeout(tmid);";
    $out_str.="$('#".$to_block."').val($('#".$from_block."').html().replace(/^\s+/, '').replace(/\s+$/, ''));";
    $out_str.="}";
    $out_str.=" $(document).ready(function() { tmid=window.setTimeout('set_fl_code();',1000); })";
    $out_str.="</script>";
    echo $out_str;
  }
function get_comp_list($comp_id=null){
  $photos=null;
  $competition_id=intval($comp_id);
  //log_message('debug', 'comp_list competition_id='.$competition_id);
  if (empty($competition_id)) {
    return null;
  }else {
    $q_str="SELECT distinct cp.competition_id,p.photo_id,p.title,p.extension,p.date_added,p.erotic_p,u.user_id,u.login
    FROM	photos p,competition_photos cp, competitions c,users u
    WHERE   c.competition_id = cp.competition_id AND cp.photo_id = p.photo_id AND c.competition_id = ".$competition_id." AND u.user_id=p.user_id";
    $query = $this->CI->db->query($q_str);
    $photos = $query->result();

    $this->lst="c";
    return $photos;
  }
}
function get_catalog_list($cat_id) {
  $photos=null;
  if (! empty($cat_id)) {
      $cat_cond="and (cat.category_id=".$cat_id.")";
  } else {
      $cat_cond="";
  }
  $q_str="SELECT ph.photo_id,ph.title,ph.extension,ph.date_added,u.user_id,u.login,ph.erotic_p FROM photos ph,users u,photo_category_map cat WHERE (ph.moderation_state >=".MOD_FEATURED.") and (u.user_id=ph.user_id) and (ph.photo_id=cat.photo_id) ".$cat_cond." ORDER BY RAND()";
  $query = $this->CI->db->query($q_str);
  $photos = $query->result();
  $this->lst="r";
  return $photos;
}
function get_feat_list() {
  $photos=null;
  $q_str="SELECT ph.photo_id,ph.title,ph.extension,ph.date_added,u.user_id,u.login,ph.erotic_p FROM photos ph,users u WHERE (ph.moderation_state=".MOD_FEATURED_MAIN.") and (u.user_id=ph.user_id) ORDER BY RAND()";
  $query = $this->CI->db->query($q_str);
  $photos = $query->result();
  $this->lst="f";
  //log_message('debug', 'get_feat_list!!!');
  return $photos;
}
function set_slide_list() {

if (eregi("comp",$_POST["p"])) {
    $comp_id=substr($_POST["p"],strpos($_POST["p"],":")+1);
    $photos=$this->get_comp_list($comp_id);
    if (empty($photos)) {
     $photos=$this->get_feat_list();
    }
}
if (eregi("catalog",$_POST["p"])) {
    $cat_id=substr($_POST["p"],strpos($_POST["p"],":")+1);
    $photos=$this->get_catalog_list($cat_id);
    if (empty($photos)) {
     $photos=$this->get_feat_list();
    }
} else {
    $photos=$this->get_feat_list();
}
$user_age =modules::run('users_mod/users_ctr/get_age', $this->user_id);
$data="";
$data.="<list base_url='".$this->photo_static."' lst='".$this->lst."'>";
foreach ($photos as $photo) {
  //$urlImg = $this->base_static."uploads/photos/".date("m", strtotime($photo->date_added))."/".$photo->photo_id."-head".$photo->extension;
  $urlImg = $this->photo_static.date("m", strtotime($photo->date_added))."/".$photo->photo_id."-head".$photo->extension;
  if (! file_exists( $urlImg)) {
     //$urlImg =$this->photo_static.date("m", strtotime($photo->date_added))."/".$photo->photo_id."-lg".$photo->extension;
  }
  if (($photo->erotic_p == 1) && ($user_age ==-1)) {
   //continue;//--don't include in display list
  }
  $data.="<item id='".$photo->photo_id."' t='".$photo->title."' u='".str_replace(array("'",'"',";")," ",$photo->login)."' a_id='".$photo->user_id."' url='".$urlImg."' dt='".date("d.m.Y",strtotime($photo->date_added))."' ></item>";
}
$data.="</list>";
echo $data;
}

}