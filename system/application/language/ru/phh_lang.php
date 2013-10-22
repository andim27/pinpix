<?php
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email');
// Retrieve a config item named site_name contained within the blog_settings array
$site_title = $ci->config->item('site_name', 'blog_settings');

$lang['pages'] = "Страницы: ";

$lang['search'] = "поиск";

$lang['mod_state_new'] = 'Новые';
$lang['mod_state_approved'] = 'Утвержденные';
$lang['mod_state_featured'] = 'Отмеченные';
$lang['mod_state_declined'] = 'Отклоненные';

$lang['registration'] = "Регистрация";

$lang['reg_step_1'] = "Шаг 1: Введите имя пользователя и e-mail";
$lang['username_field'] = "Имя пользователя:";
$lang['email_field'] = "E-mail:";

$lang['reg_step_2'] = "Шаг 2: Проверка и активация пользователя";
$lang['remember_sent'] = "Письмо для подтверждения регистрации и активации выслано на указанный e-mail.";
$lang['activation_sent'] = "Пожалуйста, проверьте указанный e-mail на предмет письма о регистрации и ссылкой для активации. Для подтверждения регистрации и активации необходимо перейти по ссылке в письме.";

$lang['reg_step_3'] = "Шаг 3: Успешная регистрация и активация";
$lang['start_using_account'] = "Регистрация и активация прошла успешно. Имя пользователя активно. Пожалуйста, проверьте Ваш e-mail - вам выслано письмо с данными для доступа к сайту.";
$lang['start_using_account_without_active'] = "На Ваш e-mail было отправлено письмо с данными для входа на сайт";

$lang['collect_information_for_profile'] = "Дополнительная информация";
$lang['error_login_empty']  = "Имя пользователя не указано!";
$lang['error_login_wrong']  = "Неверное имя пользователя или пароль!";
$lang['error_login_used']  = "Указанное имя пользователь уже используется. Пожалуйста, выберите другое.";

$lang['login_input']  = "Имя пользователя";
$lang['email_input']  = "E-mail";
$lang['error_email_format']  = "Неверный формат E-mail";
$lang['error_email_used']  = "Указанный E-mail уже используется. Возможно Вы <a href='/forgot-password' title='Восстановление пароля'>забыли Ваш пароль?</a>";
$lang['banned']  = "Имя пользователя заблокировано!";
$lang['not_activated']  = "Имя пользователя еще не активировано! Пожалуйста, перейдите по ссылке в письме активации, высланное на указанный e-mail.";
$lang['confirm_reg'] = "Подтвердите свою регистрацию на $site_title";
$lang['error_data_saving'] = "Проблема с сохранением Ваших данных. Пожалуйста, попробуйте позже или свяжитесь с администратором сайта по $admin_email";
$lang['error_activation_link'] = "Неверная ссылка для активации. Пожалуйста, используйте ссылку для активации из письма, которое мы выслали на Ваш E-mail";
$lang['error_activation_info'] = "Неверная информация для активации. Если Вы уже активировались, проверьте E-mail и Ваш пароль или свяжитесь с администратором сайта по $admin_email";
$lang['reg_success'] = "Регистрация на $site_title прошла успешно";

$lang['error_activation_process'] = "Во время активации возникли ошибки. Пожалуйста, свяжитесь с администратором сайта по $admin_email";

$lang['email_changed_adm'] = "Ваш E-mail изменен администратором";
$lang['email_changed'] = "Ваш E-mail изменен";
$lang['email_change_requested'] = "Запрошенный E-mail изменен";
$lang['confirm_email_sent'] = "Информация для подтверждения отправленна на Ваш новый E-mail. Для подтверждения изменений необходимо перейти по ссылке указанной в письме.";
$lang['error_confirmation_link'] = "Неверная ссылка для подтверждения. Для подтверждения изменений необходимо перейти по ссылке указанной в письме, высланном на E-mail.";
$lang['error_confirmation_info'] = "Неверная информация для подтверждения. Возможно,&nbsp;Вы уже подтвердили изменения?";
$lang['confirm_passw'] = "Пожалуйста,подтвердите пароль";
$lang['error_passw_mismatch'] = "Утерян пароль";
$lang['passw_changed'] = "Пароль изменен";
$lang['access_info_changed'] = "Изменена информация для доступа";
$lang['error_user_not_found'] = "Пользователь с таким e-mail не найден";
$lang['error_security_question'] = "Вы неверно указали секретный вопрос для ввода пароля. Пожалуйста, свяжитесь с администратором сайта по $admin_email";
$lang['error_answer'] = "Ответ неверный";
$lang['passw_reset'] = "Ваш пароль изменен. Пожалуйста,&nbsp;проверьте E-mail и используйте новый пароль для доступа.";

