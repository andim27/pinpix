<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['pages'] = "Парақтар: ";

$lang['search'] = "іздеу";

$lang['mod_state_new'] = 'Жаңа';
$lang['mod_state_approved'] = 'Бекітілгендер';
$lang['mod_state_featured'] = 'Белгіленгендер';
$lang['mod_state_declined'] = 'Қабылданбағандар';

$lang['registration'] = "Қабылданбағандар";

$lang['reg_step_1'] = "Қадам 1: Пайдаланушы аты мен e-mail-ыңызды енгізіңіз";
$lang['username_field'] = "Пайдаланушының аты:";
$lang['email_field'] = "E-mail:";

$lang['reg_step_2'] = "Қадам 2: Пайдаланушыны тексеру және іске қосу";
$lang['remember_sent'] = "Активация мен тіркелуді нақтылау хаты көрсетілген e-mail-ге жіберілді.";
$lang['activation_sent'] = "Көрсетілген e-mail-ге активация сілтемесі мен тіркелу хатының келу-келмеуін тексеруіңізді сұраймыз. Активация мен тіркелуді нақтылау үшін хаттағы сілтеме бойынша әрекет етуіңіз керек.";

$lang['reg_step_3'] = "Қадам 3: Сәтті тіркелу және активация";
$lang['start_using_account'] = "Тіркелу мен активация сәтті аяқталды. Пайдаланушы аты іске қосылды. Сізге сайтқа кіру мәліметтері бар хат жіберілді, e-mail-ыңызды тексеруіңізді сұраймыз.";
$lang['start_using_account_without_active'] = "Сіздің e-mail-ыңызға сайтқа кіру мәліметтері бар хат жіберілді.";

$lang['collect_information_for_profile'] = "Қосымша ақпарат";
$lang['error_login_empty']  = "Пайдаланушы аты көрсетілмеді!";
$lang['error_login_wrong']  = "Пайдаланушының аты немесе пароль дұрыс емес!";
$lang['error_login_used']  = "Мұндай ат қолданыста бар. Басқасын таңдауыңызды сұраймыз.";

$lang['login_input']  = "Пайдаланушының аты";
$lang['email_input']  = "E-mail";
$lang['error_email_format']  = "E-mail форматы дұрыс емес";
$lang['error_email_used']  = "Көрсетілген e-mail қолданыста бар. Мүмкін Сіз <a href='/forgot-password'> пароліңізді ұмыттыңыз?</a>";
$lang['banned']  = "Пайдаланушының аты істен шығарылған!";
$lang['not_activated']  = "Пайдаланушының аты әлі іске қосылмаған! Көрсетілген еmail-мекен-жайына жіберілген активация хатындағы сілтемені пайдалануыңызды сұраймыз.";
$lang['confirm_reg'] = "$site_title сайтындағы өз тіркелуіңізді нақтылаңыз";
$lang['error_data_saving'] = "Сіздің мәліметтеріңізді сақтауда қателік бар. Кейінірек байқап көріңіз немесе $admin_email мекен-жайындағы сайт әкімшілігімен байланысыңыз";
$lang['error_activation_link'] = "Активация сілтемесі дұрыс емес. Біз жіберген хаттағы активация сілтемесін пайдалануыңызды сұраймыз.";
$lang['error_activation_info'] = "Активация мәліметтері дұрыс емес. Егер сіз өз аккаунтыңызды іске қоссаңыз, бізге жіберген e-mail мен пароль сәйкестігін тексеріңіз немесе $admin_email мекен-жайындағы сайт әкімшілігімен байланысыңыз";
$lang['reg_success'] = "$site_title сайтында тіркелу сәтті аяқталды";

$lang['error_activation_process'] = "Активация үрдісі барысында қателіктер туды. $admin_email мекен-жайындағы сайт әкімшілігімен байланысуыңызды сұраймыз.";

