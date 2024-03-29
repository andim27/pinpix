<?php
$site_name = str_replace('http://','',trim(base_url(),'/'));

$lang['activation_subject'] = "Подтвердите регистрацию на $site_name";
			
$lang['activation_message'] = '
<p>Здравствуйте, уважаемый %login%.</p>
<p>Благодарим Вас за регистрацию на сайте '.$site_name.'.</p>
<p>Для подтверждения регистрации и активации Вашей учетной записи пройдите по следующей ссылке:<br/>
<a href="%activation_url%">%activation_url%</a></p>
<p>Подтверждение требуется для исключения несанкционированного использования Вашего адреса электронной почты.</p>
<p>С уважением,<br/>
Команда '.$site_name.'</p>
';

$lang['remember_subject'] = "Восстановление пароля на $site_name";
			
$lang['remember_message'] = '
<p>Здравствуйте, уважаемый %login%.</p>
<p>Используйте следующие данные для входа на сайт '.$site_name.':<br/>
Имя пользователя: %login%<br/>
Пароль: %password%</p>
<p>С уважением,<br/>
Команда '.$site_name.'</p>
';
