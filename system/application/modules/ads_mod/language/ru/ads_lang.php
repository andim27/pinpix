<?php
static $ci;
if (!is_object($ci)) $ci = &get_instance();
		
$lang['user_members_title'] = "Участники";

$lang['user_about']     = "О себе";
$lang['user_interests'] = "Мои интересы";
$lang['user_login']     = "Имя пользователя";
$lang['user_email']     = "E-mail";
$lang['user_information']= "Информация о пользователе";
$lang['user_birth_day']   = "День";
$lang['user_birth_month'] = "Месяц";
$lang['user_birth_year']  = "Год";
$lang['user_about_title'] = "Краткая информация о себе";
$lang['user_interest_title'] = "Интересы";

$lang['user_birthdate'] = "Дата рождения";
$lang['user_country']   = "Страна";
$lang['user_region']    = "Регион";
$lang['user_city']      = "Город";
$lang['user_registration_date'] = "Дата регистрации";
$lang['user_control_block'] = "Блок управления профилем";
$lang['user_save']       = "Сохранить";
$lang['user_attributes'] = "Атрибуты";
$lang['user_edit']       = "Редактировать";
$lang['user_change_psw'] = "Сменить пароль";
$lang['user_new_psw']    = "Новый пароль";
$lang['user_confirm_psw']= "Подтверждение пароля";

$lang['user_save_psw']   = "Сохранить пароль";
$lang['not_fill']        = "Не заполнено";
$lang['user_rating']     = "Ваш рейтинг";
$lang['error_activation_process'] = "Во время процесса активации возникли ошибки. Пожалуйста, свяжитесь с администратором сайта по ".$ci->config->item('admin_email');
$lang['error_activation_link'] = "Неверная ссылка для активации. Пожалуйста, используйте ссылку для активации из письма, которое мы выслали на Ваш E-mail";
$lang['error_activation_info'] = "Неверная информация для активации. Если Вы уже активировались, проверьте E-mail и Ваш пароль или свяжитесь с администратором сайта по ".$ci->config->item('admin_email');

$lang['preview']       =   "Превью";
$lang['follow_link']   =   "Ссылка на ";
$lang['active_state']  =   "Активна";
$lang['block_id']      =   "Номер блока для отображения ";
$lang['what_pages']    =   "Отображать на страницах ";

$lang['check_way']    =   "Выберите способ вставки баннера: загрузка изображения на сервер или готовый код вставки"; 

$lang['block_id']      =   "Блок, в котором будет отображаться баннер";
$lang['select_block']  =   "Выберите блок";
$lang['pages']   		=   "Страницы, на которых будет отображаться баннер ";
$lang['select_pages']  =   "Выберите страницы";
$lang['is_active']     =   "Активен?";

$lang['file_img']      =   "Выберите изображение для загрузки ";
$lang['file_url']      =   "Путь к баннеру - строка для вставки в блок отображения ";
$lang['alt_text']    	=   'Поле "Alt" - название изображения, если оно не показывается';
$lang['title_text']    =   'Поле "Title" - подпись-описание при при наведении на изображение';
$lang['onclick_url']   =   "Onclick - следовать по ссылке ";
$lang['common_fields']   =   "Общие поля ";

$lang['user_new'] = "Новый";
$lang['user_delete'] = "Удалить";
$lang['user_save'] = "Сохранить";
$lang['user_reset'] = "Очистить";
$lang['user_cancel'] = "Отменить";
