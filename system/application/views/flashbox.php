<script language="JavaScript" type="text/javascript">
var f_res="";
function show_wait(param){
  if (param==1) {$("#wait_img").show();}
  if (param==0) {$("#wait_img").hide();}
}
function get_url(){
var url=window.location.href;
if (url.search(/catalog/) > 0){
   var cat_id=jQuery.url.segment(2);
   f_res="catalog:"+ cat_id
   return f_res;
}
if (url.search(/competition/) > 0){
  f_res="comp:"+"9";
  return f_res;
}
else {
  f_res="cur";
  return f_res;
}
}
</script>
 <div id="flash_box">
<?
  $this->fl->setImgHead ("","100%","100%","fl_container");
?>

<div id="wait_img" style="position:absolute;top:50%;left:50%;z-index:100;display:none;"></div>

</div>