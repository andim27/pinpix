<?php
 //---Photo prop view


?>
<script language="JavaScript" type="text/javascript">
 function ShowPhotoProp(id) {
	 //alert ('ShowPhotoProp'+id )
  var vis=$("#popup_"+id).css("display");
  //ShowDetalis('photo_'+id);
  var _id=id;
  $("#pp_category").attr("selectedIndex", $("#pp_category option[value='"+$("#ph_category_"+_id).val()+"']").attr("index"));
  if (vis == "block") {
        HidePhotoProp(id);
   } else {
        ShowFon();
  
        $("#mes_prop").html("");

    	if (document.getElementById("call_prop_"+id))
    	{
	        pos=$("#call_prop_"+id).offset();
	        $("#popup_"+id).css("z-index", 1000);
	        $("#popup_"+id).css("top", (pos.top+30)+"px");
	        $("#popup_"+id).css("left", (pos.left-350)+"px");
    	}
    	$("#popup_"+id).show();
   }
 }
 function HidePhotoProp(id,l) {
  HideFon();
  $("#popup_"+id).hide();
  clearTimeout();
  if (l == 0) {return;}
  window.location.reload(true);
 }
 function ShowFon(){
	var overlay = document.createElement("div");
	overlay.id = "overlay";
	$("body").append(overlay);
	$("#overlay").css("opacity", .5);
	$("#overlay").css("height", $(document).height()+"px");
 }
 function HideFon() {
   $("#overlay").remove();
 }
 
 ajax_prop_path="<?php echo base_url(); ?>photo/prop_action/";
 msg_cur_prop="mes_prop";
 
 function ajax_save_prop(form_id,msg_id) {
	var options_prop = {
		target:"#"+msg_id,
		url:ajax_prop_path,
		resetForm:true,
		dataType: "json",
		success:showResponseProp
	}
	msg_cur_prop=msg_id;
	$('#'+msg_id).show();
	$('#'+msg_id).html("<?= lang('photo_prop_send'); ?>");
	$('#'+form_id).ajaxSubmit(options_prop);
}
function showResponseProp(data, statusText)  {
    $('#'+msg_cur_prop).show();
    $('#'+msg_cur_prop).css('color', '#00FF00');
    $('#'+msg_cur_prop).html("<b>"+data.mes+"</b>");

    if (data.tm != undefined){
      setTimeout('HidePhotoProp(<?= $photo->photo_id; ?>)',data.tm);
    }
}
</script>
<!-- B:New Designe -->
 <div id="popup_<?= $photo->photo_id; ?>" class="popUp" style="display:none">
  <img  style="position:absolute;top:3px;right:3px" title="Закрыть" border="0" src="<?= static_url(); ?>images/ic_close.gif" onclick="HidePhotoProp(<?= $photo->photo_id; ?>,0)" />
   <div class="wrap">
      <div class="photoOptions">

        <h2><?= lang('photo_prop_title'); ?></h2>
        <form id="photo_prop" method="post">
        <input type="hidden" name="on_what_id" value="<?= $photo->photo_id; ?>"/>
        <input type="hidden" name="action" value="photo_prop"/>
        <input id="pp_id" type="hidden" name="photo_id" value="<?= $photo->photo_id; ?>" />
        <span><?= lang('photo_prop_name'); ?><input  id="pp_title" class="i" type="text" name="photo_name" value="<?= substr($photo->title,0,58); ?>" /></span>

		<span><?=lang('user_photo_description')?></span>

		 <textarea id="pp_descr" rows="" cols="39" name="photo_desc" class="i"><?= $photo->description; ?></textarea>
         <span><?=lang('user_photo_allowed')?></span>
         <select id="pp_allowed" class="s" name="view_allowed" onChange="propAlovedChange(this);">
         <?php if(!empty($view_allowed)): foreach($view_allowed as $idx => $restrict):?>
			        <option  <?= $photo->view_allowed ==$idx?'selected="selected"':''; ?> value="<?=$idx?>"><?=$restrict?></option>
         <?php endforeach; endif; ?>
		</select>
        <div id="hidden_pwd" style="display:none;">
 		  <div class="place_ser_otsup_txt_zp">
		    <span class="ent_txt"><?=lang('')?>New password</span>
		  </div>
		  <input id="" class="i" type="text" name="photo_password" />
   		<div class="place_ser_otsup_txt_zp">
	  	  <span class="ent_txt"><?=lang('')?>New password confirmation</span>
		</div>
		  <input id="" class="i" type="text" name="photo_password_confirm" />
 		</div>

        <span><?= lang('album_cat'); ?>
        <select id="pp_category" class="s" name="photo_category">
        <?php if(!empty($categories)): foreach($categories as $category):?>
            <?php if ($category->id == $photo->category_id) { ?>
                            <option selected="selected" value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
                   <?php } else {?>
                            <option  value="<?php echo $category->id;?>"><?php echo lang_translate($category->name,"kz");?></option>
                   <?php } ?>
        <?php endforeach; endif; ?>
        </select>
        </span>
        <span><?= lang('photo_of_album'); ?>
            <select id="pp_album" class="s" name="photo_album">
            <?php if(!empty($all_albums)): foreach($all_albums as $album):?>
				<option  <?= $photo->album_id==$album->album_id?"selected='selected'":"" ?> value="<?php echo $album->album_id;?>"><?php echo $album->title?></option>
            <?	endforeach; endif; ?>
            </select>
        </span>
        <span><?= lang('photo_prop_to_comp'); ?>
