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
function set_places() {
  alert("Раздел в разработке");
}
function clear_places(ph_id) {
if (ph_id == 0) {
  conf_mes="Обнулять места?";
} else {conf_mes="Обнулить место для фото "+ph_id+" ?";}
if (confirm(conf_mes)) {
      ajax_actions_path="<?php echo base_url(); ?>gallery_mod/gallery_ctr/vote_action";
      $.post(ajax_actions_path, {
		action: "clear_places",
        photo_id:ph_id
		},
		function(data){
          data = window["eval"]("(" + data + ")");
          alert("Выполнено!");
          window.location.reload(false);
          if (data.err == '0') {
          }
          //$('#loader').hide();
		},
		'html'
	);
  }
}
</script>

<div class="users_list">

<div style="text-align:center">
 <a href="javascript:clear_places(0);" ><?php echo "Обнулить результаты";?></a>
 | <a href="javascript:set_places();"><?php echo "Утвердить результаты";?></a>
</div>

<br />

<?php echo form_open('admin/competitions', array('name'=>'compForm', 'id' => 'compForm'));?>

<table class="users_list" border="1" cellpadding="3" cellspacing="0" width="900" id="jTable">
	<thead>
		<tr style="background-color: rgb(102, 153, 255); color: rgb(255, 255, 255);">
			<th>#</th>
			<th align="center"><?php echo "Фото";?></th>
			<th align="center"><?php echo "Место";?></th>
			<th align="center"><?php echo "Жури";?></th>
			<th align="center"><?php echo "Комментарии Жури";?></th>
            <th align="center"><?php echo "Управление";?></th>
    	</tr>   
	</thead> 

<?php 
$status_row = 0;
foreach ($table_rez as $row):
$status_row++;  
$class_type = $status_row % 2 ? "row1" : "row2"; ?>
<tr class="<?php echo $class_type?>">
	<td align="center"><?php echo $status_row ?></td>

	<td align="center">
	<a class="ulink" href="<?= base_url()."photo/view/".$row->photo_id; ?>" >
	<?php
	if (!empty($row->photo_id))
	{
		$urlImg = photo_location().date("m", strtotime($row->date_added))."/".$row->photo_id."-sm".$row->extension	?>
	<img alt="<?=$row->title?>" border="0" src="<?=$urlImg?>"/>
	<?php } else echo lang('comp_opened')?>
	</a>
    <br>
    <?php echo $row->title ? $row->title : "&nbsp;"; ?>
	</td>
	<td align="center"><b><?= $row->vote; ?></b></td>
	<td align="center"><?php echo $row->login ? $row->login : "&nbsp;"; ?></td>
	<td align="center" valign="middle" ><a href="<?= base_url()."admin/comments/"; ?>">Комментарии:</a>
	<?php 
		// variant 1		
		$comments = modules::run('comments_mod/comments_ctr/get_object_comments_admin', 'photo', $row->photo_id, 0, 1, null, null, null, false);
		if(!empty($comments)) {
			$comments_str = "";
			foreach ($comments as $comment) {
				$comments_str .= "<div>".$comment->body."</div>";
			}
			echo $comments_str;
		} else {
			echo "Нет";
		}		
		// variant 2
		/*$comments_count = count(modules::run('comments_mod/comments_ctr/get_object_comments', 'photo', $row->photo_id, 0, 1, null, null, null, true));
		$comments_count = empty($comments_count) ? 0 : count($comments_count);
		echo $comments_count;*/
	?>
    </td>

    <td align="center" >
     <input type=button value="Обнулить место" onclick="clear_places(<?=$row->photo_id;?>)"><br><br>
    <!-- <input type=button value="Утвердить место" >      -->
    </td>
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

