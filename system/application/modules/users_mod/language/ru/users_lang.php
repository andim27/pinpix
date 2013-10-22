<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['req'] = "Поля, обязательные для за полнения";
$lang['choose_country'] = "Выберите страну";
$lang['choose_region'] = "Выберите регион";
$lang['choose_city'] = "Выберите город";

$lang['user_members_title'] = "Участники";
$lang['user_about']     = "О себе";
$lang['user_interests'] = "Интересы";
$lang['user_login']     = "Имя пользователя";
$lang['user_email']     = "E-mail";
$lang['user_information']= "Дополнительная информация";
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
$lang['user_psw']        = "Пароль";
$lang['user_new_psw']    = "Новый пароль";
$lang['user_confirm_psw']= "Подтверждение пароля";
$lang['user_cur_psw']    = "Укажите текущий пароль";
$lang['user_save_psw']   = "Сохранить пароль";
$lang['not_fill']        = "Не заполнено";
$lang['user_rating']     = "Ваш рейтинг";
$lang['comment_can']     = "Уведомления о комментариях";

$lang['month_1']         = "январь";
$lang['month_2']         = "февраль";
$lang['month_3']         = "март";
$lang['month_4']         = "апрель";
$lang['month_5']         = "май";
$lang['month_6']         = "июнь";
$lang['month_7']         = "июль";
$lang['month_8']         = "август";
$lang['month_9']         = "сентябрь";
$lang['month_10']        = "октябрь";
$lang['month_11']        = "ноябрь";
$lang['month_12']        = "декабрь";
$lang['change_psw_good'] = "Пароль изменен успешно";
$lang['change_psw_error']= "Ошибка изменения пароля";

$lang['bad_avatar']      = "Недопустимый файл";  
$lang['pict_avatar']     = "Аватар";
$lang['ready_avatar']    = "Готовые";
$lang['load_avatar']     = "Загрузить свою картинку (файл jpg, png, gif)";
$lang['max_s_avatar']    = "Максимальный размер";
$lang['max_v_avatar']    = "Максимальный вес";
$lang['user_avatar']     = "Ваш Аватар";
$lang['default_avatar']  = "Предопределенный аватар";
$lang['personal_avatar'] = "Личный аватар";
$lang['choice']          = "Выбор";
$lang['set_avatar']      = "Установить";
$lang['choice_avatar_befor']    = "Выберите аватар перед установкой"; 
$lang['change_avatar_process']  = "Устанавливаю аватар...";
$lang['change_avatar_good']  = "Аватар успешно установлен";
$lang['change_avatar_error'] = "Ошибка изменения аватара";
$lang['user_profile']="Личный профиль";
$lang['registration'] = "Регистрация";
$lang['nik'] = "Имя пользователя";
$lang['user_password'] = "Пароль";
$lang['user_password_confirm'] = "Подтвердите пароль";
$lang['user_birtdate'] = "Дата рождения ";

$lang['user_info'] = "Краткая информация о себе";
$lang['user_nic-fio'] = "Имя пользователя";
$lang['cancel']	= "Отмена";

$lang['bibb_password'] = "Пароль";
$lang['bibb_nik'] = "Имя пользователя";
$lang['bibb_email']= "E-mail";
$lang['bibb_password_confirm']= "Подтвердить пароль";