$lang['email_changed_adm'] = "Сіздің e-mail әкімшілік тарапынан өзгертілді";
$lang['email_changed'] = "Сіздің e-mail өзгертілді";
$lang['email_change_requested'] = "Сұралған e-mail өзгертілді";
$lang['confirm_email_sent'] = "Мәліметтер нақтылану үшін Сіздің жаңа e-mail-ыңызға жіберілді. Өзгерістерді нақтылау үшін біз жіберген хаттағы активация сілтемесін пайдалануыңызды сұраймыз.";
$lang['error_confirmation_link'] = "Нақтылау сілтемесі дұрыс емес. Біз жіберген хаттағы активация сілтемесін пайдаланыңыз.";
$lang['error_confirmation_info'] = "Нақтылауға арналған мәліметтер дұрыс емес. Мүмкін Сіз өзгерістерді нақтылаған шығарсыз?";
$lang['confirm_passw'] = "Праольді нақтылауыңызды сұраймыз";
$lang['error_passw_mismatch'] = "Пароль жоғалып кетті";
$lang['passw_changed'] = "Пароль өзгертілді";
$lang['access_info_changed'] = "Қолжетімділік ақпараттары өзгертілген";
$lang['error_user_not_found'] = "Мұндай e-mail-ді пайдаланушы табылмады";
$lang['error_security_question'] = "Парольді енгізуге арналған құпия сұрақты дұрыс көрсетпедіңіз. $admin_email мекен-жайындағы сайт әкімшілігімен байланысуыңызды сұраймыз";
$lang['error_answer'] = "Жауап дұрыс емес";
$lang['passw_reset'] = "Сіздің пароліңіз өзгертілген. Поштаңызды тексеріңізді және кіру үшін жаңа парольді пайдалануыңызды сұраймыз";

$lang['login'] = 'Пайдаланушының аты';
$lang['password'] = 'Пароль';
$lang['error_auth']  = "Пайдаланушының аты немесе пароль дұрыс емес!";

$lang['forgot_password'] = 'Парольді ұмыттыңыз ба?';
$lang['register'] = 'Тіркелу';

$lang['male'] = "Ер";
$lang['female'] = "Әйел";

$lang['name'] = 'Аты';
$lang['description'] = 'Қысқаша сипаттамасы';

$lang['category_tags'] = 'Топтаманың кілт сөздері';

$lang['to_delete'] = "жою";
$lang['to_edit'] = "түзету";
$lang['to_add'] = "қосу";
$lang['date'] = "Күні";
$lang['rate'] = "Голосов";

$lang['competitions'] = "Байқаулар";
$lang['comp_sort_by'] = "Топтастыру";
$lang['comp_sort_by_date'] = "Ең соңғылары";
$lang['comp_sort_by_rate'] = "Ең әйгілілері";
$lang['comp_sort_by_name'] = "A-Я";
$lang['comp_sort_by_name_desc'] = "Я-А";
$lang['comp_sort_by_relevance'] = "Сәйкестік";
$lang['faq'] = "FAQ";
$lang['contacts'] = "Байланыстар";
$lang['help'] = "Көмек";
$lang['exit'] = "Шығу";
$lang['enter'] = "Кіру";
$lang['categories'] = "Бөлімдер";

$lang['search'] = "Іздеу";
$lang['search_key_word'] = "Кілт сөздер";
$lang['search_in_section'] = "Бөлімнен іздеу";