$lang['login'] = "Имя пользователя ";
$lang['password'] = "Пароль ";
$lang['error_auth']  = "Неверное имя пользователя или пароль!";

$lang['forgot_password'] = "Забыли пароль?";
$lang['register'] = "Регистрация";

$lang['male'] = "Мужской";
$lang['female'] = "Женский";

$lang['name'] = "Имя";
$lang['description'] = "Краткое описание";

$lang['category_tags'] = "Ключевые слова категории";

$lang['to_delete'] = "удалить";
$lang['to_edit'] = "редактировать";
$lang['to_add'] = "добавить";
$lang['date'] = "Дата";
$lang['rate'] = "Голосов";

$lang['competitions'] = "Конкурсы";
$lang['comp_sort_by'] = "Сортировка";
$lang['comp_sort_by_date'] = "Самые последние";
$lang['comp_sort_by_rate'] = "Самые популярные";
$lang['comp_sort_by_name'] = "A-Я";
$lang['comp_sort_by_name_desc'] = "Я-А";
$lang['comp_sort_by_relevance'] = "Соответствие";
$lang['faq'] = "FAQ";
$lang['contacts'] = "Контакты";
$lang['help'] = "Помощь";
$lang['exit'] = "Выход";
$lang['enter'] = "Вход";
$lang['categories'] = "Разделы";

$lang['search'] = "Поиск";
$lang['search_key_word'] = "Ключевые слова";
$lang['search_in_section'] = "Поиск в разделе";

$lang['user_all_photos'] = "Все фото подряд";
$lang['user_comments'] = "Комментариев";
$lang['user_views'] = "Просмотров";
$lang['user_albums'] = "Альбомов";
$lang['user_photos'] = "Фото";
$lang['title_comments']="Комментарии:";
$lang['user_photo_properties'] = "Свойства фото";
$lang['user_photo_title'] = "Название фото";
$lang['user_photo_category'] = "Раздел";
$lang['user_photo_album'] = "Альбом";
$lang['user_photo_competitions'] = "Отправить на конкурс";
$lang['user_photo_album_main'] = "Сделать главной для альбома";
$lang['user_photo_page_main'] = "Показать в заголовке главной страницы";
$lang['user_photo_upload'] = "Загрузить фото";
$lang['user_photo_file'] = "Файл";
$lang['user_photo_erotic'] = "Эротическое содержание";
$lang['user_photo_description'] = "Описание";
$lang['user_photo_allowed'] = "Просмотр для";
$lang['user_photo_bulk_upload'] = "Для массовой загрузки фотографий, просто упакуйте их на своем компьютере в любой из архивов .ZIP, .RAR";
$lang['user_photo_5_upload'] = "На конкурс можно присылать не больше 5 фотографий!";
$lang['user_photo_interests'] = "Интересы";
$lang['user_photo_about'] = "Немного о себе";
$lang['user_photo_email'] = "E-mail:";
$lang['user_photo_contacts'] = "Контакты";
$lang['user_photo_profile'] = "Профиль";
$lang['upload'] = "Загрузить";
$lang['cancel'] = "Отмена";
$lang['user_photo_uploaded_types'] = 'Все файлы рисунков';
$lang['user_photo_uploaded_text'] = 'Выбрать';
$lang['page_not_found'] = "<strong><em>404</em> Страница не найдена</strong>";
$lang['file_not_found'] = "<strong><em>404</em> Фотография не найдена</strong>";
$lang['photo_deleted'] = "<strong>Фотография была удалена</strong>";
$lang['photo_declined'] = "<strong>Фотография была отклонена</strong>";
$lang['photo_not_approved'] = "<strong>Фотография еще не прошла модерацию</strong>";
$lang['abum_not_approved'] = "<strong>Альбом еще не прошел модерацию</strong>";
$lang['photo_not_good'] = "<strong>Качество фотографии недостаточно хорошее</strong>";

$lang['deleted_photos_albums'] = "Удаленные альбомы и фотографии";
$lang['restore_photo'] = "Восстановить удаленную фотографию";
$lang['remove_photo'] = "Удалить фотографию";
$lang['remove_album'] = "Удалить альбом";
$lang['restore_album'] = "Восстановить удаленный альбом";

