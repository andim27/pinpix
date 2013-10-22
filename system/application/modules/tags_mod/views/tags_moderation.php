<?php modules::load_file('ajax_requests_js.php',MODBASE.modules::path().'/scripts/'); ?>

<?php display_errors(); ?>

<div id="update_success" style="display:none;" align="center"></div><br/><br/>

<!-- show toolbar -->
<div align="right">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<b><?php echo lang('tag_search_tag'); ?> :</b>
			<input type="text" id="text_search" size="20" maxlength="40" value="<?php echo $search_string; ?>"/>
			<input type="button" id="submit_search" value="Search" onclick="search_tag(); document.getElementById('tags_filter_all').selected=true;"/>
		</td>
		
		<td>&nbsp;&nbsp;
			<b><?php echo lang('tag_filters_state'); ?> :</b>
			<select id="tags_filter" size="1" style="width:100px;" onchange="tag_filter(this.value); document.getElementById('text_search').value='';">
				<?php $selected = ($mod_filter == '%') ? 'selected' : ''; ?>
				<option id="tags_filter_all" value="%" <?php echo $selected; ?>><?php echo lang('tag_filters_state_all'); ?></option>
				<?php foreach ($states as $key=>$value) : ?>
					<?php $selected = ("$key" == "$mod_filter") ? 'selected' : ''; ?>
					<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
</table>
</div>

<!-- show table tags -->
<div id="show_tags" align="center" style="margin: 10px 0px 10px 0px;">
<?php include('tag_list.php'); ?>
</div>