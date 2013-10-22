<?php if ($list) : ?>
		<table  id="show_tag" cellpadding="0" cellspacing="0" width="540" border="1">
			<caption><b><?php echo lang('tag_tags'); ?></b></caption>
			<tr align="center" style="background-color: #6699FF; color: #FFFFFF;">
				<td><b><?php echo lang('tag_name'); ?></b></td>
				<td><b><?php echo lang('tag_count'); ?></b></td>
				<td><b><?php echo lang('tag_state'); ?></b></td>
				<td><b><?php echo lang('tag_update'); ?></b></td>
				<td><b><?php echo lang('tag_delete'); ?></b></td>
			</tr>
		<?php foreach ($list as $row) : ?>
			<tr align="center">
				<td width="100"><?php echo $row->tag; ?></td>
				<td width="100"><?php echo $row->tag_count; ?></td>
				<td width="120">
					<select id="state_<?php echo $row->tag_id; ?>" size="1" style="width:100px;">
					<?php foreach ($states as $key=>$value) : ?>
							<?php $selected = ($key == $row->moderation_state)? 'selected' : ''; ?>
							<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
					</select>
				</td>
				<td width="120"><a href="javascript:update_tag_state(<?php echo $row->tag_id; ?>,document.getElementById('state_<?php echo $row->tag_id; ?>').value,'<?php echo $mod_filter; ?>');" style="text-decoration: underline;"><b><?php echo lang('tag_update_state'); ?></b></a></td>
				<td width="100"><a href="javascript:delete_tag(<?php echo $row->tag_id; ?>,'<?php echo $mod_filter; ?>');" style="text-decoration: underline;"><b><?php echo lang('tag_delete'); ?></b></a></td>
			</tr>
		<?php endforeach; ?>
		</table>
<?php else : ?>
<div align="left" id="no_data"><?php echo lang('tag_no_data'); ?></div>
<?php endif; ?>
<input type="hidden" id="mod_filter" value="<?php echo $mod_filter; ?>" />

<?php echo paginate($paginate_args); ?>