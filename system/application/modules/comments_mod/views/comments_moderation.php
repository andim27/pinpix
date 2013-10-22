<?php display_errors(); ?>

<div id="update_success" style="display:none;" align="center"></div><br/><br/>

<!-- show toolbar -->
<div id="show_com">
<table cellpadding="0" cellspacing="10" border="0">
	<tr>
		<td>
			<b><?php echo lang('comment_body_field'); ?> :</b><br />
			<input type="text" id="comment_body" size="20" maxlength="40" value="<?php //echo $filters['comment_body']; ?>"/>
		</td>		
		<td>
		<?php
			$date_from = "";
			$date_to = "";
			if(isset($filters['comment_date']) && !empty($filters['comment_date'])) {
				if(is_array($filters['comment_date'])) {
					$date_from = $filters['comment_date'][0];
					$date_to = $filters['comment_date'][1];
				}
			}
		?>
<!--			<input type="text" id="comment_date" size="20" maxlength="40" value="<?php //echo $filters['comment_date']; ?>"/>-->
			<div style="float: left; font-size: 12px; text-align: left; width: 243px;">
				<div style="bottom:2px;font-weight:bold;left:75px;position:relative;"><?php echo lang('comment_date_field'); ?></div>
				<span style="font-weight:bold;margin-right:5px;"><?=lang('date_from')?></span><input name="start" id="start" type="text" value="<?=$date_from?>" style="width:100px;height:12px;" />
				<span style="font-weight:bold;margin-left:5px;"><?=lang('date_till')?></span><input name="end" id="end" type="text" value="<?=$date_to?>" style="width:90px;height:12px;" />								
			</div>
		</td>
		
		<td>
			<b><?php echo lang('comment_author_field'); ?> :</b><br />
			<input type="text" id="comment_author" size="20" maxlength="40" value="<?php //echo $filters['comment_author']; ?>"/>
		</td>
		<?php $mod_filter = $filters['moderation_state']; ?>
		<td>
			<b><?php echo lang('comment_mod_state_field'); ?> :</b><br />
			<select id="comment_mod_state" size="1" style="width:120px;" onchange="">
				<?php $selected = ($mod_filter == '.') ? 'selected' : ''; ?>
				<option id="comments_filter_all" value="." <?php echo $selected; ?>><?php echo lang('comment_mod_state_all'); ?></option>
				<?php foreach ($states as $key=>$value) : ?>
					<?php $selected = ("$key" == "$mod_filter") ? 'selected' : ''; ?>
					<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
		
		<td valign="bottom">
			<input type="button" id="submit_apply" value="Apply Filters" onclick="comment_filter(); "/>
		</td>
		<td valign="bottom">
			<input type="button" id="submit_reset" value="Reset" onclick="comment_filter_reset();"/>
		</td>
	</tr>
</table>
</div>

<!-- show table comments -->
<div id="show_list" style="margin: 10px 0px 10px 0px;">
<?php include('comment_list.php'); ?>
</div>