<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

//$lang[''] = "";  //for copying

$lang['bibb_register'] = "Тіркелу";
$lang['bibb_enter'] = "Кіру";
$lang['bibb_terms'] = "18 қаңтар – 15 сәуір 2010 жыл";
$lang['bibb_comp'] = "Байқау";
$lang['bibb_comp_name'] = "Қазақстан фотосурет әлемінде";
$lang['bibb_login'] = "Пайдаланушының аты";
$lang['bibb_password'] = "Пароль";
$lang['bibb_nik'] = "Пайдаланушының аты";
$lang['bibb_email']= "E-mail";
$lang['bibb_password_confirm']= "Парольді нақтылаңыз";
$lang['bibb_contry']= "Мемлекетті";
$lang['bibb_birtdate']= "Туған күніңіз";
$lang['bibb_chouse_contry']= "Мемлекетті таңдаңыз";
$lang['bibb_city']= "Қала";
$lang['bibb_chouse_city']= "Қаланы таңдаңыз";
$lang['bibb_user_info']= "Өзіңіз туралы қысқаша мәлімет";
$lang['bibb_user'] = "Пайдаланушы";
$lang['bibb_exit'] = "Шығу";
$lang['error_auth']  = "Пароліңіз немесе логиніңіз дұрыс емес!";

$lang['conditions_text'] = "«Қазақстан фотосурет әлемінде» - Қазақстанды барынша дәл және заманауи сәнді үлгіде таныстыратын фотосурет байқауы. Аталмыш байқау фотосурет-әуесқойлар мен кәсіби фотографтар көзімен Қазақстанды баршаға таныстыру мақсатында ұйымдастырылып отыр. <br /><br />
Байқауға Қазақстанның тыныс-тіршілігін, сұлулығын, ерекшеліктерін, тарихын, осында өмір сүретін, жұмыс істейтін, жаңа Қазақстанның іргетасын қалаушы және өз Отанын шексіз сүйетін адамдар бейнеленген фотосуреттер жіберіңіздер. Байқауға қатысушы әрбір фотограф сүйікті Отанын өз елестетуі бойынша таныта алады ";
$lang['bibb_rules'] = "Байқаудың өтілу шарттары";

$lang['welcome']  = "Қош келдіңіз! Сіз жүйеге сәтті кірдіңіз!";  
$lang['ok_redirect']  = "Фотосурет жүктеу";  

$lang['upload_succ']  = "Рахмет, Сіздің жұмысыңыз байқауға қатысуға қабылданды. Тексеруден өткен соң, сіздің e-mail-ыңыз жұмыстың байқауға қатысатындығы туралы мәлімдемеге ие болады";
$lang['user_photo_upload'] = "Байқауға фотосурет жүктеу";
$lang['user_photo_upload_again'] = "Байқауға тағы да фотосурет жүктеу";
$lang['user_photo_file'] = "Файл";
$lang['user_photo_description'] = "Сипаттама";
$lang['user_photo_title'] = "Фотосуреттің атауы";
$lang['user_photo_5_upload'] = "Байқауға 5 фотосурет жүктеуге болады";

$lang['js_label_FileEmpty'] = "Жүктеу файлы көрсетілмеген";
$lang['js_label_EmptyTitle'] = "Атауын енгізу керек.";
$lang['js_label_NotValidTitle'] = "Атауында тыйым салынған таңбалар бар";
$lang['js_label_EmptyDescription'] = "Сипаттамасын толтыру керек.";
$lang['js_label_ImgSubmitFailed'] = "Сурет жүктелмеді";
$lang['lot_of_photos'] = "Байқауға 5 фотосуреттен артық жүктеуге болмайды! ";


$lang['demidov_name'] ='Демидов А., Қазақстан, Алматы қ-сы';
$lang['demidov_desc'] ='Кәсіби фотограф (жарнама/журналдар)';

$lang['ywakov_name'] ='Ушаков В.А., Қырғызстан, Бішкек қ-сы';
$lang['ywakov_desc'] ='«Қырғызстан фототілшілер Одағының» бас директоры';

$lang['babkin_name'] ='Бабкин В., Қазақстан, Алматы қ-сы';
$lang['babkin_desc'] ='«Афиша» журналының бас редакторы, кәсіби фотограф';

$lang['korolev_name'] ='Королев Н.В., Ресей, Мәскеу қ-сы';
$lang['korolev_desc'] ='«Российская газета» Халықаралық жобалар Дирекциясының фоторедакторы';

$lang['pix_juri' ] ='Әділ-қазылары';
$lang['pix_prize'] ='Жүлделер';

$lang['eos'] ='<i>Canon EOS 500D</i> айналы сандық фотокамера';
$lang['pwg'] ='<i>Canon PowerShot G1</i> сандық фотокамера';
$lang['bag'] ='<i>Canon</i> сандық айналы фотокамераларына арналған сөмке';
$lang['footer'] ='Жүлделерді ұсынған';

$lang['cancel'] = 'Болдырмау';
$lang['req']	= "Толтыру міндетті";
$lang['juri_page']	= "\"Қазақстан фотосурет әлемінде\" байқауының әділ-қазылары";
$lang['juri_rev']	= "Байқауға оралу";
$lang['upgrade_browser'] = "Сайт Internet Explorer 6-шы нұсқа браузерінде дұрыс жұмыс істемейді. Өз браузеріңізді жаңартыңыз немесе Firefox, Opera, Safari, Chrome браузерлерін пайдаланыңыз";
$lang['partner_cannon']  = " компаниясымен серіктес ";
$lang['user_support'] = "Пайдаланушыларды қолдау: ";
$lang['no_flash'] ="Сайттағы Flash-элементтер<br> анық көрінуі үшін <br><a href='http://www.adobe.com/go/getflash/'>Flash-ойналымды орнату қажет</a>";

/*** News block*/
$lang['news_title'] = "Жаңалықтар";
$lang['news_1'] = "Фотобайқау 15 сәуірге дейін ұзартылды! Қар.";
$lang['news_1_continue'] = "Ережелер";
$lang['news_2'] = "Pinpix – ҚР-дағы мүмкіндіктері шектеулі адамдардың мәселелеріне қоғам назарын аударумен айналысатын әлеуметтік жобаның қолдаушысы.";
$lang['news_2_continue'] = "Толығырақ...";