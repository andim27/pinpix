<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['bibb_register'] = "Регистрация";
$lang['bibb_enter'] = "Войти";
$lang['bibb_terms'] = "18 января – 15 апреля 2010 г";
$lang['bibb_comp'] = "Конкурс";
$lang['bibb_comp_name'] = "Казахстан в фокусе";
$lang['bibb_login'] = "Имя пользователя";
$lang['bibb_password'] = "Пароль";
$lang['bibb_nik'] = "Имя пользователя";
$lang['bibb_email']= "E-mail";
$lang['bibb_password_confirm']= "Подтвердить пароль ";
$lang['bibb_birtdate']= "Дата рождения";
$lang['bibb_contry']= "Cтрана";
$lang['bibb_chouse_contry']= "Выберите страну";
$lang['bibb_city']= "Город";
$lang['bibb_chouse_city']= "Выберите город";
$lang['bibb_user_info']= "Краткая информация о себе";
$lang['bibb_user'] = "Пользователь";
$lang['bibb_exit'] = "Выход ";
$lang['error_auth']  = "Неверный логин или пароль!";

$lang['conditions_text'] = "«Казахстан в фокусе» - конкурс на лучшую фотографию, максимально точно <br /> и 
в то же время креативно презентующую Казахстан. Конкурс проводится, чтобы показать <br />
общественности, каким видят Казахстан фотолюбители и фотографы-профессионалы.</p><br />
<p>Присылайте фотографии, показывающие, чем живет Казахстан, колоритность, красоту,<br />
историю, людей, которые здесь живут, работают, строят новый Казахстан и любят свою страну. <br />
В данном конкурсе каждый фотограф может раскрыть свое видение любимой страны";

$lang['bibb_rules'] = "Правила проведения конкурса";

$lang['welcome']  = "Добро пожаловать! Вы успешно вошли в систему!";  
$lang['ok_redirect']  = "Загрузить фото";  

$lang['upload_succ']  = "Спасибо, Ваша работа принята на рассмотрение для участия в конкурсе.<br />После проверки на Ваш e-mail будет выслано уведомление об участии в конкурсе.";


$lang['user_photo_upload'] = "Загрузить фотографии для участия в конкурсе";
$lang['user_photo_upload_again'] = "Загрузить еще фотографию для участия в конкурсе";
$lang['user_photo_file'] = "Файл";
$lang['user_photo_description'] = "Описание";
$lang['user_photo_title'] = "Название фото";
$lang['user_photo_5_upload'] = "На конкурс можно загружать до 5 фотографий";

$lang['js_label_FileEmpty'] = "Не выбран файл для загрузки";
$lang['js_label_EmptyTitle'] = "Необходимо заполнить название.";
$lang['js_label_NotValidTitle'] = "Название сожержит недопустимые символы";
$lang['js_label_EmptyDescription'] = "Необходимо заполнить описание.";
$lang['js_label_ImgSubmitFailed'] = "Не удалось загрузить рисунок";
$lang['lot_of_photos'] = "Запрещено загружать более 5-ти фото на конкурс! ";


$lang['demidov_name'] ='Демидов А., Казахстан, г.Алматы';
$lang['demidov_desc'] ='Профессиональный фотограф (реклама/журналы)';

$lang['ywakov_name'] ='Ушаков В.А., Кыргызстан, г.Бишкек';
$lang['ywakov_desc'] ='Генеральный директор «Союза Фотожурналистов Кыргызстанa»';

$lang['babkin_name'] ='Бабкин В., Казахстан, г.Алматы';
$lang['babkin_desc'] ='Профессиональный фотограф, главный редактор журнала «Афиша»';

$lang['korolev_name'] ='Королев Н.В., Россия, г.Москва';
$lang['korolev_desc'] ='Фоторедактор  Дирекции международных проектов "Российской газеты"';

$lang['pix_juri' ] ='Жюри';
$lang['pix_prize'] ='Призы';

$lang['eos'] ='Зеркальная цифровая фотокамера <i>Canon EOS 500D</i>';
$lang['pwg'] ='Цифровая фотокамера <i>Canon PowerShot G11 </i>';
$lang['bag'] ='Сумка для цифровых зеркальных фотокамер <i>Canon</i>';
$lang['footer'] ='Призы предоставлены компанией';

$lang['cancel'] = 'Отмена';
$lang['req']	= "Обязательно для заполнения";
$lang['juri_page']	= "Жюри конкурса «Казахстан в фокусе»";
$lang['juri_rev']	= "Обратно к конкурсу";


$lang['upgrade_browser'] = "Сайт работает не корректно в браузере Internet Explorer версии 6. Обновите свой браузер или используйте браузеры Firefox, Opera, Safari, Chrome.";

$lang['partner_cannon']  = "В партнерстве с компанией ";

$lang['user_support'] = "Поддержка пользователей: ";

$lang['no_flash'] ="Для отображения <br>Flash-элементов на сайте,<br/>необходимо <a href='http://www.adobe.com/go/getflash/'>установить<br> Flash-проигрыватель</a>";

/*** Блок Новости*/
$lang['news_title'] = "Новости";
$lang['news_1'] = "Фотоконкурс продлен до 15 апреля! См.";
$lang['news_1_continue'] = "Правила";
$lang['news_2'] = "Pinpix поддерживает соцпроект по привлечению внимания общества к проблемам людей с ограничеснными возможностями в РК.";
$lang['news_2_continue'] = "Подробнее...";