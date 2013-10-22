<?php
	$page_info = array();

	$_user_id = $this->db_session->userdata('user_id');
	$page_info['_user_id'] = $_user_id;
	
	$_link = "<a class='backtokonk_but' href = " . base_url() . "competition> Обратно в конкурс </a>";
				
	$page_info['title'] = 'Жюри конкурса «Казахстан в фокусе»';
	$page_info['content'] = 
	"
						<div class='roundedBlock'><div class='tr'><div class='br'><div class='bl'>
						<ul class='juryList'>
							<li class='title'><strong>PinPix</strong> Жюри</li>   
							<li><a href='#' class='avatara'><img src='"  . base_url()."images/Uvarow_small.jpg'>  </a> <strong><b>Уваров Евгений</b><br />г. Москва., Россия </strong> <em>C 2003 по 2008 год возглавлял ведущий российский журнал о фотографии Digital Photo. Последние шесть лет является председателем жюри конкурса «Фотофорум – Продукт года». С 2000-го года преподает фотографию. В настоящее время работает преподавателем отделения фотографии в Колледже кино, телевидения и мультимедиа ВГИК и руководит отделением рекламы.</em></li>
							<li><a href='#' class='avatara'><img src='"  . base_url()."images/avatars/_avatara.gif'> </a> <strong><b>Ушаков Владислав Александрович</b><br /> г. Бишкек., Кыргызстан<br />Генеральный директор «Союза фотожурналистов» Кыргызстана</strong> <em>Фототокорреспондент со стажем более 10 лет, работал в информационном Агенстве АКИ-пресс, нескольких газетах, был штатным фотографом при МИДе КР, в изданиях «Российская газета»- «Новый Кыргызстан» - журнал «Ракурс»- «Zentr.kg»- информационное агентство «24.kg».<br />
							В настоящий момент является фотографом мэрии города Бишкека и гос.предприятия «Кыргыз Темир –Жолы»- агентства по Туризму а  также председателем Союза Фотожурналистов КР, автором и  координатором ряда интернет-проектов, связанных с фотографией (www.photo.kg  - www.photoasia.ru  - www.report.kg – www.gde.kg – www.aitmatov.kg -www.show.kg – www.foto.kg – www.photocross.kg).<br />
							Контакты:<br />Email:  photo.kg@mail.ru</em></li>												
							<li><a href='#' class='avatara'><img src='"  . base_url()."images/avatars/_avatara.gif'> </a> <strong><b>Бабкин В.</b><br />г.Алматы, Казахстан</strong> <em>Профессиональный фотограф, главный редактор журнала </em></li>						
							<li><a href='#' class='avatara'><img src='"  . base_url()."images/demidov_small.jpg'> </a><strong><b>Демидов Алексей Игоревич</b><br />г.Алматы, Казахстан</strong><em>Рекламный/журнальный фотограф, имеющий свою студию и участвующий во многих арт-проектах.<br />
							Алексей начал заниматься фото сравнительно недавно. Все начиналось как хобби, которое незаметно переросло в главное дело жизни. Многочисленные приглашения фотографировать за деньги сподвигли бросить основную работу и начать заниматься только фотографией. <br />
							Контакты:<br />Email:  public@demidov.kz<br />Web-сайт:  www.demidov.kz</em></li>
						</ul>
					</div></div></div></div>
	" 
	. $_link;

	return $page_info;
/* EOF */