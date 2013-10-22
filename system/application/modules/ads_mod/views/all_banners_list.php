<!-- begin all_users_list -->

<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>
<script type="text/javascript">
	
	function pre_submit (action)
	{
	if (document.adsForm.boxchecked.value == 0)
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
	document.getElementById('block').value = 'ads_details';
	
	document.getElementById('action').value = action;
	document.getElementById('adsForm').submit(); 
	}


	function checkAll( n, fldName ) {
		  if (!fldName) {
		     fldName = 'cb';
		  }
			var f = document.adsForm;
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
				document.adsForm.boxchecked.value = n2;
			} else {
				document.adsForm.boxchecked.value = 0;
			}
		}

		function listItemTask( id, task ) {
		    var f = document.adsForm;
		    cb = eval( 'f.' + id );
		    if (cb) {
		        for (i = 0; true; i++) {
		            cbx = eval('f.cb'+i);
		            if (!cbx) break;
		            cbx.checked = false;
		        } // for
		        cb.checked = true;
		        f.boxchecked.value = 1;
		        submitbutton(task);
		    }
		    return false;
		}

		function hideMainMenu()
		{
			document.adsForm.hidemainmenu.value=1;
		}

		function isChecked(isitchecked){
			if (isitchecked == true){
				document.adsForm.boxchecked.value++;
			}
			else {
				document.adsForm.boxchecked.value--;
			}
		}



</script>


<div class="users_list">

<div style="text-align:center">
<span onclick="javascript:pre_submit('edit')"><?php echo lang('user_edit');?></span> 
 | <span onclick="javascript:submitbutton('new')" ><?php echo lang('user_new');?></span> 
 | <span onclick="javascript: if (document.adsForm.boxchecked.value == 0) { alert('<?php echo lang('user_valid_select_any_user_for_delete');?>'); } else if (confirm('Are you sure you want to delete selected items? ')){ submitbutton('delete');}"><?php echo lang('user_delete');?></span>
</div>
<br />

<?php echo form_open('admin/ads', array('name'=>'adsForm', 'id' => 'adsForm'));?>

<table class="users_list" border="1" cellpadding="3" cellspacing="0" width="900">
<tr>
<td align="center">#</td>

<td align="center"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($table_rez)?>);" /></td>
<td align="center"><?php echo lang('preview');?> </td>
<td align="center"><?php echo lang('follow_link');?></td>
<td align="center"><?php echo lang('title_text');?></td>
<td align="center"><?php echo lang('alt_text');?></td>
<td align="center"><?php echo lang('active_state');?></td>
<td align="center"><?php echo lang('block_id');?></td>
<td align="center"><?php echo lang('description');?></td>

</tr>
<?php 
$status_row = 0; 
foreach ($table_rez as $row): 
$status_row++;  
$class_type = $status_row % 2 ? "row1" : "row2"; ?>
<tr class="<?php echo $class_type?>">
	<td align="center"><?php echo $status_row ?></td>
	
	<td align="center"><input type="checkbox" id="cb<?php echo ($status_row - 1); ?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
	
	<td>
	<a class="ulink" href="javascript:void(0)" onclick="javascript: isChecked( document.getElementById('cb<?php echo ($status_row - 1); ?>').checked = true ); pre_submit('edit'); ">
	
	<?php if (isset($row->file_type)) { ?>
	
	<?php  
	if ($row->file_type == 'string')
	 		echo $row->file_url;	 		
	if ($row->file_type != 'string') 
	{	
	?>
	<img alt="<?php echo $row->alt_text?>" title = "<?php echo $row->title?>" src="<?php echo base_url() . 'uploads/banners/' . $row->file_url; ?>">	
	<?php } } else echo "no image"?>
	</a>
	</td>
	
	<td><a class="ulink" target='_blank' href = "<?php echo $row->onclick_url ?>" ><?php echo $row->onclick_url ? $row->onclick_url: "&nbsp;"; ?> </a></td>
	<td><?php echo $row->title? $row->title : "&nbsp;"; ?></td>
	<td><?php echo $row->alt_text? $row->alt_text: "&nbsp;"; ?></td>
	<td align="center"><?php if ($row->active_state == '1') echo lang('user_yes'); else echo lang('user_no'); ?></td>
	<td><?php echo $row->block_id ? $row->block_id : "&nbsp;"; ?></td>
	<td><?php echo $row->description ? $row->description : "&nbsp;"; ?></td>	
</tr>

<?php  endforeach; ?>
</table>

<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" id="action" name="action" value="view" />
<input type="hidden" id="key" name="form_key" value="key" />
<input type="hidden" id="block" name="block" value="" />

<?php form_close();?>
<br />

</div>
<!-- end all_users_list -->

