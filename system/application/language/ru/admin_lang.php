<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

$lang['admin_area'] = "$site_title Административный раздел";
$lang['catalog'] = "Каталог";
$lang['delete_category'] = "Удалить";
$lang['select_files'] = "Выберите файл для загрузки:";

$lang['preview'] = "Просмотр";
$lang['section'] = "Раздел";
$lang['username'] = "Пользователь";
$lang['title'] = "Название";
$lang['date_added'] = "Добавлен";
$lang['photo_like'] = "Количество баллов жюри";
$lang['moderation_state'] = "Состояние";
$lang['erotic'] = "Содержит эротическое содержание";
$lang['action'] = "Действие";
$lang['date_from'] = "с:";
$lang['date_till'] = "по:";
$lang['yes'] = "Да";
$lang['no'] = "Нет";
$lang['set_photo_filter'] = "Установить";
$lang['clear_photo_filter'] = "Очистить";
$lang['btn_ok'] = "OK";
$lang['btn_cancel'] = "Отмена";
$lang['mod_new'] = "Новый";
$lang['mod_approved'] = "Одобрен";
$lang['mod_featured'] = "Хорошие";
$lang['mod_declined'] = "Отклонён";
$lang['mod_deleted'] = "Удалён";
$lang['balls'] = "Рейтинг";
$lang['place'] = "Присвоить место";
$lang['comp_photos'] = "Фотографии, отправленные на конкурс ";
$lang['back_to_comp'] = "Назад к списку конкурсов";
$lang['descr'] = "Описание решения";
$lang['no_photos'] = "В конкурсе пока нет фотографий";


/*<?=lang('');? >
/* End of file upload_lang.php */