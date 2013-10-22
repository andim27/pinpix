<!-- begin all_users_list -->
<link rel="stylesheet" type="text/css" href="<?php echo static_url(); ?>css/ui.all.css">
<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery-ui-1.6.custom.min.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>
<script type="text/javascript">
	

	function pre_submit (action)
	{
	if (document.adminForm.boxchecked.value == 0)
		{ 
		alert('<?php echo lang('user_valid_select_any_user');?>!');
		}
	else {
		submitbutton(action);
		 }	
	}

	function submitbutton (action)
	{ 
	if (action == 'new' || action== 'edit')
	document.getElementById('block').value = 'user_details';
	
	document.getElementById('action').value = action;
	document.getElementById('adminForm').submit(); 
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
			buttonImage: '<?php echo static_url(); ?>images/calendar.png',
			buttonImageOnly: true,
			dateFormat: 'dd M yy',
			duration: '',
			yearRange: '-10:+10',
			onSelect: StartSelect
		});
		$("#end").datepicker({
			showOn: 'button',
			buttonImage: '<?php echo static_url(); ?>images/calendar.png',
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
			action     : "set_user_filter",
			login      : $("#flr_login").val(),
			username   : $("#flr_usr_name").val(),
			usersname  : $("#flr_usr_sname").val(),
			useremail  : $("#flr_email").val(),
			date_from  : $("#start").val(),
			date_till  : $("#end").val(),
			moderation : $("#flr_mdr").val()},
		  function(data){
				if (data == '-1') {
					alert("Не получилось");
				} else {
					window.location = "<?php echo base_url().'admin/users/';?>";
				}
		  }
		);
	}
	function ClearFilter() {
		$.post(url, {action: "clear_user_filter"}, function(data){
			if (data == '-1') {
				alert("Не получилось");
			} else {
				window.location.reload(true);
			}
		});
	}
	
	
</script>


<div class="users_list">

<div style="text-align:center">
<!-- span onclick="javascript:pre_submit('logout')"><?php echo lang('user_logout');?></span>
 | --><span onclick="javascript:pre_submit('edit')"><?php echo lang('user_edit');?></span> 
 | <span onclick="javascript:submitbutton('new')" ><?php echo lang('user_new');?></span> 
 <!--  | <span onclick="javascript: if (document.adminForm.boxchecked.value == 0) { alert('<?php //echo lang('user_valid_select_any_user_for_delete');?>'); } else if (confirm('Are you sure you want to delete selected items? ')){ submitbutton('delete');}"><?php //echo lang('user_delete');?></span>  -->
</div>
<br />

<?php echo form_open('admin/users', array('name'=>'adminForm', 'id' => 'adminForm'));?>

