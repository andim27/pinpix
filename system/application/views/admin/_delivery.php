<?php include('head.php'); ?>
<body class="pix">
	<div id="pix">
		<?php include('header.php'); ?>
		<?php display_errors(); ?>
		<div id="mainContent">
			<div id="main_cont_body">
				<div id="c_box">
					<form action="<?=base_url()?>admin/delivery" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="delivery_send" value="true" />
						<div>
							<div style="margin-bottom:15px;">Здравствуйте!</div>
								<div style="margin-bottom:15px;">Команда Pinpix.kz рада сообщить об открытии нового конкурса под названием «Летняя игра»!</div>
								<div style="margin-bottom:15px;">Долгожданное лето наступило - пора отдыха, встреч, активности, игр и тепла! А какое лето без красивых фотографий?! Играйте, снимайте и присылайте нам свои яркие, красивые, солнечные фотографии в процессе летних игр. Футбол, волейбол, бадминтон, прятки, а может, это будет игра, которую вы придумали сами :).</div>
								<div style="margin-bottom:15px;">Нам очень хочется увидеть ваше летнее настроение! Чем ярче будут ваши работы, тем больше вероятность выиграть главный приз – красивейшую цифровую фоторамку :)</div>
								<div style="margin-bottom:15px;">Период проведения – 10 июня – 1 июля 2010 г. Подробнее о конкурсе <a href="<?=base_url()?>competition">тут</a></div>
								<div>С уважением,</div>
								<div>Команда <a style="text-decoration:none;" href="http://pinpix.kz" target=_blank>Pinpix.kz</a></div>							
						</div><br />
						<input type="submit" value="Отправить"/>
					</form>
				</div>
				<div style="color:red;"><?=$success?></div>
				<div style="color:red;">Список пользователей, учавствующих в рассылке(<?=count($users)?> человек):</div>
				<div style="color:red;">
					<?php
					$usr_str = "";
					if(!empty($users)) : foreach ($users as $user) {
						$usr_str .= "<div>". $user->login ." - ". $user->email ."</div>";
					} endif;
					echo $usr_str;	
					?>
				</div>
				<br /><br /><br /><br />
			</div>
		</div>
		<?php include('footer.php'); ?>
	</div>
</body>
</html>