$lang['user_all_photos'] = "Барлық фотосуреттер";
$lang['user_comments'] = "Сын-пікірлер";
$lang['user_views'] = "Көрулер";
$lang['user_albums'] = "Альбомдар";
$lang['user_photos'] = "Фотосурет";
$lang['title_comments']="Сын-пікір:";
$lang['user_photo_properties'] = "Фотосурет сипаты";
$lang['user_photo_title'] = "Фотосурет атауы";
$lang['user_photo_category'] = "Бөлім";
$lang['user_photo_album'] = "Альбом";
$lang['user_photo_competitions'] = "Байқауға жіберу";
$lang['user_photo_album_main'] = "Альбом үшін басты фотосурет ретінде қолдану";
$lang['user_photo_page_main'] = "Басты парақтың тақырыптық жолағында көрсету";
$lang['user_photo_upload'] = "Фотосурет жүктеу";
$lang['user_photo_file'] = "Файл";
$lang['user_photo_erotic'] = "Эротикалық сипатқа ие";
$lang['user_photo_description'] = "Сипаттамасы";
$lang['user_photo_allowed'] = "Арналған көрулер";
$lang['user_photo_bulk_upload'] = "Бірнеше фотосуреттерді қатар жүктеу үшін оларды өз компьютеріңізде кез келген ZIP, RAR форматтарында мұрағаттаңыз";
$lang['user_photo_5_upload'] = "На конкурс можно загружать до 5 фотографий";
$lang['user_photo_interests'] = "Менің қызығушылықтарым";
$lang['user_photo_about'] = "Қысқаша өзім туралы мәлімет";
$lang['user_photo_email'] = "E-mail:";
$lang['user_photo_contacts'] = "Менің байланыс мәліметтерім";
$lang['user_photo_profile'] = "Профиль";
$lang['upload'] = "Жүктеу";
$lang['cancel'] = "Болдырмау";    
$lang['user_photo_uploaded_types'] = 'Суреттердің барлық файлдары';
$lang['user_photo_uploaded_text'] = 'Таңдау';

$lang['page_not_found'] = "<strong><em>404</em> Парақ табылмады</strong>";
$lang['file_not_found'] = "<strong><em>404</em> Фотосурет табылмады</strong>";

$lang['photo_deleted'] = "<strong>Фотосурет жойылды</strong>";
$lang['photo_declined'] = "<strong>Фотосурет қабылданбады</strong>";
$lang['photo_not_approved'] = "<strong>Фотосурет әлі модерациядан өткен жоқ</strong>";
$lang['abum_not_approved'] = "<strong>Альбом әлі модерациядан өткен жоқ</strong>";
$lang['photo_not_good'] = "<strong>Качество фотографии недостаточно хорошее</strong>";

$lang['deleted_photos_albums'] = "Жойылған альбомдар мен фотосуреттер";
$lang['restore_photo'] = "Жойылған суретті қалпына келтіру";
$lang['remove_photo'] = "Суретті жою";
$lang['remove_album'] = "Удалить альбом";
$lang['restore_album'] = "Восстановить удаленный альбом";

$lang['js_label_FileEmpty'] = "Жүктеу файлы таңдалған жоқ";
$lang['js_label_PasswordEmpty'] = "Парольді толтыру міндетті болып табылады";
$lang['js_label_ConfirmationEmpty'] = "Нақтылауды толтыру міндетті болып табылады";
$lang['js_label_PwsConfDifferent'] = "Енгізілген пароль мен нақтылау сәйкес келмейді";
$lang['js_label_PwsConfDifferent_cur'] = "Енгізілген пароль  мен текущий пароль сәйкес келмейді";
$lang['js_label_ImgSubmitFailed'] = "Сурет жүктелмеді";
$lang['js_label_EmptyAlbumTitle'] = "У Вас нет альбомов. Необходимо создать альбом перед загрузкой фотографий.";
$lang['js_label_DeleteConfirmation'] = "Сенімдісіз бе?";
$lang['js_label_DeleteAlbumConfirmation'] = "Таңдалған альбомды жою керек пе?";

$lang['js_label_EmptyTitle'] = "Атауын енгізуіңіз керек";
$lang['js_label_NotValidTitle'] = "Атауында рұқсат етілмеген таңбалар бар";
$lang['js_label_EmptyStartTime'] = "Басталу күнін толтыру керек";
$lang['js_label_EmptyEndTime'] = "Аяқталу күнін толтыру керек";
$lang['js_label_EmptyDescription'] = "Сипаттамасын толтыру керек";

$lang['deleted_warnung_title'] = "Назар аударыңыз!";
$lang['deleted_warnung'] = "Жойылған фотосуреттер мен альбомдар мұнда 30 күн ғана сақталады. Кейін олар күндеікті серверіндегі сақтау қорынан да жойылады.";

