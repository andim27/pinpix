<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

$lang['admin_area'] = "$site_title Әкімшілік бөлім";
$lang['catalog'] = "Каталог";
$lang['delete_category'] = "Жою";
$lang['select_files'] = "Жүктеу файлын таңдаңыз:";

$lang['preview'] = "Көру";
$lang['section'] = "Бөлім";
$lang['username'] = "Пайдаланушы";
$lang['title'] = "Атауы";
$lang['date_added'] = "Қосылды";
$lang['photo_like'] = "Количество баллов жюри";
$lang['moderation_state'] = "Күйі";
$lang['erotic'] = "Содержит эротическое содержание";
$lang['action'] = "Әрекет";
$lang['date_from'] = "бастап";
$lang['date_till'] = "дейін";
$lang['yes'] = "Иә";
$lang['no'] = "Жоқ";
$lang['set_photo_filter'] = "Орнату";
$lang['clear_photo_filter'] = "Тазалау";
$lang['btn_ok'] = "OK";
$lang['btn_cancel'] = "Болдырмау";
$lang['mod_new'] = "Жаңа";
$lang['mod_approved'] = "Қабылданды";
$lang['mod_featured'] = "Жақсы";
$lang['mod_declined'] = "Қабылданбады";
$lang['mod_deleted'] = "Жойылды";
$lang['balls'] = "Рейтингтік көрсеткіші";
$lang['place'] = "Орын беру";
$lang['comp_photos'] = "Байқауға жіберілген фотосуреттер ";
$lang['back_to_comp'] = "Байқаулар тізіміне оралу";
$lang['descr'] = "Шешім сипаттамасы";
$lang['no_photos'] = "Байқауда әзірге фотосурет жоқ";

/*<?=lang('');? >
/* End of file upload_lang.php */