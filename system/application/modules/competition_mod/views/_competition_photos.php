<?php
	foreach($photos as $index=>$photo) {
?>
	<tr valign="center" align="center" height="150"<?=(($index%2 == 1)?' style="background-color:#ccc;"':'');?>>
		<td width="20"><input type="checkbox" id="cb_<?=$index?>" name="cid[]" value="<?=$photo->photo_id;?>" onclick="isChecked(this.checked);" /></td>
		<td style="text-align:center;">
			<?php $urlImg = base_url()."uploads/photos/".date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension; ?>																	
			<img alt="<?php echo $urlImg ?>" src="<?=$urlImg ?>" />
		</td>
		<td><?=$photo->login?></td>
		<td><?=$photo->title?></td>
		<td><?=date("Y-m-d", strtotime($photo->date_added))?></td>
		<td><?=$photo->balls?></td>
		<td width="90">
			<select name="moderation_state" id="moderation_state_<?=$index?>">
				<option value="0" <?=(($photo->moderation_state == 0)?' selected="selected"':'');?>>Новый</option>
				<option value="1" <?=(($photo->moderation_state == 1)?' selected="selected"':'');?>>Одобрен</option>
				<option value="2" <?=(($photo->moderation_state == 2)?' selected="selected"':'');?>>Хорошие</option>
				<option value="-1" <?=(($photo->moderation_state == -1)?' selected="selected"':'');?>>Отклонен</option>
				<option value="-2" <?=(($photo->moderation_state == -2)?' selected="selected"':'');?>>Удален</option>
			</select>
		</td>
		<td width="64">
			<select name="place_<?=$index?>">
				<option value="" <?=(empty($photo->place_taken)?' selected="selected"':'');?>>Нет</option>
				<?php for ($t=0; $t<10; $t++) {?>
				<option value="<?=$t+1?>" <?=(($photo->place_taken == $t+1)?' selected="selected"':'');?>><?=$t+1?></option>
				<?php } ?>
			</select>
		</td>
		<td width="64">
			<textarea id="place_description" name="place_description" cols="" rows="" style = "width:130px; height:150px;" ><?php if (isset ($photo->place_description)) echo $photo->place_description ?></textarea>	
		</td>
		<td width="130" align="center">
			<input type="submit" value="Ок" onclick="javascript: apply_one('<?=$index?>');" />
			<input type="reset" value="Отмена" />
			
			<input type="hidden" id="photo_id_<?=$index?>" value="<?=$photo->photo_id?>"/>
		</td>
	</tr>
<?php
	}
?>