$lang['error_heading']  = "Қателік бар:";
$lang['error_mailing']  = "Ақпаратты поштамен жіберуде қателік туындады";
$lang['no_photo']  = "Соңғы аптада жаңа фотосуреттер жоқ";
$lang['new_photo']  = "Жаңа түсірілімдер";
$lang['only_for_author']  = "Фотосурет авторы ғана көре алады"; 
$lang['no_flash'] ="<a href='http://www.adobe.com/go/getflash/'> сайтында Flash-элементтер дұрыс көрсетілуі үшін Flash-ойнатылымын</a> орнату қажет";
$lang['welcome']  = "Қош келдіңіз!";  
$lang['ok_redirect']  = "Байқауға жұмыс жүктеу үшін сілтеме бойынша әрекет етіңіз";  

$lang['work_added']  = "Сіздің фотосуретіңіз қабылданды. Модератор тарапынан тексеруден өткен соң, Сіздің e-mail-ыңызға фотосуретіңіздің байқауға қатысуға қабылданғандығы туралы хат жіберіледі.";  
$lang['comp_continue']  = 'Егер тағы бір фотосурет қосқыңыз келсе, "Жалғастыру" батырмасын басыңыз';  
$lang['email_in_use']  = "Бұл e-mail басқа аккаунтты тіркеу барысында қолданылған";  
$lang['remember_me'] = "Мені есте сақтау";
$lang['forget_pwd'] = "Парольді ұмыттыңыз ба?";
$lang['all_albums'] = "Барлық альбомдар";
$lang['New_password'] = "Жаңа пароль";
$lang['New_password_confirmation'] = "Парольді нақтылаңыз";
$lang['wrong_pwd'] = "Пароль дұрыс емес!";
$lang['my_photo'] = "Менің фотосуреттерім";
$lang['my_profile'] = "Менің профайлым";
$lang['add_photo'] = "Фотосурет қосу";
$lang['agreement'] = "Келісім";
$lang['command'] = "Ұжым";
$lang['footer_text'] = "";
$lang['emailinuse'] = "Мұндай e-mail қолданыста бар";
$lang['upgrade_browser'] = "Сайт Internet Explorer 6-шы нұсқа браузерінде дұрыс жұмыс істемейді. Өз браузеріңізді жаңартыңыз немесе Firefox, Opera, Safari, Chrome браузерлерін пайдаланыңыз.";
$lang['error_heading'] = "Қателік бар!";
$lang['lot_of_photos'] = "Бір байқауға 5 фотосуреттен артық жіберуге болмайды!";
$lang['erotic_banned'] = "Бұл топтамадағы фотосуреттерді тек тіркелген Пайдаланушылар ғана көре алады, достигшим 18 лет";
$lang['comp_openall'] = "Барлығын ашу";
$lang['comp_closeall'] = "Барлығын жасыру ";
$lang['upload_warn'] = "Фотосурет жүктеліп жатыр, жүктелу аяқталмайынша парақты жаңартпаңыз!";
$lang['upload_place']  = "Сіздің фотосуретіңіз қабылданды:";
$lang['upload_gen_err']= "Генерацияда қателік бар!";
$lang['upload_in']     = "Файл қабылданды, өңделіп жатыр, жүктелу аяқталмайынша парақты жаңартпаңыз...";
$lang['bibb_password'] = "Пароль";
$lang['bibb_nik'] = "Пайдаланушының аты";
$lang['bibb_email']= "E-mail";
$lang['bibb_password_confirm']= "Парольді нақтылаңыз";
$lang['month_1']         = "қаңтар";
$lang['month_2']         = "ақпан";
$lang['month_3']         = "наурыз";
$lang['month_4']         = "сәуір";
$lang['month_5']         = "мамыр";
$lang['month_6']         = "маусым";
$lang['month_7']         = "шілде";
$lang['month_8']         = "тамыз";
$lang['month_9']         = "қыркүйек";
$lang['month_10']        = "қазан";
$lang['month_11']        = "қараша";
$lang['month_12']        = "желтоқсан";

$lang['settings_save_well'] = "өзгерістер сақталған";