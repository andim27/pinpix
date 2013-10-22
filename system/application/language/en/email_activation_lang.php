<?php
$site_name = str_replace('http://','',trim(base_url(),'/'));

$lang['activation_subject'] = "Подтвердите регистрацию на $site_name";
			
$lang['activation_message'] = '
<p>Здравствуйте, %login%.</p>
<p>Спасибо за регистрацию на  '.$site_name.'.<br/>
Чтобы закончить регистрацию и активировать Ваш аккаунт, перейдите по ссылке ниже:<br/>
<a href="%activation_url%">%activation_url%</a><br/>
Надеемся скоро Вас увидеть, <br/>
'.$site_name.' администрация</p>
';

$lang['remember_subject'] = "Восстановление пароля на $site_name";
			
$lang['remember_message'] = '
<p>Здравствуйте, %login%.</p>
<p>Ваш акккаунт на сайте'.$site_name.'
будет доступен под паролем: <br/>
%password%<br/>
Надеемся скоро Вас увидеть, <br/>
Администрация '.$site_name.'</p>
';
