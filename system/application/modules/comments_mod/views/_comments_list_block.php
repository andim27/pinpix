<?php 
	if(!empty($comments)) {
		foreach ($comments as $i=>$comment) : ?>
		<tr align="center">
			<td width="20"><input type="checkbox" id="cb_<?=$comment->id?>" name="cid[]" value="<?=$comment->id?>" onclick="isChecked(this.checked);" /> </td>
			<td width="100"><?php echo $comment->comment_date; ?></td>
			<td>
				<a class="ulink" target="_blank" href="<?php echo url('profile_view_url',$comment->user_id); ?>"><?php echo $comment->login; ?></a>
			</td>
			<td align="left"><?php echo $comment->body; ?></td>
			<td>
				<?php if( $comment->commented_object_type=='photo') { ?>
				<a class="ulink" href="<?php echo url('view_photo_url',$comment->commented_object_id); ?>">
					<img alt = <?php echo $comment->title ?> src = "<?php echo photo_location().date("m", strtotime($comment->date_added))."/".$comment->commented_object_id."-sm".$comment->extension; ?>"></img>
				</a>
				<?php } ?>
			</td>
			<td width="120">
				<select id="state_<?php echo $comment->id; ?>" size="1" style="width:100px;">
					<?php foreach ($states as $key=>$value) : ?>
					<?php $selected = ($key == $comment->moderation_state)? 'selected' : ''; ?>
					<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td width="120"><a href="#" onclick="javascript: apply_one('<?=$comment->id?>'); return false;" style="text-decoration: underline;"><b><?php echo lang('comment_update_field'); ?></b></a></td>
			<td width="100">
				<a href="#" onclick = "comment_delete('<?php echo $comment->lft; ?>','<?php echo $comment->rgt; ?>','<?php echo $comment->commented_object_type; ?>'); return false" style="text-decoration: underline;"><b><?php echo lang('comment_delete_field'); ?></b></a>
				<input type="hidden" id="comment_id_<?=$comment->id?>" value="<?=$comment->id?>" />
			</td>
		</tr>
<?php 
		endforeach;
	}
?>