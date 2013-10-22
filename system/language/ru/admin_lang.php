<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
$site_title = $ci->config->item('site_name', 'blog_settings');

$lang['admin_area'] = "$site_title Admin Area";
$lang['catalog'] = "Каталог";
$lang['delete_category'] = "Удалить";
$lang['select_files'] = "Выбирите файл для загрузки:";

$lang['preview'] = "Просмотр";
$lang['section'] = "Раздел";
$lang['username'] = "Пользователь";
$lang['title'] = "Название";
$lang['date_added'] = "Добавлен";
$lang['moderation_state'] = "Состояние";
$lang['erotic'] = "Эротика?";
$lang['action'] = "Действие";
$lang['date_from'] = "с:";
$lang['date_till'] = "по:";
$lang['yes'] = "Да";
$lang['no'] = "Нет";
$lang['set_photo_filter'] = "Установить";
$lang['clear_photo_filter'] = "Очистить";
$lang['btn_ok'] = "Ok";
$lang['btn_cancel'] = "Отмена";
$lang['mod_new'] = "Новый";
$lang['mod_approved'] = "Одобрен";
$lang['mod_featured'] = "Хорошие";
$lang['mod_declined'] = "Отклонён";
$lang['mod_deleted'] = "Удалён";
/* End of file upload_lang.php */