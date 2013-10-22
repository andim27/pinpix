<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['category'] = "Топтама";

$lang['cat_name_field'] = "Топтама атауы:";
$lang['cat_desc_field'] = "Топтама сипаттамасы:";
$lang['save'] = "Топтаманы сақтау";

$lang['error_data_saving'] = "Мәліметтерді сақтауда қателік бар. Кейінірек жіберіп көріңіз немесе $admin_email мекен-жайындағы сайт әкімшілігімен байланысыңыз";

/***** Catalog names **/
$lang['cat_name_motorcycles'] = "Мотоциклы";
$lang['cat_description_motorcycles'] = "Мотоциклы";
$lang['cat_name_picture'] = "Портрет";
$lang['cat_description_picture'] = "Портрет";
$lang['cat_name_scenery'] = "Пейзаж";
$lang['cat_description_scenery'] = "Пейзаж";
$lang['cat_name_stilllife'] = "Натюрморт";
$lang['cat_description_stilllife'] = "Натюрморт";
$lang['cat_name_animals'] = "Жан-жануарлар";
$lang['cat_description_animals'] = "Жан-жануарлар";
$lang['cat_name_travels'] = "Саяхат";
$lang['cat_description_travels'] = "Саяхат";
$lang['cat_name_nu'] = "Ню";
$lang['cat_description_nu'] = "Ню";
$lang['cat_name_genre'] = "Жанр";
$lang['cat_description_genre'] = "Жанр";
$lang['cat_name_makro'] = "Макро";
$lang['cat_description_makro'] = "Макро";
$lang['cat_name_other'] = "Әртүрлі";
$lang['cat_description_other'] = "Әртүрлі";
$lang['cat_name_city'] = "Қала";
$lang['cat_description_city'] = "Қала";
$lang['cat_name_glamur'] = "Сән-салтанат";
$lang['cat_description_glamur'] = "";
$lang['cat_name_wedding_foto'] = "Үйлену тойының фотосуреттері";
$lang['cat_description_wedding_foto'] = "";
$lang['cat_name_advertising'] = "Жарнама";
$lang['cat_description_advertising'] = "";
$lang['cat_name_family'] = "Отбасы, балалар";
$lang['cat_description_family'] = "";
$lang['cat_name_sport'] = "Спорт";
$lang['cat_description_sport'] = "";
$lang['cat_name_history'] = "Тарих";
$lang['cat_description_history'] = "";
$lang['cat_name_tehno'] = "Техно";
$lang['cat_description_tehno'] = "";
$lang['cat_name_computer_art'] = "Компьютерлік өнер";
$lang['cat_description_computer_art'] = "";
$lang['cat_name_lomography'] = "Ломография";
$lang['cat_description_lomography'] = "вид фотографии, при котором художественную ценность имеет не каждый отдельный кадр, а их общее количество, на которых может быть изображено всё, что угодно. Это также — съёмка «от бедра», не глядя в видоискатель, преимущественно — простым автоматическим плёночным фотоаппаратом. Название произошло от производителя фотоаппаратов — Ленинградского оптико-механического объединения, ЛОМО. Целью ломографии является изучение и документирование поверхности Земли путем ломографирования моментов её жизни.";
/* End of file catalog_lang.php */
