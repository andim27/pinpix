<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

$lang['comment_title_field'] = "Заголовок коментария:";
$lang['comment_body_field'] = "Текст коментария:";
$lang['save'] = "Сохранить коментарий";
$lang['add_comment'] = "Добавить коментарий";
$lang['comment_need_login'] = "Чтобы оставлять комментарий необходимо зарегистрироваться";
$lang['comments'] = "Комментарии";
$lang['answer'] = "Ответ";
$lang['answer_with_quoting'] = "Ответ с цитатой";
$lang['remove'] = "Удалить";
$lang['comment_title_field'] = "Заголовок комментария:";
$lang['comment_body_field'] = "Текст комментария";
$lang['save'] = "Сохранить комментарий";
$lang['add_comment'] = "Оставить комментарий";
$lang['comment_need_login'] = "Чтобы оставлять комментарий необходимо зарегистрироваться";
$lang['add_answer'] = "Добавьте свой ответ на: ";
$lang['error_delete_comment'] = "Возникла проблема с удалением комментария";
$lang['error_data_saving'] = "Возникла проблема с сохранением данных. Попробуйте позже или свяжитесь с администратором по $admin_email";
$lang['comment_date_field'] = 'Дата добавления';
$lang['comment_author_field'] = 'Автор';
$lang['commented_object_field'] = 'Объект комментирования';
$lang['comment_mod_state_field'] = 'Moderation State';
$lang['comment_update_field'] = 'Обновить';
$lang['comment_delete_field'] = 'Удалить';
$lang['comment_mod_state_all'] = 'Все';
$lang['comment_no_data'] = 'Нет данных';
$lang['confirm_delete_comment'] = 'Удалить комментарий?';