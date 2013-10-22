<div class="comments" id="category" style="margin-top:20px;">

<table id="comment_id" class="comment_class" width="100%" cellspacing="5">
	<tbody>
		<tr>
					
			<td class="cont">
			
			<h1><?php echo lang('category'); ?></h1><br />
			
			<form method="post" name="category_form" enctype="multipart/form-data">
			
			<!--  
			<input type="hidden" id="cat_rgt" name="cat_rgt" value="<?php //echo $cat_rgt; ?>" />
			<input type="hidden" id="cat_lft" name="cat_lft" value="<?php //echo $cat_lft; ?>" />
			-->
			<input type="hidden" id="cat_id" name="cat_id" value="<?php echo $cat_id; ?>" />
			
			<table>
			<tr>
				<td><?php echo lang('cat_icon_field'); ?></td>
			</tr>
			<tr>
				<td>
				<?php if ( ! empty($cat_icon_src)) : ?>
					<img alt="" src="<?php echo $cat_icon_src; ?>" align="left" />
				<?php endif; ?>
				&nbsp;&nbsp;&nbsp;<label for="cat_icon" class=""><?php echo lang('select_files'); ?></label><br />
				&nbsp;&nbsp;&nbsp;<input type="file" name="cat_icon" id="cat_icon" />
				</td>
			</tr>
			</table>
			
			<br /><br />
			
			<table>
			<tr>
				<td><?php echo lang('cat_preview_field'); ?></td>
			</tr>
			<tr>
				<td>
				<?php if ( ! empty($cat_preview_src)) : ?>
					<img alt="" src="<?php echo $cat_preview_src; ?>" align="left" />
				<?php endif; ?>
				&nbsp;&nbsp;&nbsp;<label for="cat_preview" class=""><?php echo lang('select_files'); ?></label><br />
				&nbsp;&nbsp;&nbsp;<input type="file" name="cat_preview" id="cat_preview" />
				</td>
			</tr>
			</table>
			
			<br /><br />
			
			<?php echo form_error('cat_name', '<br /><div class="error_message">','</div>'); ?>
			<?php echo lang('cat_name_field'); ?><br />
			<input type="text" id="cat_name" name="cat_name" value="<?php echo $cat_name; ?>" size="50" />
			<span class="required">*</span>
			
			<br /><br />
			
			<?php echo form_error('cat_desc', '<br /><div class="error_message">','</div>'); ?>
			<?php echo lang('cat_desc_field'); ?><br />
			<textarea id="cat_desc" name="cat_desc" cols="50"><?php echo $cat_description; ?></textarea>
			<span class="required">*</span>
			
			<br /><br />
			
			<?php echo lang('cat_parent_field'); ?>
			<select name="cat_parent_id" id="cat_parent">
			<?php echo $cat_list; ?>
			</select>
			
			<script language="javascript" type="text/javascript">
				document.getElementById('cat_parent').value = '<?php echo $cat_parent; ?>';
			</script>
			
			<br /><br />
			<!-- 
			<?php //echo lang('cat_sort_order_field'); ?>
			<select name="cat_sort" id="cat_sort">
				<?php /*foreach (range(1, $cat_count) as $number) : ?>
						<?php $selected = $cat_sort_order==$number?'selected':''; ?>	
    					<option <?php echo $selected; ?> ><?php echo $number; ?></option>
				<?php endforeach;*/ ?>
			</select>
			
			<br /><br />
			-->
			
			<div><input type="submit" id="submit_button" value="<?php echo lang('save'); ?>" /></div>
			
			</form>
			
			</td>
		</tr>
	</tbody>
</table>

</div>