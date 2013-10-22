<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['comments'] = "Comments";

$lang['answer'] = "Answer";
$lang['answer_with_quoting'] = "Answer with Quoting";
$lang['remove'] = "Remove";

$lang['comment_title_field'] = "Comment Title:";
$lang['comment_body_field'] = "Comment Text";
$lang['save'] = "Save Comment";
$lang['add_comment'] = "Leave a comment";
$lang['comment_need_login'] = "It is necessary to be registered on pinpix to leave a comment ";
$lang['add_answer'] = "Add your Answer on";

$lang['error_delete_comment'] = "Problem occured with comment removing.";
$lang['error_data_saving'] = "Problem occured with your data saving. Please try again latter or contact site admin at $admin_email";

$lang['comment_date_field'] = 'Date';
$lang['comment_author_field'] = 'Author';
$lang['commented_object_field'] = 'Commented Object';
$lang['comment_mod_state_field'] = 'Moderation State';
$lang['comment_update_field'] = 'Update';
$lang['comment_delete_field'] = 'Delete';
$lang['comment_mod_state_all'] = 'All';
$lang['comment_no_data'] = 'No data';

/*
$security_question[1] = "What is your 1st pet's name?";
$security_question[2] = "What is your favourite movie?";
$security_question[3] = "What is your favourite band?";
*/
?>
