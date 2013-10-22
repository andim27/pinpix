<?php
static $ci;
if (!is_object($ci)) $ci = &get_instance();

$lang['user_members_title'] = "Қатысушылар";

$lang['user_about']     = "Өзіңіз туралы";
$lang['user_interests'] = "Менің қызығушылықтарым";
$lang['user_login']     = "Пайдаланушының аты";
$lang['user_email']     = "E-mail";
$lang['user_information']= "Пайдаланушы туралы мәлімет";
$lang['user_birth_day']   = "Күні";
$lang['user_birth_month'] = "Айы";
$lang['user_birth_year']  = "Жылы";
$lang['user_about_title'] = "Өзіңіз туралы қысқаша мәлімет";
$lang['user_interest_title'] = "Талғамдар";

$lang['user_birthdate'] = "Туған күніңіз";
$lang['user_country']   = "Мемлекет";
$lang['user_region']    = "Аймақ";
$lang['user_city']      = "Қала";
$lang['user_registration_date'] = "Тіркелу мерзімі";
$lang['user_control_block'] = "Профильді басқару болгы";
$lang['user_save']       = "Сақтау";
$lang['user_attributes'] = "Атрибуттар";
$lang['user_edit']       = "Түзету";
$lang['user_change_psw'] = "Парольді өзгерту";
$lang['user_new_psw']    = "Жаңа пароль";
$lang['user_confirm_psw']= "Парольді нақтылау";

$lang['user_save_psw']   = "Парольді сақтау";
$lang['not_fill']        = "Толтырылмаған";
$lang['user_rating']     = "Сіздің рейтингтік көрсеткіштеріңіз";
$lang['error_activation_process'] = "Активация үрдісі барысында қателіктер туды. ".$ci->config->item('admin_email')." мекен-жайындағы сайт әкімшілігімен байланысуыңызды сұраймыз.";
$lang['error_activation_link'] = "Активация сілтемесі дұрыс емес. Біз жіберген хаттағы активация сілтемесін пайдалануыңызды сұраймыз.";
$lang['error_activation_info'] = "Активация мәліметтері дұрыс емес. Егер сіз өз аккаунтыңызды іске қоссаңыз, бізге жіберген email мен пароль сәйкестігін тексеріңіз немесе ".$ci->config->item('admin_email')." мекен-жайындағы сайт әкімшілігімен байланысыңыз";

$lang['preview']       =   "Превью";
$lang['follow_link']   =   "Сілтемесі";
$lang['active_state']  =   "Іске қосылған";
$lang['block_id']      =   "Бейнелеуге арналған блок номері ";
$lang['what_pages']    =   "Парақтарда көрсету ";

$lang['check_way']    =   "Баннерді орнату әдісін таңдаңыз: бейнені серверге жүктеу немесе дайын қою кодын таңдау "; 

$lang['block_id']      =   "Баннер бейнеленетін блок";
$lang['select_block']  =   "Блокты таңдаңыз";
$lang['pages']   		=   "Баннер бейнеленетін парақтар ";
$lang['select_pages']  =   "Парақты таңдаңыз";
$lang['is_active']     =   "Іске қосулы ма?";

$lang['file_img']      =   "Жүктелетін суретті таңдаңыз ";
$lang['file_url']      =   "Баннерге жету жолы - бейне блогына орнатуға арналған жолақ ";
$lang['alt_text']    	=   'Егер көрсетілмеген болса, "Alt"  жолағы - суреттің атауы';
$lang['title_text']    =   '"Title" жолағы - суретке нұсқағандағы сипаттама-қолтаңба';
$lang['onclick_url']   =   "Onclick - сілтеме бойынша әрекет ету ";
$lang['common_fields']   =   "Жалпы жолақтар ";
 
$lang['user_new'] = "Жаңа";
$lang['user_delete'] = "Жою";
$lang['user_save'] = "Сақтау";
$lang['user_reset'] = "Тазалау";
$lang['user_cancel'] = "Болдырмау";