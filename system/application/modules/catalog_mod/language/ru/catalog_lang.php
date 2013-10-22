<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['category'] = "Категория";

$lang['cat_name_field'] = "Название категории:";
$lang['cat_desc_field'] = "Описание категории:";
$lang['save'] = "Сохранить категорию";

$lang['error_data_saving'] = "Проблема с сохранением данных. Попробуйте отправить позже или свяжитесь с администратором сайта по адресу $admin_email";

/***** Catalog names **/
$lang['cat_name_motorcycles'] = "Мотоциклы";
$lang['cat_description_motorcycles'] = "Мотоциклы";
$lang['cat_name_picture'] = "Портрет";
$lang['cat_description_picture'] = "Портрет";
$lang['cat_name_scenery'] = "Пейзаж";
$lang['cat_description_scenery'] = "Пейзаж";
$lang['cat_name_stilllife'] = "Натюрморт";
$lang['cat_description_stilllife'] = "Натюрморт";
$lang['cat_name_animals'] = "Животные";
$lang['cat_description_animals'] = "Животные";
$lang['cat_name_travels'] = "Путешествия";
$lang['cat_description_travels'] = "Путешествия";
$lang['cat_name_nu'] = "Ню";
$lang['cat_description_nu'] = "Ню";
$lang['cat_name_genre'] = "Жанр";
$lang['cat_description_genre'] = "Жанр";
$lang['cat_name_makro'] = "Макро";
$lang['cat_description_makro'] = "Макро";
$lang['cat_name_other'] = "Разное";
$lang['cat_description_other'] = "Разное";
$lang['cat_name_city'] = "Город";
$lang['cat_description_city'] = "Город";
$lang['cat_name_glamur'] = "Гламур";
$lang['cat_description_glamur'] = "";
$lang['cat_name_wedding_foto'] = "Свадебное фото";
$lang['cat_description_wedding_foto'] = "";
$lang['cat_name_advertising'] = "Реклама";
$lang['cat_description_advertising'] = "";
$lang['cat_name_family'] = "Семья, дети";
$lang['cat_description_family'] = "";
$lang['cat_name_sport'] = "Спорт";
$lang['cat_description_sport'] = "";
$lang['cat_name_history'] = "История";
$lang['cat_description_history'] = "";
$lang['cat_name_tehno'] = "Техно";
$lang['cat_description_tehno'] = "";
$lang['cat_name_computer_art'] = "Компьютерное искусство";
$lang['cat_description_computer_art'] = "";
$lang['cat_name_lomography'] = "Ломография";
$lang['cat_description_lomography'] = "вид фотографии, при котором художественную ценность имеет не каждый отдельный кадр, а их общее количество, на которых может быть изображено всё, что угодно. Это также — съёмка «от бедра», не глядя в видоискатель, преимущественно — простым автоматическим плёночным фотоаппаратом. Название произошло от производителя фотоаппаратов — Ленинградского оптико-механического объединения, ЛОМО. Целью ломографии является изучение и документирование поверхности Земли путем ломографирования моментов её жизни.";
	
/* End of file catalog_lang.php */
