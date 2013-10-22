<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['keywords'] = 'Keywords: ';
$lang['tag_search_tag'] = 'Search Tag';
$lang['tag_filters_state'] = 'Filters State';
$lang['tag_filters_state_all'] = 'All';
$lang['tag_filters_state_new'] = 'New';
$lang['tag_filters_state_approved'] = 'Approved';
$lang['tag_filters_state_featured'] = 'Featured';
$lang['tag_filers_state_declined'] = 'Declined';
$lang['tag_tags'] = 'Tags';
$lang['tag_name'] = 'Name';
$lang['tag_count'] = 'Count';
$lang['tag_state'] = 'State';
$lang['tag_update'] = 'Update';
$lang['tag_delete'] = 'Delete';
$lang['tag_update_state'] = 'Update state';
$lang['tag_no_data'] = 'No data';
?>