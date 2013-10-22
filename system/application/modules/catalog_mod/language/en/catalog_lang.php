<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['category'] = "Category";

$lang['cat_icon_field'] = "Category Icon:";
$lang['cat_preview_field'] = "Category Preview:";
$lang['cat_name_field'] = "Category Name:";
$lang['cat_desc_field'] = "Category Description:";
$lang['cat_parent_field'] = "Category Parent:";
$lang['cat_sort_order_field'] = "Category Sort Order:";
$lang['save'] = "Save Category";

$lang['error_data_saving'] = "Problem occured with your data saving. Please try again latter or contact site admin at $admin_email";

/* End of file catalog_lang.php */
