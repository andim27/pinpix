<!-- begin all_users_list -->
<script type="text/javascript" src="<?php echo static_url(); ?>js/tablesorter/jquery-latest.js"></script> 
<script type="text/javascript" src="<?php echo static_url(); ?>js/tablesorter/jquery.tablesorter.js"></script> 

<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>
<script type="text/javascript">
	
	function pre_submit (action)
	{
	if (document.compForm.boxchecked.value == 0)
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
		document.getElementById('block').value = 'comp_details';
		
		document.getElementById('action').value = action;
		document.getElementById('compForm').submit(); 
	}


	function checkAll( n, fldName ) {
		  if (!fldName) {
		     fldName = 'cb';
		  }
			var f = document.compForm;
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
				document.compForm.boxchecked.value = n2;
			} else {
				document.compForm.boxchecked.value = 0;
			}
		}

		function listItemTask( id, task ) {
		    var f = document.compForm;
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
			document.compForm.hidemainmenu.value=1;
		}

		function isChecked(isitchecked){
			if (isitchecked == true){
				document.compForm.boxchecked.value++;
			}
			else {
				document.compForm.boxchecked.value--;
			}
		}
function set_estimate(el) {
 ajax_actions_path="<?php echo base_url(); ?>competition_mod/competition_ctr/ajax_actions";
 $.post(ajax_actions_path, {
		action: "estimate",
        id: el.value
		},
		function(data){
          data = window["eval"]("(" + data + ")");
          alert("Установлено!"+data.mes);
          if (data.err == '0') {
          }
          //$('#loader').hide();
		},
		'html'
	);
}
</script>

<div class="users_list">

<div style="text-align:center">
<span onclick="javascript:pre_submit('edit')"><?php echo lang('user_edit');?></span>
 | <span onclick="javascript:submitbutton('new')" ><?php echo lang('user_new');?></span>
 | <span onclick="javascript: if (document.compForm.boxchecked.value == 0) { alert('<?php echo lang('user_valid_select_any_user_for_delete');?>'); } else if (confirm('Are you sure you want to delete selected items? ')){ submitbutton('delete');}"><?php echo lang('user_delete');?></span>
</div>
<br />

<?php echo form_open('admin/competitions', array('name'=>'compForm', 'id' => 'compForm'));?>

<table class="users_list" border="1" cellpadding="3" cellspacing="0" width="900" id="jTable">
	<thead>
		<tr>
			<th>#</th>
			<th align="center"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($table_rez)?>);" /></th>
			<th align="center"><?php echo lang('comp_leader');?> </th>
			<th align="center"><?php echo lang('comp_title');?></th>
			<th align="center"><?php echo lang('comp_description');?></th>
			<th align="center"><?php echo lang('comp_start_date');?></th>
			<th align="center"><?php echo lang('comp_end_date');?></th>
			<th align="center"><?php echo lang('comp_view_photos');?></th>
           	<th align="center"><?php echo "Оценивать" ?></th>
    	</tr>   
	</thead> 

<?php 
$status_row = 0; 
foreach ($table_rez as $row): 
$status_row++;  
$class_type = $status_row % 2 ? "row1" : "row2"; ?>
<tr class="<?php echo $class_type?>">
	<td align="center"><?php echo $status_row ?></td>	
	<td align="center"><input type="checkbox" id="cb<?php echo ($status_row - 1); ?>" name="cid[]" value="<?php echo $row->competition_id; ?>" onclick="isChecked(this.checked);" /></td>	
	<td>
	<a class="ulink" href="javascript:void(0)" onclick="javascript: isChecked( document.getElementById('cb<?php echo ($status_row - 1); ?>').checked = true ); pre_submit('edit'); ">
	<?php 
	if (!empty($row->photo_id))
	{
		$urlImg = photo_location().date("m", strtotime($row->date_added))."/".$row->photo_id."-sm".$row->extension	?>
	<img alt="<?=$row->ptitle?>" border="0" src="<?=$urlImg?>"/>
	<?php } else echo lang('comp_opened')?>
	</a>
	</td>
	<td><?php echo $row->title ? $row->title : "&nbsp;"; ?></td>
	<td><?php echo $row->description ? $row->description : "&nbsp;"; ?></td>	
	<td><?php echo $row->start_date ? date("Y-m-d", strtotime($row->start_date)) : "&nbsp;"; ?></td>
	<td><?php echo $row->end_date ? date("Y-m-d", strtotime($row->end_date)): "&nbsp;"; ?></td>
	<td align="center"><a href = "<?php echo url('admin_comp_photos_url', $row->competition_id)?>"><?php echo lang('comp_view_photos');?></a></td>
    <td align="center"><input type="radio" name="comp_estimate" value=<?= $row->competition_id; ?> onclick="set_estimate(this);" /></td>
</tr>

<?php  endforeach; ?>
</table>
<script>
$(document).ready(function() 
    { 
        $("#jTable").tablesorter(); 
    } 
); 
</script>

<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" id="action" name="action" value="view" />
<input type="hidden" id="key" name="form_key" value="key" />
<input type="hidden" id="block" name="block" value="" />

<?php form_close();?>
<br />

</div>
<!-- end all_users_list -->