<!--            <select id="pp_competition" class="s" <?php //if(!empty($competition_photo)) echo "disabled"; ?>>-->
        <?php
        	$style = "";
        	if(isset($competition_photo) && !empty($competition_photo)) {        	
        		if(!empty($competition_photo->competition_id) && !empty($competition_photo->photo_id)) {
        			$style = 'disabled="disabled"';
        		}
        	}
        ?>
			<select id="pp_competition" class="s" name="photo_competition" <?=$style?>>
		  	    <option value="0">&nbsp;</option>
                <?php
                	if(!empty($competitions)): foreach($competitions as $competition) {
                		$selected = "";
                		if($competition->competition_id == $competition_photo->competition_id) $selected = "selected='selected'";
                ?>
		  	                <option <?=$selected?> <?php if ($competition->active == 0) echo ' style="display:none;"'?> value="<?php echo $competition->competition_id;?>"><?php echo $competition->title;?></option>
                <?php	} endif; ?>
            </select>
        </span>

        <span>
      <!--  <em>
        <strong>Внимание!</strong>
        На один конкрс можно присылать не больше пяти фото!
        </em>-->
        </span>
        <span>
         <input style="display:inline" id="pp_erotic" class="ch" name="erotic_p" type="checkbox" <?= $photo->erotic_p ==1?"checked":""; ?> />
         <?= lang("album_erotic"); ?>
        </span>
        <span style="display:none">
            <input class="ch" type="checkbox" value="" name=""/>
            <?= lang('photo_prop_main_album'); ?>
        </span>
        <span>
            <input style="display:inline" id="pp_album_main" class="ch"  type="checkbox" value="" title="<?= lang('photo_prop_main_album'); ?>" name="photo_main_page" />
            <?= lang('photo_prop_main_album'); ?>
        </span>
        <br/>
        <span id="mes_prop" style="display:none">
        </span>
        <span>
            <input class="button" type="button" value="Ok" name="" onclick="ajax_save_prop('photo_prop','mes_prop');"/>
            <input class="button" type="button" value="Отмена" name="" onclick="HidePhotoProp(<?= $photo->photo_id; ?>,0)"/>
        </span>
    </div>
    <!--        old          -->

    <!--        old             -->
    </form>
    <div class="topBg"></div>
    <div class="bottBg"></div>
   <!-- wrap -->
   </div>
<!-- PopUp -->
 </div>


<!-- E:New Designe -->
