<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Pinpix.kz</title>
</head>
<body leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<div>
		<?=$photo_author_login?>,<br /><br />
		Пользователь <?=$comented_user_login?> ответил(-a) на Ваш комментарий к фотографии:<br />
		<a href="<?=base_url()?>photo/view/<?=$photo_id?>"><?=base_url()?>photo/view/<?=$photo_id?></a><br />
        <br>
        <div>
		    <div style="float:left;width:50px;"><hr style="background-color:black;border-width:0;color:black;height:1px;"></div>
    		<div style="clear: both;">Настройка уведомлений о комментариях доступна в разделе  <a href="<?=base_url()?>profile/edit">Мой профиль</a></div>
	    </div>
       <br />С уважением,<br />
		Команда <a style="text-decoration:none;" href="<?=base_url()?>" target=_blank>Pinpix.kz</a><br /><br />
	</div>

</body>
</html>