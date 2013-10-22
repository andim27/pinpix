<?php include('head.php'); ?>
<body class="pix">
<link rel="stylesheet" type="text/css" href="<?php echo static_url(); ?>/css/ui.all.css">
<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery-ui-1.6.custom.min.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>
<script type="text/javascript">
function checkAll( n, fldName ) {
	  if (!fldName) {
	     fldName = 'cb';
	  }
		var f = document.phForm;

		var c = f.toggle.checked;
		var n2 = 0;
		for (i=0; i < n; i++) {
			cb = eval( 'f.' + fldName + '' + i );
			if (cb) {
				cb.checked = c;
				n2++;
			}
		}
		if (c) {
			document.phForm.boxchecked.value = n2;
		} else {
			document.phForm.boxchecked.value = 0;
		}
	}

function submitbutton (action)
{
if (action == 'new' || action== 'edit')
document.getElementById('block').value = 'user_details';

document.getElementById('action').value = action;
document.getElementById('phForm').submit();
}

function isChecked(isitchecked){
	if (isitchecked == true){
		document.phForm.boxchecked.value++;
	}
	else {
		document.phForm.boxchecked.value--;
	}
}

function StartSelect(dateText) {
	$('#end').datepicker('option', 'minDate', new Date(dateText));
	if ($('#end').val() == '') {
		$('#end').val(dateText);
		return;
	}
	var Start = new Date(dateText).getTime();
	var End = new Date($('#end').val()).getTime();
	if (End < Start) {
		$('#end').val(dateText);
	}
}
function EndSelect(dateText) {
	$('#start').datepicker('option', 'maxDate', new Date(dateText));
	if ($('#start').val() == '') {
		$('#start').val(dateText);
		return;
	}
	var Start = new Date($('#start').val()).getTime()
	var End = new Date(dateText).getTime();
	if (End < Start) {
		$('#start').val(dateText);
	}
}
$(function(){
	$("#start").datepicker({
		showOn: 'button',
		buttonImage: '/css/images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-10:+10',
		onSelect: StartSelect
	});
	$("#end").datepicker({
		showOn: 'button',
		buttonImage: '/css/images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-10:+10',
		onSelect: EndSelect
	});
});
var url = "<?=	base_url()?>admin/ajax_actions";
function SetFilter() {
	$.post(url, {
		action    : "set_photo_filter",
		category  : $("#flr_cat").val(),
		username  : $("#flr_usr").val(),
		title     : $("#flr_ttl").val(),
		date_from : $("#start").val(),
		date_till : $("#end").val(),
		moderation: $("#flr_mdr").val()},
	  function(data){
			if (data == '-1') {
				alert("Не получилось");
			} else {
				window.location = "<?php echo base_url().'admin/photos/';?>";
			}
	  }
	);
}
function ClearFilter() {
	$.post(url, {action: "clear_photo_filter"}, function(data){
		if (data == '-1') {
			alert("Не получилось");
		} else {
			window.location.reload(true);
		}
	});
}
function Editor(photo_id) {
  window.location.href="<?php echo base_url().'admin/editor/'?>"+photo_id+"/";
}
</script>
	<div id="pix">

		<?php include('header.php'); ?>

		<?php display_errors(); ?>

		<div id="mainContent">

			<div id="main_side_right">

				<!-- start main_enter_box -->
				<?php if ( ! $user_id) echo $auth_block; ?>
				<!-- end main_enter_box -->
			        
				<!-- Start Main_side_und_box-->
				<div style="display:none;" id="main_side_und_box" class="nifty">

					
					   
					<!--Center-->
					<div id="ms_center">
						<div id="ms_cen_zag" align="left"><span class="m_zag_txt"></span></div>
						<div id="ms_cen_box">
							<?php // include('admin_menu.php'); ?>
						</div>
					</div>
					<!-- End center-->
					  
				
				  
				</div>   
				<!-- End Main_side_und_box-->
			   
			</div>  
			<!-- End main_side_right -->
			   
			<!-- main_cont_body -->
			<div id="main_cont_body">
			   	
			   	<!-- Start center main-->
				<div id="c_box">
				
								
					<div style="color:#373D41;font-family:Verdana,Geneva,sans-serif;font-size:11px;">
					<form action="" method="post" name = "phForm" id = "phForm">
					
						<table border="1" cellpadding="0" cellspacing="0" width="900" style="table-layout:fixed;border-collapse:collapse;">
							<tr align="center">
