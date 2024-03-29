<?php
$site_name = str_replace('http://','',trim(base_url(),'/'));

$lang['activation_subject'] = "$site_name сайтында тіркелуді нақтылау";
			
$lang['activation_message'] = '
<p>Сәлеметсіз бе, құрметті %login%!</p>
<p>'.$site_name.' сайтында тіркелгеніңіз үшін Сізге алғысымызды білдіреміз!.</p>
<p>Тіркелуді нақтылау үшін және Өз тіркелгіңізді іске қосу үшін келесі сілтеме бойынша әрекет етіңіз:<br/>
<a href="%activation_url%">%activation_url%</a></p>
<p>Нақтылау Сіздің электронды поштаңыздың мекен-жайын заңсыз пайдалануға жол бермес үшін қажет.</p>
<p>Құрметпен,<br/>
'.$site_name.' ұжымы</p>
';

$lang['remember_subject'] = "$site_name сайтында парольді қалпына келтіру";
			
$lang['remember_message'] = '
<p>Здравствуйте, уважаемый %login%.</p>
<p>Сайтқа кіру үшін келесі мәліметтерді пайдаланыңыз:<br/>
Имя пользователя: %login%<br/>
Пароль: %password%</p>
<p>Құрметпен,<br/>
'.$site_name.' ұжымы</p>
';