$lang['js_label_FileEmpty'] = "Не выбран файл для загрузки";
$lang['js_label_PasswordEmpty'] = "Необходимо заполнить пароль";
$lang['js_label_ConfirmationEmpty'] = "Необходимо заполнить подтверждение";
$lang['js_label_PwsConfDifferent'] = "Введённые пароль и подтверждение не совпадают";
$lang['js_label_PwsConfDifferent_cur'] = "Введённые пароль и текущий пароль не совпадают";
$lang['js_label_ImgSubmitFailed'] = "Не удалось загрузить рисунок";
$lang['js_label_EmptyAlbumTitle'] = "У Вас нет альбомов. Необходимо создать альбом перед загрузкой фотографий";
$lang['js_label_DeleteConfirmation'] = "Вы уверены?";
$lang['js_label_DeleteAlbumConfirmation'] = "Удалить выбранный альбом?";
$lang['js_label_EmptyTitle'] = "Необходимо заполнить название.";
$lang['js_label_NotValidTitle'] = "Название сожержит недопустимые символы";
$lang['js_label_EmptyStartTime'] = "Необходимо заполнить дату начала";
$lang['js_label_EmptyEndTime'] = "Необходимо заполнить дату конца";
$lang['js_label_EmptyDescription'] = "Необходимо заполнить описание";
$lang['deleted_warnung_title'] = "Внимание!";
$lang['deleted_warnung'] = "Удаленные фотографии и альбомы хранятся только 30 дней. После этого они будут полностью удалены.";
$lang['error_heading']  = "Возникла ошибкa: ";
$lang['error_mailing']  = "Возникла ошибкa при отправке информации по e-mail";
$lang['no_photo']  = "Нет новых фотографий за последнюю неделю";
$lang['new_photo']  = "Новые поступления";
$lang['only_for_author']  = "Доступ открыт только для автора фотографии";
$lang['no_flash'] ="Для отображения <br>Flash-элементов на сайте,<br/><a href='http://www.adobe.com/go/getflash/'>необходимо установить<br> Flash-проигрыватель</a>";
$lang['welcome']  = "Добро пожаловать!";
$lang['ok_redirect']  = "Для загрузки фотографий для участия в конкурсе, пройдите по ссылке ";

$lang['work_added']  = "Ваша фотография получена. После проверки модератором, на Ваш e-mail будет отправлено сообщение об участии фотографии в конкурсе.";  
$lang['comp_continue']  = 'Если Вы хотите добавить еще одну фотографию, нажмите "Продолжить"';

$lang['email_in_use']  = "Указанный E-mail уже используется";  

$lang['remember_me'] = "запомнить меня";
$lang['forget_pwd'] = "Забыли пароль?";
$lang['all_albums'] = "Все альбомы";

$lang['New_password'] = "Новый пароль";
$lang['New_password_confirmation'] = "Подтвердите пароль";
$lang['wrong_pwd'] = "Неверный пароль!";

$lang['my_photo'] = "Мои фото";
$lang['my_profile'] = "Мой профиль";
$lang['add_photo'] = "Добавить фото";

$lang['agreement'] = "Соглашение";
$lang['command'] = "Команда";
$lang['footer_text'] = "Footer text";
$lang['emailinuse'] = "Указанный E-mail уже используется";

$lang['upgrade_browser'] = "Сайт не работает корректно в браузере Internet Explorer версии 6. <br> Обновите свой браузер или используйте браузеры Firefox, Opera, Safari, Chrome.";
$lang['error_heading'] = "Возникла ошибка! ";

$lang['lot_of_photos'] = "На конкурс можно присылать не больше 5 фотографий!";
$lang['erotic_banned'] = "Фотографии из данной категории разрешается просматривать только зарегистрированным пользователям, достигшим 18 лет";

$lang['comp_openall'] = "Раскрыть все";
$lang['comp_closeall'] = "Скрыть все";
	
$lang['upload_warn']   = "Идет загрузка фото, не обновляйте страницу до завершения загрузки!";
$lang['upload_place']  = "Ваше фото принято:";
$lang['upload_gen_err']= "Ошибка генерации изображения!";
$lang['upload_in']     = "Файл принят, не обновляйте страницу, идет обработка ...";

$lang['bibb_password'] = "Пароль";
$lang['bibb_nik'] = "Имя пользователя";
$lang['bibb_email']= "E-mail";
$lang['bibb_password_confirm']= "Подтвердить пароль ";
$lang['month_1']         = "январь";
$lang['month_2']         = "февраль";
$lang['month_3']         = "март";
$lang['month_4']         = "апрель";
$lang['month_5']         = "май";
$lang['month_6']         = "июнь";
$lang['month_7']         = "июль";
$lang['month_8']         = "август";
$lang['month_9']         = "сентябрь";
$lang['month_10']        = "октябрь";
$lang['month_11']        = "ноябрь";
$lang['month_12']        = "декабрь";

$lang['settings_save_well'] = "изменения сохранены";