<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$config['branch_before'] = '<table width="90%" border=0 style="margin-left: 20px" >
<tr>
	<td >';
$config['branch_after'] = '</td>
</tr>
</table>';

$config['item_before'] = '';
$config['item_text'] = '
<table border=0>
<tr>
	<td>
		<a href="'.url('admin_category_base_url').'/{id}" >
			<img alt="" src="'.url('category_icon_url','{icon}').'" align="left" />
		</a>
	</td>
	<td><a href="'.url('admin_category_base_url').'/{id}" id="cat_{id}">{name}</a></td>
	<td>({description})</td>
	<td>
		<form method="post" id="remove_cat_{id}" name="remove_cat_{id}">
    		<input type="hidden" name="removed_lft" value="{lft}" />
    		<input type="hidden" name="removed_rgt" value="{rgt}" />
    	</form>
    	&nbsp;&nbsp;&nbsp;
		<a href="javascript: document.remove_cat_{id}.submit();">'.lang('delete_category').'</a>
	</td>
</tr>
</table>
';
$config['item_after'] = '';

/* End of file */