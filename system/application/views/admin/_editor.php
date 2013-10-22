<html>
<?php include('head.php'); ?>

<style type="text/css">
body
{
	margin: 0;
	padding: 0;
	background: #BBBBBB;
}

.jcropper-holder { border: 1px black solid; }

#outer {
	text-align: center;
}

.jcExample
{
	text-align: left;
	background: white;
	width: 975px;
	font-size: 80%;
	margin: 3.5em auto 2em auto;
	*margin: 3.5em 10% 2em 10%;
	border: 1px black solid;
	padding: 1em 2em 2em;
}

.jcExample .article
{
	width: 975px;
}

form
{
	margin: 1.5em 0;
}

form label
{
	margin-right: 1em;
	font-weight: bold;
	color: #990000;
}

.jcExample p
{
	font-family: Verdana, Helvetica, Arial, sans-serif;
	font-size: 90%;
}
.imgCropCont
{
  width:70%;
  float:left;

}

.author
{
  position: relative;
  right: 0px;
  bottom: 0px;
  margin: 5px;
  text-align: right;
  font-size: xx-small;
  color: #CCCCCC;
}


.imgCropControl
{
  position: relative;
  float: right;
  margin: 5px 1px;
  text-align: left;
  width:29%;

}

#cropwh
{
 display:none;
 text-align: left;
}
#headphotos_list
{
  display:none;
  border-style:none;
  border-color: #00CC00;
  overflow: scroll auto;
  height: 120px;
  text-align: left;
  padding: 5px;
  margin: 5px 0px;
}
#headphotos_one
{
 float:left;
 width: 145px;
 height: 97px;
 margin:0px 5px 0px;
}

</style>
<script language="Javascript">
var cx,cy,cx2,cy2,cw,ch;
var ajax_actions_path = "<?php echo base_url(); ?>gallery_mod/gallery_ctr/crop_action";
var preview_url="";
var hlv=false;
var photo_id=<?=$photo_id;?>;
 jQuery(function(){
			jQuery('#cropbox').Jcrop(
            {
            onSelect: showCoords,
            onChange: showCoords
            }
            );
 });
 function showCoords(c) {
  $("#cropwh").show();
  $("#crop_w").val(c.w);
  $("#crop_h").val(c.h);
  cx=c.x;
  cy=c.y;
  cw=c.w;
  ch=c.h;
 }
 function saveCrop() {
     $('#loader').show();
     jQuery.post(ajax_actions_path, {
		action: "crop_img",
        photo_id: photo_id,
        cx: cx,
        cy: cy,
        cw: cw,
        ch: ch
		},
		function(data){
          data = window["eval"]("(" + data + ")");
          alert(data.mes);
          showImgHead(data.dst);
          if (data.err == '0') {
             alert("Error !");
          }
          $('#loader').hide();
		},
		'html'
	);
 }
 function cancelCrop() {
   window.location.reload()
 }
 function setHTMLHead(url) {
   preview_url=url;
   var ct = new Date();
   rand=ct.getTime();
   $("#flash_box").html("<img src='"+preview_url+"?"+rand+"' />");
 }
 function showImgHead(url) {
   preview_url=url;//'<?=$src_head;?>';
   setHTMLHead(preview_url);
 }
 function showHeadList() {
   if (hlv == false) {
      $("#headphotos_list").show();
      hlv=true;
   } else {
      $("#headphotos_list").hide();
      hlv=false;
   }
 }
 function showHeadReal() {
   window.location.reload()
 }
 function selectListOne(src_one,p_id) {
   photo_id=p_id;
   $(".a_one").css("border-color","#CCCCCC");
   $(".small_one").css("border-color","#CCCCCC");
   $("#img_"+p_id).css("border-color","#00CC00");
   $("#cropbox").attr("src",src_one);
   $("#imgCropCont >div >div >div >img").attr("src",src_one);
   $("#imgCropCont >div >img").attr("src",src_one);
   setHTMLHead(src_one.replace("-md","-head"));


 }
</script>
	<body>
		<div id="outer">

        <div class="jcExample">
        	<div id="h_r_p_people" <?php if( ! $user_id) echo 'style="display: none;"';?>></div>
        		<div id="place_txt_people" <?php if( ! $user_id) echo 'style="display: none;"';?>>
        			<a href="<?php echo url('my_account_url'); ?>" class="head_link" style="vertical-align:top;">
        				<span class="txt_people" id="user_login"><?php echo $user_login; ?></span>
        			</a>
        		</div>

        		<div id="p_h_link_2" style="text-align:left;<?php if( ! $user_id) echo 'display: none;';?>" >
        			<a href="<?=base_url();?>admin" class="head_link">Админ меню</a>
        	</div>

        <div id="flash_box">
         <?  $this->fl->setImgHead ("<?=$src_head; ?>","100%","100%","fl_container");?>
        </div>
        <div class="article">
			<h1>Редактор изображения шапки</h1>

            <div id="dl_links">
				<a href="<?=base_url(); ?>admin/">Админ меню</a> |
				<a href="<?=base_url(); ?>admin/photos/">Админ фото</a>  |
               	<a href="javascript:showHeadList();">Шапка фото</a>  |
               	<a href="javascript:showHeadReal();">Шапка сайта</a>
			</div><br>
            <div id="headphotos_list" >
               <?php foreach ($photos as $item) :?>
                  <div id="headphotos_one">
                   <a class="a_one" style="color: #CCCCCC;" href="javascript:selectListOne('<?=photo_location().date('m', strtotime($item->date_added)).'/'.$item->photo_id.'-md'.$item->extension;  ?>',<?= $item->photo_id; ?>);">
                   <?php if ($photo_id == $item->photo_id) : ?>
                     <img class="small_one" id="img_<?= $item->photo_id; ?>" border="2px" style='border-color: #00CC00;' width="145" height="97" src='<?=photo_location().date('m', strtotime($item->date_added)).'/'.$item->photo_id.'-sm'.$item->extension;  ?>' />
                    <?php else: ?>
                     <img class="small_one" id="img_<?= $item->photo_id; ?>" width="145" height="97" src='<?=photo_location().date('m', strtotime($item->date_added)).'/'.$item->photo_id.'-sm'.$item->extension;  ?>' />
                    <?php endif; ?>
                    </a>
                  </div>
               <?php endforeach; ?>
            </div>
		   <!--	<img src="demo_files/flowers.jpg" id="cropbox" />      -->
           <div class="imgCropCont" id="imgCropCont">
                <img src="<?=$src_md;?>" id="cropbox" />
           </div>

           <div class="imgCropControl">
                 <p>
    				<b>Редактор предназначен для подготовки изображений для шапки.<br><br>
    				Укажите новую область на изображении (ЛКМ+перетащить+отпустить).</b>
    			 </p>
                 <div id="cropwh">
                    Ширина: <input id="crop_w" type="text" size="4"/>
                    Высота: <input id="crop_h" type="text" size="4"/>
                </div><br>
    			<div id="dl_links">
    				<a href="javascript:saveCrop();">Сохранить</a>
                    <img id="loader" alt="saving..." border="0" src="<?php echo base_url() ?>static/images/add-note-loader.gif" style="display:none;" /> |
    				<a href="javascript:cancelCrop();">Отмена</a>
    			</div>
           </div>
           <div class="clear"></div>


		</div>
        <div class="author"><a style="color: #CCCCCC;" title="Developer:Andrey Makarevich,Ukraine,Kharkov" href="skype:andrey_makarevich">(c) AndMak</a></div>
		</div>
		</div>
	</body>
</html>

