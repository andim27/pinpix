<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name');

$lang['admin_area'] = "$site_title Admin Area";
$lang['catalog'] = "Catalog";
$lang['delete_category'] = "Delete";
$lang['select_files'] = "Select Files to Upload:";

$lang['preview'] = "Preview";
$lang['section'] = "Category";
$lang['username'] = "Username";
$lang['title'] = "Title";
$lang['date_added'] = "Date added";
$lang['moderation_state'] = "Moderation state";
$lang['erotic'] = "Erotic?";
$lang['action'] = "Action";
$lang['date_from'] = "from:";
$lang['date_till'] = "till:";
$lang['yes'] = "Yes";
$lang['no'] = "No";
$lang['set_photo_filter'] = "Go";
$lang['clear_photo_filter'] = "Clear";
$lang['btn_ok'] = "Ok";
$lang['btn_cancel'] = "Cancel";
$lang['mod_new'] = "New";
$lang['mod_approved'] = "Approved";
$lang['mod_featured'] = "Featured";
$lang['mod_declined'] = "Declined";
$lang['mod_deleted'] = "Deleted";
/* End of file admin_lang.php */