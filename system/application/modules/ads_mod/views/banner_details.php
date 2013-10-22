<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>
<script type="text/javascript">
function submitbutton (action)
{
	
		if ( action == 'cancel' )
		{
			document.getElementById('action').value = action;
			document.getElementById('adsForm').submit(); 
			return;
		}	 
		else 
		{
			document.getElementById('action').value = action;
			document.getElementById('adsForm').submit(); 
		}

}

function unable_fields(val)
{
	if (val == 1)
	{
		document.getElementById ("file_url").disabled = false;
		document.getElementById ("userfile").disabled = true;
		document.getElementById ("onclick_url").disabled = true;
		document.getElementById ("title").disabled = true;
		document.getElementById ("alt_text").disabled = true;
	}
	else
	{
		document.getElementById ("file_url").disabled = true;
		document.getElementById ("userfile").disabled = false;
		document.getElementById ("onclick_url").disabled = false;
		document.getElementById ("title").disabled = false;
		document.getElementById ("alt_text").disabled = false;
	}		
};

</script>


<div class="users_list">

<div style="text-align:center">

<span onclick="javascript:submitbutton('save');"><?php echo lang('user_save');?></span>
 | <span onclick="document.getElementById('adsForm').reset();"><?php echo lang('user_reset');?></span>
 | <span onclick="javascript:submitbutton('cancel');" ><?php echo lang('user_cancel');?></span>
</div>
<br />

<form name="adsForm" id = "adsForm" enctype="multipart/form-data" action="<?=base_url() . 'admin/ads'?>" method="post">

<table class="users_list" border="1" cellpadding="3" cellspacing="3" width="700px" align="center">
<tr>
<td colspan="3">
<?php echo lang('check_way');?>: 
</td>

<tr>
<td colspan="2"><br>
<?php echo lang('file_img');?>
<input type = "radio" name="way" value="0" onclick="unable_fields(0);" checked="checked" >
</td>
<td>
<?php echo lang('file_url');?>
<input type = "radio" name="way" value="1" onclick="unable_fields(1);" <?php if (isset($banner_info_->file_type)&&($banner_info_->file_type=='string')) echo  "checked='checked'" ?>>
</td>
</tr>
<tr>
<td width="30%" ><?php echo lang('file_img');?>: 
		
</td>
<td>
	 <input name="userfile" id="userfile"  type="file" /> 		
</td>
<td width="30%"  rowspan="4">
<?php echo lang('file_url');?>: 
	<textarea name="file_url" id="file_url" rows="" cols="" style = "width:100%; height: 100px"  /><?php if (isset($banner_info_->file_url)) if (isset($banner_info_->file_type)&&($banner_info_->file_type=='string')) echo $banner_info_->file_url; ?></textarea>
</td>
</tr>

<tr>
<td width="30%" ><?php echo lang('onclick_url');?>:</td>
<td><input type="text"  name="onclick_url" id="onclick_url" value="<?php if (isset($banner_info_->onclick_url)) echo $banner_info_->onclick_url;?>" size="30" /></td>
</tr>

<tr>
<td width="30%" ><?php echo lang('title_text');?>:</td>
<td><input type="text" name="title" id="title" value="<?php if (isset($banner_info_->title)) echo $banner_info_->title;?>" size="30" /></td>
</tr>

<tr>
<td width="30%" ><?php echo lang('alt_text');?>:</td>
<td><input type="text" name="alt_text" id="alt_text" value="<?php if (isset($banner_info_->alt_text)) echo $banner_info_->alt_text;?>" size="30" /></td>
</tr>

<tr>
<td colspan="3" align="center">
<?php echo lang('common_fields');?>:
</td>
</tr>

<tr>
<td width="30%" ><?php echo lang('is_active');?>:</td>
<td colspan="2"><input type="checkbox" name="active_state" id="active_state" <?php if (isset($banner_info_->active_state) && ($banner_info_->active_state == 1 )) echo "checked='checked'" ; ?>/></td>
</tr>

<tr>
<td width="30%" ><?php echo lang('block_id');?>:</td>
<td colspan="2"><select id="block_id" name="block_id">
<option value="none"><?php echo lang('select_block');?></option>
<?php
foreach ($ads_block_ids as $key => $value)
{
	echo "<option value=\"".$key."\" ";
	if (  (isset($banner_info_->block_id) ) && $key == $banner_info_-> block_id)
		echo " selected ";
	
	echo ">".strtoupper($value)."</option>\n";
	
}
?>
</select></td>
</tr>

<tr>
<td width="30%" ><?php echo lang('description');?>:</td>
<td colspan="2">
<textarea name="description" id="description" rows="" cols="" style = "width:100%; height: 100px"  /><?php if (isset($banner_info_->description)) echo $banner_info_->description; ?></textarea>
</td>
</tr>

</table>


<input type="hidden" id="action" name="action" value="" />
<input type="hidden" id="form_key" name="form_key" value="key" />
<input type="hidden" id="banner_id" name="banner_id" value="<?php if (isset($banner_info_->id)) echo $banner_info_->id;?>" />
</form>

<br />
</div>
<?php if (isset($banner_info_->file_type)&&($banner_info_->file_type=='string'))
$val = 1; else $val = 0;
?>

<script type="text/javascript">
unable_fields(<?php echo $val ?>);
</script>

