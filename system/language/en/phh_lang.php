<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['pages'] = "Pages: ";

$lang['search'] = "search";

$lang['mod_state_new'] = 'New';
$lang['mod_state_approved'] = 'Approved';
$lang['mod_state_featured'] = 'Featured';
$lang['mod_state_declined'] = 'Declined'; 

$lang['registration'] = "Registration";
$lang['reg_step_1'] = "Step 1: Pick your username and supply email address";
$lang['username_field'] = "Pick your preffered username:";
$lang['email_field'] = "Email address:";
$lang['reg_step_2'] = "Step 2: Activation email sent";
$lang['activation_sent'] = "Please check your email for activation email we sent to you, and use supplied link to confirm your registration.";
$lang['reg_step_3'] = "Step 3: Activation successfull";
$lang['start_using_account'] = "Your account now active and you can start using it.
  Please check your email for access information we sent to you. 
  Don't forget to keep your access information safe from the others.";
$lang['collect_information_for_profile'] = "Now, we want to collect some additional information for your profile on our site.";

$lang['error_login_empty']  = "Login or password empty!";
$lang['error_login_wrong']  = "Wrong login or password!";
$lang['error_login_used']  = "This username already taken. Please pick another one";

$lang['login_input']  = "Enter username";
$lang['email_input']  = "Enter email";
$lang['error_email_format']  = "Email format invalid";
$lang['error_email_used']  = "Email already used. May be you <a href='/forgot-password'>forgot your password?</a>";
$lang['banned']  = "This account banned!";
$lang['not_activated']  = "Account not activated yet! Please follow the link from the activation email first.";
$lang['confirm_reg'] = "Confirm your registration at $site_title";
$lang['error_data_saving'] = "Problem occured with your data saving. Please try again latter or contact site admin at $admin_email";
$lang['error_activation_link'] = "Invalid activation link. Please use activation link from activation email sent to you";
$lang['error_activation_info'] = "Wrong activation info. If you have already activated your account check your email for password sent to you, or contact site admin at $admin_email";
$lang['reg_success'] = "Registration at $site_title successfull";

$lang['error_activation_process'] = "There were errors during your activation process. Please contact site admin at $admin_email";

$lang['email_changed_adm'] = "Your email was changed by administrator";
$lang['email_changed'] = "Email changed";
$lang['email_change_requested'] = "You have requested email change";
$lang['confirm_email_sent'] = "Confirmation email was sent to your new address. Please use supplied link to confirm change.";
$lang['error_confirmation_link'] = "Invalid confirmation link. Please use confirmation link from confirmation email sent to you";
$lang['error_confirmation_info'] = "Wrong confirmation info. May be you have confirmed your change already?";
$lang['confirm_passw'] = "Please confirm password";
$lang['error_passw_mismatch'] = "Passwords mismatch";
$lang['passw_changed'] = "Password changed";
$lang['access_info_changed'] = "Access information changed";
$lang['error_user_not_found'] = "User with this email not found";
$lang['error_security_question'] = "You haven't assigned security question for password reset. Please contact site admin at $admin_email";
$lang['error_answer'] = "Answer incorrect";
$lang['passw_reset'] = "Your password was reset. Please check email and use new password for access";

$lang['login'] = 'Login: ';
$lang['password'] = 'Password: ';
$lang['error_auth']  = "Wrong login or password!";

$lang['forgot_password'] = 'Forgot your password?';
$lang['register'] = 'Register';

$lang['male'] = "Male";
$lang['female'] = "Female";

$lang['name'] = 'Name';
$lang['description'] = 'Brief Description';

$lang['category_tags'] = 'Category Keywords';

$lang['to_delete'] = "to delete";
$lang['to_edit'] = "to edit";
$lang['to_add'] = "to add";
$lang['date'] = "Date";
$lang['rate'] = "Rate";

$lang['competitions'] = "Competitions";
$lang['comp_sort_by'] = "Sorting";
$lang['comp_sort_by_date'] = "Date";
$lang['comp_sort_by_rate'] = "Rate";
$lang['comp_sort_by_name'] = "A-Z";
$lang['comp_sort_by_relevance'] = "Relevance";
$lang['faq'] = "FAQ";
$lang['contacts'] = "Contacts";
$lang['help'] = "Help";
$lang['exit'] = "Exit";
$lang['enter'] = "Enter";
$lang['categories'] = "Categories";

