<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>
<script type="text/javascript">
	

	function submitbutton (action)
	{
			
			if ( action == 'cancel' )
			{
				document.getElementById('action').value = action;
				document.getElementById('adminForm').submit(); 
				return;
			}

			var form = document.adminForm;
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
            var e = new RegExp("[0-9a-z_]+@[0-9a-z_^.]+\\.[a-z]{2,3}", 'i');
			// do field validation
			if ( trim( form.user_firstname.value ) == "" ) {
				alert( '<?php echo lang('user_valid_input_firstname');?>!' ); form.user_firstname.focus();
			}   else if  ( trim(form.user_lastname.value) == "" ) {
				alert ('<?php echo lang('user_valid_input_lastname');?>!'); form.user_lastname.focus();
			} else if ( form.user_login.value == "" ) {
				alert( '<?php echo lang('user_valid_input_login');?>!' ); form.user_login.focus();
			} else if ( r.exec(form.user_login.value) || form.user_login.value.length < 3 ) {
				alert( '<?php echo lang('user_valid_input_true');?>!' ); form.user_login.focus();
			} else if (!e.test(form.user_email.value) || trim(form.user_email.value) == "") {
				alert( '<?php echo lang('user_valid_input_email');?>!' ); form.user_email.focus();
			} else if (form.user_m_state.value == "none") {
				alert( '<?php echo lang('user_valid_input_m_state');?>!' ); 
			} else if (trim(form.user_password.value) != "" && form.user_password.value != form.user_confirm.value){
				alert( '<?php echo lang('user_valid_pass_no_equal');?>!' );
			} else if (form.user_group.value == "none") {
				alert( '<?php echo lang('user_valid_input_group');?>!' );
			} 
			 
			 else {
				document.getElementById('action').value = action;
				document.getElementById('adminForm').submit();
			}

	}
</script>


<div class="users_list">

<div style="text-align:center">

<span onclick="javascript:submitbutton('save');"><?php echo lang('user_save');?></span>
 |  <span onclick="document.getElementById('adminForm').reset();"><?php echo lang('user_reset');?></span>
 | <span onclick="javascript:submitbutton('cancel');" ><?php echo lang('user_cancel');?></span>
</div>
<br />

<?php echo form_open('admin/users', array('name'=>'adminForm', 'id' => 'adminForm'));?>

<table class="users_list" border="1" cellpadding="3" cellspacing="3" width="60%" align="center">

<tr>
<td width="40%"><?php echo lang('user_firstname');?>:</td>
<td><input type="text" name="user_firstname" id="user_firstname" value="<?php if (isset($user_info_->first_name)) echo $user_info_->first_name;?>" size="50" /></td>
</tr>

<tr>
<td width="20%"><?php echo lang('user_lastname');?>:</td>
<td><input type="text" name="user_lastname" id="user_lastname" value="<?php if (isset($user_info_->last_name)) echo $user_info_->last_name;?>" size="50" /></td>
</tr>

<tr>
<td><?php echo lang('user_login');?>:</td>
<td><input type="text" name="user_login" id="user_login" value="<?php if (isset($user_info_->login)) echo $user_info_->login; ?>" size="50" /></td>
</tr>

<tr>
<td><?php echo lang('user_email');?>:</td>
<td><input type="text" name="user_email" id="user_email" value="<?php if (isset($user_info_->email)) echo $user_info_->email;?>" size="50" /></td>
</tr>

<tr>
<td><?php echo lang('user_password');?>:</td>
<td><input type="password" name="user_password" id="user_password" value="" size="50" /></td>
</tr>

<tr>
<td><?php echo lang('user_pass_confirm');?>:</td>
<td><input type="password" name="user_confirm" id="user_confirm" value="" size="50" /></td>
</tr>

<tr>
<td><?php echo lang('user_moderation_state');?>:</td>
<td><select id="user_m_state" name="user_m_state">
<option value="none"><?php echo lang('user_select_type');?></option>
<?php
foreach ($moder_state as $key => $value)
{
	echo "<option value=\"$key\" ";
	if (  (isset($user_info_->moderation_state) ) && $key == $user_info_->moderation_state    )
		echo " selected ";
	
	echo ">".$value."</option>\n";
	
}
?>
</select></td>
</tr>

<tr>
<td><?php echo lang('user_group');?>:</td>
<td><select id="user_group" name="user_group" col="50">
<option value="none"><?php echo lang('user_select_group');?></option>
<?php
if  ($this->db_session->userdata['user_group'] == 'admins')
    $user_groups = array( "1" => "Users", "2" => "Registered Users", "3" => "Moderators", "4" => "Hired Moderators", "5" => "Administrators" ,"6" =>"Jury");
else
    $user_groups = array( "1" => "Users", "2" => "Registered Users", "3" => "Moderators", "4" => "Hired Moderators");
foreach ($user_groups as $key => $value)
{
	echo "<option value=\"$key\" ";
	if (  (isset($user_info_->group_id) ) && $key == $user_info_->group_id    )
		echo " selected ";
	
	echo ">".$value."</option>\n";
	
}
?>
</select></td>
</tr>

</table>


<input type="hidden" id="action" name="action" value="" />
<input type="hidden" id="form_key" name="form_key" value="key" />
<input type="hidden" id="user_id" name="user_id" value="<?php if (isset($user_info_->user_id)) echo $user_info_->user_id;?>" />
</form>

<br />
</div>