<!-- /* *************************************************** */ -->	
								<td width="20">&nbsp;</td>									
								<td width="150"><div style="width:150px;">&nbsp;</div></td>
								<td width="110">
									<select id="flr_cat" style="width:105px;">
										<option <?=($photo_filter['category'] == 0)?' selected="selected"':'';?> value="0">&nbsp;</option>
										<?php foreach($categories as $category):?>
										<option <?=($photo_filter['category'] == $category->id)?' selected="selected"':'';?> value="<?php echo $category->id;?>"><?php echo $category->name?></option>
										<?php endforeach;?>
									</select>
								</td>
								<td width="110"><input value="<?=(!empty($photo_filter['username']))?$photo_filter['username']:'';?>" id="flr_usr" type="text" style="width:105px;"/></td>
								<td width="116"><input value="<?=(!empty($photo_filter['title']))?$photo_filter['title']:'';?>" id="flr_ttl" type="text" style="width:112px;"/></td>
								<td align="right" width="125" style="white-space:nowrap;padding-right:5px;">
									<?=lang('date_from')?><input value="<?=(!empty($photo_filter['date']['start']))?date("d M Y", strtotime($photo_filter['date']['start'])):'';?>" name="start" id="start" type="text" style="width:80px;"/><br/>
									<?=lang('date_till')?><input value="<?=(!empty($photo_filter['date']['end']))?date("d M Y", strtotime($photo_filter['date']['end'])):'';?>" name="end" id="end" type="text" style="width:80px;"/>
								</td>
								<td width="120">
									<select id="flr_mdr" style="width:88px;" name="flr_mdr">
										<option <?=($photo_filter['moderation'] == -999)?' selected="selected"':'';?> value="-999">&nbsp;</option>
										<option <?=($photo_filter['moderation'] == MOD_NEW)?' selected="selected"':'';?> value="<?=MOD_NEW?>"><?=lang('mod_new');?></option>
										<option <?=($photo_filter['moderation'] == MOD_APPROVED)?' selected="selected"':'';?> value="<?=MOD_APPROVED?>"><?=lang('mod_approved');?></option>
										<option <?=($photo_filter['moderation'] == MOD_FEATURED)?' selected="selected"':'';?> value="<?=MOD_FEATURED?>"><?=lang('mod_featured');?></option>
                                        <option <?=($photo_filter['moderation'] == MOD_FEATURED_MAIN)?' selected="selected"':'';?> value="<?=MOD_FEATURED_MAIN?>"><?=lang('mod_featured');?> для главной</option>
										<option <?=($photo_filter['moderation'] == MOD_DECLINED)?' selected="selected"':'';?> value="<?=MOD_DECLINED?>"><?=lang('mod_declined');?></option>
										<option <?=($photo_filter['moderation'] == MOD_DELETED)?' selected="selected"':'';?> value="<?=MOD_DELETED?>"><?=lang('mod_deleted');?></option>
									</select>
								</td>
								<td width="64"><div style="width:60px;">&nbsp;</div></td>
								<td width="130" align="center">
									<input type="button" value="<?=lang('set_photo_filter')?>" onclick="SetFilter()"/>
									<input type="button" value="<?=lang('clear_photo_filter')?>" onclick="ClearFilter()"/>
								</td>										
							</tr>
							<!-- /* *************************************************** */ -->								
						
							<tr style="background-color:#373D41;color:#fff;text-align:center;">
								
								<td width="20">&nbsp;</td>	
								<td width="150"><?=lang('preview');?></td>						
								<td width="110"><?=lang('section');?></td>
								<td width="110"><?=lang('username');?></td>
								<td width="112"><?=lang('title');?></td>
								<td width="130">
									<?=lang('date_added');?>&nbsp;
									<a href="<?=base_url().'admin/photos/'.((strtolower($sort_order) == 'asc')?'desc':'asc').'/page/'.$cpage;?>">
										<img style="vertical-align: middle;" alt="" src="<?=static_url()?>/images/m_main_arrow<?=((strtolower($sort_order) == 'asc')?'':'_desc');?>.gif" />
									</a>
								</td>
								<td width="90"><?=lang('moderation_state');?></td>
								<td width="64"><?=lang('erotic');?></td>
								<td width="130"><?=lang('action');?></td>
							</tr>
							<?php $i=0; foreach($photos as $photo):?>
						
							<tr align="center" height="150"<?=(($i%2 == 1)?' style="background-color:#ccc;"':'');?>>
								
								<td width="20"><input type="checkbox" id="cb<?php echo ($i); ?>" name="cid[]" value="<?php echo $photo->photo_id; ?>" onclick="isChecked(this.checked);" /> </td>	
								<form action="" method="post">
								<input type="hidden" name="photo_id" value="<?=$photo->photo_id?>"/>
								<input type="hidden" name="old_mod_state" value="<?=$photo->moderation_state?>"/>
								<td style="text-align:center;">
									<?php $urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension	?>
									<a style = "text-decoration:none; background-color:white;" onfocus="this.blur()" href = "<?php echo base_url()  . 'photo/view/' . $photo->photo_id ?>">
									<img border="0" alt="" src="<?=$urlImg ?>" />
									</a>
                                    <?php if (($photo->lg_width > 975 ) && ($photo->moderation_state == MOD_FEATURED_MAIN)): ?>
                                        <input type="button" value="Редактор" title="Редактировать фото (<?=$photo->photo_id;?>) для шапки" onclick="Editor(<?=$photo->photo_id;?>);"/>
                                     <?php endif; ?>
								</td>
								<td><?=$photo->catname?></td>
								<td><?=$photo->username?></td>
								<td style = "overflow: hidden;"><?=$photo->title;?></td>
								<td><?=$photo->date_added?></td>														
											<td width="90">
												<select name="moderation_state">
													<option value="<?=MOD_NEW?>" <?=(($photo->moderation_state == MOD_NEW)?' selected="selected"':'');?>><?=lang('mod_new');?></option>
													<option value="<?=MOD_APPROVED?>" <?=(($photo->moderation_state == MOD_APPROVED)?' selected="selected"':'');?>><?=lang('mod_approved');?></option>
													<option value="<?=MOD_FEATURED?>" <?=(($photo->moderation_state == MOD_FEATURED)?' selected="selected"':'');?>><?=lang('mod_featured');?></option>
                                                    <option value="<?=MOD_FEATURED_MAIN?>" <?=(($photo->moderation_state == MOD_FEATURED_MAIN)?' selected="selected"':'');?>>Для главной</option>
                                                    <option value="<?=MOD_DECLINED?>" <?=(($photo->moderation_state == MOD_DECLINED)?' selected="selected"':'');?>><?=lang('mod_declined');?></option>
													<option value="<?=MOD_DELETED?>" <?=(($photo->moderation_state == MOD_DELETED)?' selected="selected"':'');?>><?=lang('mod_deleted');?></option>
												</select>
											</td>
											<td width="64">
												<select name="erotic">
													<option value="0"<?=(($photo->erotic_p == 0)?' selected="selected"':'');?>><?=lang('no');?></option>
													<option value="1"<?=(($photo->erotic_p == 1)?' selected="selected"':'');?>><?=lang('yes');?></option>
												</select>
											</td>
											<td width="130" align="center">
												<input type="submit" value="<?=lang('btn_ok')?>" />
												<input type="reset" value="<?=lang('btn_cancel')?>" />

											</td>
										</form>	
								
							</tr>
							<?php $i++;endforeach;?>
							<!-- /* *************************************************** */ -->		
				
							<tr style="text-align:center;">								
								<td width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($photos)?>);" /></td>
								<td width="612" colspan="5"> <input type="button" value="Установить состояние для всех выбранных"  onclick = "javascript: if (document.phForm.boxchecked.value == 0) { alert('Не выбрано ни одно фото из списка!'); } else {submitbutton('setAll');}" ></td>
								<td width="90">	
									<select id="ms_all" style="width:88px;" name="ms_all">
										<option <?=($photo_filter['moderation'] == MOD_NEW)?' selected="selected"':'';?> value="<?=MOD_NEW?>"><?=lang('mod_new');?></option>
										<option <?=($photo_filter['moderation'] == MOD_APPROVED)?' selected="selected"':'';?> value="<?=MOD_APPROVED?>"><?=lang('mod_approved');?></option>
										<option <?=($photo_filter['moderation'] == MOD_FEATURED)?' selected="selected"':'';?> value="<?=MOD_FEATURED?>"><?=lang('mod_featured');?></option>
                                        <option <?=($photo_filter['moderation'] == MOD_FEATURED_MAIN)?' selected="selected"':'';?> value="<?=MOD_FEATURED_MAIN?>"><?=lang('mod_featured');?> для главной</option>
										<option <?=($photo_filter['moderation'] == MOD_DECLINED)?' selected="selected"':'';?> value="<?=MOD_DECLINED?>"><?=lang('mod_declined');?></option>
										<option <?=($photo_filter['moderation'] == MOD_DELETED)?' selected="selected"':'';?> value="<?=MOD_DELETED?>"><?=lang('mod_deleted');?></option>
									</select>
								</td>
								<td width="64">&nbsp;</td>
								<td width="130">&nbsp;</td>
							</tr>
						
					
<!-- /* *************************************************** */ -->		
						</table>
						<input type="hidden" name="boxchecked" value="0" />
						<input type="hidden" id="action" name="action" value="" />		
					</form>
						<?php echo paginate($paginate_args); ?>
					</div>
  					
					
				</div>
				<!--End Center main-->
				   
				<br /><br /><br /><br />
			</div>
			<!-- end #main_cont_body -->
		   
		</div>   
		<!-- end #mainContent -->
		
	</div>
	<!-- end #pix -->

</body>
</html>