$lang['search'] = "Search";
$lang['search_key_word'] = "Key words";
$lang['search_in_section'] = "Search in category";

$lang['user_all_photos'] = "All photos";
$lang['user_comments'] = "Comments";
$lang['user_views'] = "Views";
$lang['user_albums'] = "Albums";
$lang['user_photos'] = "Photos";
$lang['title_comments']="Comments:";
$lang['user_photo_properties'] = "Photo Properties";
$lang['user_photo_title'] = "Title photo";
$lang['user_photo_category'] = "Category";
$lang['user_photo_album'] = "Album";
$lang['user_photo_competitions'] = "Submit to the competitions";
$lang['user_photo_album_main'] = "Make main for album";
$lang['user_photo_page_main'] = "Display in homepage header";
$lang['user_photo_upload'] = "Upload photo";
$lang['user_photo_file'] = "File";
$lang['user_photo_erotic'] = "Erotic";
$lang['user_photo_description'] = "Description";
$lang['user_photo_allowed'] = "Allowed for";
$lang['user_photo_bulk_upload'] = "To bulk upload photos, simply pack them into your computer in any of the archives .ZIP, .RAR";

$lang['user_photo_interests'] = "My interests";
$lang['user_photo_about'] = "About me";
$lang['user_photo_email'] = "e-mail:";
$lang['user_photo_contacts'] = "My contacts";
$lang['user_photo_profile'] = "Profile";

$lang['user_photo_uploaded_types'] = 'All images';
$lang['user_photo_uploaded_text'] = 'Choose';

$lang['page_not_found'] = "<strong><em>404</em> Page hot found</strong>";
$lang['file_not_found'] = "<strong><em>404</em> Photo not found</strong>";

$lang['deleted_photos_albums'] = "Deleted photos and albums";

$lang['js_label_FileEmpty'] = "File for upload isn't chousen";
$lang['js_label_PasswordEmpty'] = "Password is empty";
$lang['js_label_ConfirmationEmpty'] = "Confirmation is empty";
$lang['js_label_PwsConfDifferent'] = "Password and confirmation are diferrent";
$lang['js_label_ImgSubmitFailed'] = "Image not been uploaded";
$lang['js_label_EmptyAlbumTitle'] = "You may enter album title";
$lang['js_label_DeleteConfirmation'] = "Are you sure?";

$lang['js_label_EmptyTitle'] = "Title is empty.";
$lang['js_label_EmptyStartTime'] = "Start time is empty.";
$lang['js_label_EmptyEndTime'] = "End time is empty.";
$lang['js_label_EmptyDescription'] = "Description is empty.";

$lang['deleted_warnung_title'] = "Attention!";
$lang['deleted_warnung'] = "Deleted photos and albums kept here only 30 days. Later they will be erased from the repository on the server permanently.";
$lang['no_photo']  = "No new photo for last week";
$lang['new_photo']  = "The newest photos";

$lang['only_for_author']  = "Permission allowed only to author of photo";

$lang['photo_deleted'] = "<strong> Photo was deleted </strong>";
$lang['photo_declined'] = "<strong> Photo was declined</strong>";
$lang['photo_not_approved'] = "<strong> Photo is not approved yet</strong>";
$lang['photo_not_good'] = "<strong>Photo is not good enough</strong>";
/*
$security_question[1] = "What is your 1st pet's name?";
$security_question[2] = "What is your favourite movie?";
$security_question[3] = "What is your favourite band?";
*/
$lang['no_flash'] ="This content requires the  Adobe Flash Player<br/>and a browser with JavaScript enabled<br/><a href='http://www.adobe.com/go/getflash/'>Get Flash</a>";
$lang['welcome']  = "Welcome! ";  
$lang['ok_redirect']  = "Everithyng's ok, please follow the link ";  

$lang['work_added']  = "Thanks, your work acsepted ";  
$lang['comp_continue']  = "If you want to add more works - click to continue ";  

$lang['abum_not_approved'] = "<strong> This album isn't approved yet </strong>";
$lang['email_in_use']  = "Этот email уже был указан при регистрации другого аккаунта";  