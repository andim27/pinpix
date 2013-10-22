<?php include('head.php'); ?>
<link rel="stylesheet" type="text/css" href="/css/ui.all.css">
<script type="text/javascript" src="/static/js/jquery-ui-1.6.custom.min.js"></script>
<script type="text/javascript">
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
		action    : "set_album_filter",
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
				window.location.reload(true);
			}
	  }
	);
}
function ClearFilter() {
	$.post(url, {action: "clear_album_filter"}, function(data){
		if (data == '-1') {
			alert("Не получилось");
		} else {
			window.location.reload(true);
		}
	});
}
</script>
<body class="pix">
	
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
						<table border="1" cellpadding="0" cellspacing="0" width="850" style="table-layout:fixed;border-collapse:collapse;">
							<tr valign="center" align="center">
<!-- /* *************************************************** */ -->											
								<td width="110">
									<select id="flr_cat" style="width:105px;">
										<option<?=($album_filter['category'] == 0)?' selected="selected"':'';?> value="0">&nbsp;</option>
<?php foreach($categories as $category):?>
										<option<?=($album_filter['category'] == $category->id)?' selected="selected"':'';?> value="<?php echo $category->id;?>"><?php echo $category->name?></option>
<?php endforeach;?>
									</select>
								</td>
								<td width="110"><input value="<?=(!empty($album_filter['username']))?$album_filter['username']:'';?>" id="flr_usr" type="text" style="width:105px;"/></td>
								<td width="116"><input value="<?=(!empty($album_filter['title']))?$album_filter['title']:'';?>" id="flr_ttl" type="text" style="width:112px;"/></td>
								<td align="right" width="125" style="white-space:nowrap;padding-right:5px;">
									<?=lang('date_from')?><input value="<?=(!empty($album_filter['date']['start']))?date("d M Y", strtotime($album_filter['date']['start'])):'';?>" name="start" id="start" type="text" style="width:90px;"/><br/>
									<?=lang('date_till')?><input value="<?=(!empty($album_filter['date']['end']))?date("d M Y", strtotime($album_filter['date']['end'])):'';?>" name="end" id="end" type="text" style="width:90px;"/>
								</td>
								<td width="90">
									<select id="flr_mdr" style="width:88px;" name="moderation_state">
										<option<?=($album_filter['moderation'] == -999)?' selected="selected"':'';?> value="-999">&nbsp;</option>
										<option<?=($album_filter['moderation'] == MOD_NEW)?' selected="selected"':'';?> value="<?=MOD_NEW?>"><?=lang('mod_new');?></option>
										<option<?=($album_filter['moderation'] == MOD_APPROVED)?' selected="selected"':'';?> value="<?=MOD_APPROVED?>"><?=lang('mod_approved');?></option>
										<option<?=($album_filter['moderation'] == MOD_FEATURED)?' selected="selected"':'';?> value="<?=MOD_FEATURED?>"><?=lang('mod_featured');?></option>
										<option<?=($album_filter['moderation'] == MOD_DECLINED)?' selected="selected"':'';?> value="<?=MOD_DECLINED?>"><?=lang('mod_declined');?></option>
										<option<?=($album_filter['moderation'] == MOD_DELETED)?' selected="selected"':'';?> value="<?=MOD_DELETED?>"><?=lang('mod_deleted');?></option>
									</select>
								</td>
								<td width="64"><div style="width:60px;">&nbsp;</div></td>
								<td width="130" align="center">
									<input type="button" value="<?=lang('set_photo_filter')?>" onclick="SetFilter()"/>
									<input type="button" value="<?=lang('clear_photo_filter')?>" onclick="ClearFilter()"/>
								</td>
<!-- /* *************************************************** */ -->											
							</tr>
							<tr style="background-color:#373D41;color:#fff;text-align:center;">
								<td width="110"><?=lang('section');?></td>
								<td width="110"><?=lang('username');?></td>
								<td width="112"><?=lang('title');?></td>
								<td width="130">
									<?=lang('date_added');?>&nbsp;
									<a href="<?=base_url().'admin/albums/'.((strtolower($sort_order) == 'asc')?'desc':'asc').'/page/'.$cpage;?>">
										<img style="vertical-align: middle;" alt="" src="<?=static_url()?>/images/m_main_arrow<?=((strtolower($sort_order) == 'asc')?'':'_desc');?>.gif" />
									</a>
								</td>
								<td width="90"><?=lang('moderation_state');?></td>
								<td width="64"><?=lang('erotic');?></td>
								<td width="130"><?=lang('action');?></td>
							</tr>
<?php $i = 0; foreach($albums as $album):?>
							<tr valign="center" align="center" height="30"<?=(($i%2 == 1)?' style="background-color:#ccc;"':'');?>>
								<td><?=$album->catname?></td>
								<td><?=$album->username?></td>
								<td><?=$album->title?></td>
								<td><?=$album->date_added?></td>
								<form action="" method="post">
									<input type="hidden" name="album_id" value="<?=$album->album_id?>"/>
									
											<td width="90">
												<select name="moderation_state">
													<option value="<?=MOD_NEW?>" <?=(($album->moderation_state == MOD_NEW)?' selected="selected"':'');?>><?=lang('mod_new');?></option>
													<option value="<?=MOD_APPROVED?>" <?=(($album->moderation_state == MOD_APPROVED)?' selected="selected"':'');?>><?=lang('mod_approved');?></option>
													<option value="<?=MOD_FEATURED?>" <?=(($album->moderation_state == MOD_FEATURED)?' selected="selected"':'');?>><?=lang('mod_featured');?></option>
													<option value="<?=MOD_DECLINED?>" <?=(($album->moderation_state == MOD_DECLINED)?' selected="selected"':'');?>><?=lang('mod_declined');?></option>
													<option value="<?=MOD_DELETED?>" <?=(($album->moderation_state == MOD_DELETED)?' selected="selected"':'');?>><?=lang('mod_deleted');?></option>
												</select>
											</td>
											<td width="64">
												<select name="erotic">
													<option value="0"<?=(($album->erotic_p == 0)?' selected="selected"':'');?>><?=lang('no');?></option>
													<option value="1"<?=(($album->erotic_p == 1)?' selected="selected"':'');?>><?=lang('yes');?></option>
												</select>
											</td>
											<td width="130" align="center">
												<input type="submit" value="<?=lang('btn_ok')?>" />
												<input type="reset" value="<?=lang('btn_cancel')?>" />
											</td>
										
								</form>
							</tr>
<?php $i++; endforeach;?>
						</table>
						<?php echo paginate($paginate_args); ?>
					</div>
  					
					
				</div>
				<!--End Center main-->
				   
				<br /><br /><br /><br />
			</div>
			<!-- end #main_cont_body -->
		   
		</div>   
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>
		
	</div>
	<!-- end #pix -->

</body>
</html>