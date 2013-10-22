<script type="text/javascript">
function changeButton(cur, index) {

	sImg = cur.src.substring(cur.src.lastIndexOf('images/')+7);
	if (sImg == 'm_box_plus.gif') {
		cur.src = '<?php echo base_url(); ?>images/m_box_minus.gif';
		document.getElementById('comp_invisible_'+index).style.display = '';
	}
	else {
		cur.src = '<?php echo base_url(); ?>images/m_box_plus.gif';
		document.getElementById('comp_invisible_'+index).style.display = 'none';
	}	
}

function changeAllButtons(amount) {
	for (var i = 1; i<= amount; i++)
	{
	document.getElementById('comp_invisible_'+i).style.display = '';
	var a = document.getElementById('im_znak_'+i);
	var sImg = a.src.substring(a.src.lastIndexOf('images/')+7);
	if (sImg == 'm_box_plus.gif') {
		a.src = '<?php echo static_url(); ?>images/m_box_minus.gif';			
		}	
	}
}
</script>
<?php $user_group = $this->db_session->userdata('user_group'); ?>
<?php if ($user_group === "admins" || $user_group === "moderators") :?>
<script type="text/javascript">
function StartSelect(dateText) {
	$('#end_date').datepicker('option', 'minDate', new Date(dateText));
	if ($('#end_date').val() == '') {
		$('#end_date').val(dateText);
		return;
	}
	var Start = new Date(dateText).getTime();
	var End = new Date($('#end_date').val()).getTime();
	if (End < Start) {
		$('#end_date').val(dateText);
	}
}
function EndSelect(dateText) {
	$('#start_date').datepicker('option', 'maxDate', new Date(dateText));
	if ($('#start_date').val() == '') {
		$('#start_date').val(dateText);
		return;
	}
	var Start = new Date($('#start_date').val()).getTime()
	var End = new Date(dateText).getTime();
	if (End < Start) {
		$('#start_date').val(dateText);
	}
}
var EmptyTitle = "<?=lang('js_label_EmptyTitle');?>";
var EmptyStartTime = "<?=lang('js_label_EmptyStartTime');?>";
var EmptyEndTime = "<?=lang('js_label_EmptyEndTime');?>";
var EmptyDescription = "<?=lang('js_label_EmptyDescription');?>";

function AddSubmit() {
	if ($('#title').val() == '') {
		alert(EmptyTitle);
		return false;
	}
	if ($('#start_date').val() == '') {
		alert(EmptyStartTime);
		return false;
	}
	if ($('#end_date').val() == '') {
		alert(EmptyEndTime);
		return false;
	}
	var desc = tinyMCE.get('description').getContent();
	if (desc.length == 0) {
		alert(EmptyDescription);
		return false;
	}	 
	return true;
}
$(function(){
	document.getElementById("add_form").reset();
	$("#start_date").datepicker({
		showOn: 'button',
		buttonImage: '<?php echo static_url()?>images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-0:+10',
		onSelect: StartSelect
	});
	$("#end_date").datepicker({
		showOn: 'button',
		buttonImage: '<?php echo static_url()?>images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-0:+10',
		onSelect: EndSelect
	});
});
</script>
<?php endif; ?>
	<div class="userAlbumsList">
	
	
<!-- ***************************************************************************** -->
<?php if ($user_group === "admins" || $user_group === "moderators"):?>
<div>
	<form id="add_form" action="<?=base_url()?>competition/add" method="post" onsubmit="return AddSubmit();" style="overflow:auto;">
		<?php if (isset($comp_info_->competition_id)) {?>
		<input type="hidden" id="comp_id" name="comp_id" value="<?php echo $comp_info_->competition_id;?>" />
		<?php } ?>
		<div><?=lang('comp_new_comp')?></div>
		<div><input id="title" type="text" name="title" class="ent_area_zp_comp" <?php if (isset ($comp_info_->title)) echo "value = '$comp_info_->title'"?>/></div>
		<div><?=lang('comp_date_start')?></div>
		<div><input id="start_date" type="text" name="start_date" class="ent_area_zp_comp" readonly="readonly" <?php if (isset ($comp_info_->start_date )) echo "value = '" . date("Y-m-d", strtotime($comp_info_->start_date)) . "'"?>/></div>
		<div><?=lang('comp_date_end')?></div>
		<div><input id="end_date" type="text" name="end_date" class="ent_area_zp_comp" readonly="readonly" <?php if (isset ($comp_info_->end_date )) echo "value = '" . date("Y-m-d", strtotime($comp_info_->end_date)) . "'"?>/></div>
		<div><?=lang('comp_desc_comp')?></div>
		<div><textarea id="description" class="i" name="description" cols="" rows="" ><?php if (isset ($comp_info_->description)) echo $comp_info_->description ?></textarea></div>
		<div>
			<input type="reset" value="Отмена"/>
			<input type="submit" value="Применить" />
		</div>
	</form>
</div>



<!--<div id="comp_invisible_0">

<div class="roundedBlock"> <div class="tr"><div class="br"><div class="bl">
	<form id="add_form" action="<?=base_url()?>competition/add" method="post" onsubmit="return AddSubmit();" style="background:#373D41;overflow:auto;">
		<?php if (isset($comp_info_->competition_id)) {?>
		<input type="hidden" id="comp_id" name="comp_id" value="<?php echo $comp_info_->competition_id;?>" />
		<?php } ?>
		<h2><?=lang('comp_new_comp')?></h2>
							
		<span> <?php echo lang('comp_name_comp');?> 
		<input id="title" type="text" name="title" class="ent_area_zp_comp" <?php if (isset ($comp_info_->title)) echo "value = '$comp_info_->title'"?>/></span>

		<span> <?=lang('comp_date_start')?>			
		<input id="start_date" type="text" name="start_date" class="ent_area_zp_comp" readonly="readonly" <?php if (isset ($comp_info_->start_date )) echo "value = '" . date("Y-m-d", strtotime($comp_info_->start_date)) . "'"?>/>
		</span>
		
		<span> <?=lang('comp_date_end')?>						
		<input id="end_date" type="text" name="end_date" class="ent_area_zp_comp" readonly="readonly" <?php if (isset ($comp_info_->end_date )) echo "value = '" . date("Y-m-d", strtotime($comp_info_->end_date)) . "'"?>/>
		</span>
		
		<span> <?=lang('comp_desc_comp')?>
		<textarea id="description" class="i" name="description" cols="" rows="" ><?php if (isset ($comp_info_->description)) echo $comp_info_->description ?></textarea>
		</span>													
														
		<span>
		<input type="reset" value="" class="canc_butt_sp"/>
		<input type="submit" value="" class="ent_butt"/>
		</span>
		
	</form>
</div></div></div></div>

</div>-->
<?php endif;?>
<!-- ***************************************************************************** -->


</div>	
<script type="text/javascript" src="<?php echo base_url(); ?>static/js/tiny_mce/tiny_mce.js"></script> 
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		elements : "short_description, description",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