<table class="users_list" border="1" cellpadding="3" cellspacing="0" width="900" id="jjTable">
<!-- /* *************************************************** */ -->	
<tr align="center">
	<td>&nbsp;</td>									
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><input value="<?=(!empty($user_filter['login']))?$user_filter['login']:'';?>" id="flr_login" type="text"/></td>
	<td align="right" width="125" style="white-space:nowrap;padding-right:5px;">
		<?=lang('date_from')?><input value="<?=(!empty($photo_filter['date']['start']))?date("d M Y", strtotime($photo_filter['date']['start'])):'';?>" name="start" id="start" type="text" style="width:80px;"/><br/>
		<?=lang('date_till')?><input value="<?=(!empty($photo_filter['date']['end']))?date("d M Y", strtotime($photo_filter['date']['end'])):'';?>" name="end" id="end" type="text" style="width:80px;"/>
	</td>
	<td><input value="<?=(!empty($user_filter['user_email']))?$user_filter['user_email']:'';?>" id="flr_email" type="text"/></td>
	<td>
		<select id="flr_mdr" style="width:88px;" name="flr_mdr">
			<option<?=($user_filter['moderation'] == -999)?' selected="selected"':'';?> value="-999">&nbsp;</option>
			<option<?=($user_filter['moderation'] == MOD_NEW)?' selected="selected"':'';?> value="<?=MOD_NEW?>"><?=lang('mod_new');?></option>
			<option<?=($user_filter['moderation'] == MOD_APPROVED)?' selected="selected"':'';?> value="<?=MOD_APPROVED?>"><?=lang('mod_approved');?></option>
			<option<?=($user_filter['moderation'] == MOD_FEATURED)?' selected="selected"':'';?> value="<?=MOD_FEATURED?>"><?=lang('mod_featured');?></option>
			<option<?=($user_filter['moderation'] == MOD_DECLINED)?' selected="selected"':'';?> value="<?=MOD_DECLINED?>"><?=lang('mod_declined');?></option>
			<option<?=($user_filter['moderation'] == MOD_DELETED)?' selected="selected"':'';?> value="<?=MOD_DELETED?>"><?=lang('mod_deleted');?></option>
		</select>
	</td>	
	<td><input size="10px" value="<?=(!empty($user_filter['user_name']))?$user_filter['user_name']:'';?>" id="flr_usr_name" type="text"/></td>
	<td><input size="10px" value="<?=(!empty($user_filter['user_sname']))?$user_filter['user_sname']:'';?>" id="flr_usr_sname" type="text"/></td>
		<td width="130" align="center">
		<input type="button" value="<?=lang('set_photo_filter')?>" onclick="SetFilter()"/>
		<input type="button" value="<?=lang('clear_photo_filter')?>" onclick="ClearFilter()"/>
	</td>								
</tr>
<!-- /* *************************************************** */ -->		
<tr>
	<th align="center">#</th>
	<th align="center"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($table_rez)?>);" /></th>
	<th align="center"><?php echo lang('user_id');?></th>
	<th align="center"><?php echo lang('user_login');?></th>
	<th align="center"><?php echo lang('user_loggedIn');?></th>
	<th align="center"><?php echo lang('user_email');?></th>
	<th align="center"><?php echo lang('user_moderation_state');?></th>
	<th align="center"><?php echo lang('user_firstname');?></th>
	<th align="center"><?php echo lang('user_lastname');?></th>
	<th align="center"><?php echo lang('user_group');?></th>
</tr>

<?php 
$status_row = 0; 
foreach ($table_rez as $row): 
$status_row++;  
$class_type = $status_row % 2 ? "row1" : "row2"; ?>
<tr class="<?php echo $class_type?>">
	<td align="center"><?php echo $status_row + $paginate_args['per_page'] * ($paginate_args['cur_page'] -1 ) ?></td>
	<td align="center"><input type="checkbox" id="cb<?php echo ($status_row - 1); ?>" name="cid[]" value="<?php echo $row->user_id; ?>" onclick="isChecked(this.checked);" /></td>
	<td align="center"><?php echo $row->user_id; ?></td>
	<td><a class="ulink" href="javascript:void(0)" onclick="javascript: isChecked( document.getElementById('cb<?php echo ($status_row - 1); ?>').checked = true ); pre_submit('edit'); "><?php echo $row->login; ?></a></td>
	<td align="center"><?php echo $row->registration_date ? $row->registration_date : "&nbsp;";  ?></td>	
	<td><?php echo $row->email ? $row->email : " &nbsp; " ; ?></td>
	<td><?php echo $moder_state[$row->moderation_state]; ?></td>
	<td><?php echo $row->first_name ? $row->first_name : "&nbsp;"; ?></td>
	<td><?php echo $row->last_name ? $row->last_name : "&nbsp;"; ?></td>
	<td><?php echo $row->name; ?></td>
</tr>

<?php  endforeach; ?>
</table>

<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" id="action" name="action" value="view" />
<input type="hidden" id="key" name="form_key" value="key" />
<input type="hidden" id="block" name="block" value="" />

<?php form_close();?>
<br />

<?php echo paginate($paginate_args); ?>
</div>
<!-- end all_users_list -->