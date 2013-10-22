<?php

$lang['email_must_be_array'] = "Метод валидации email должен быть записан в массив.";
$lang['email_invalid_address'] = "Неверный адрес email: %s";
$lang['email_attachment_missing'] = "Невозможно определить местонахождение файла, прикрепленного к email: %s";
$lang['email_attachment_unreadable'] = "Невозможно открыть вложение: %s";
$lang['email_no_recipients'] = "Необходимо указать получаетелей в полях: To, Cc, или Bcc.";
$lang['email_send_failure_phpmail'] = "Нвозможно отправить сообщение используя PHP mail().  Возможно, Ваш сервер не сконфигурирован для использования данного метода.";
$lang['email_send_failure_sendmail'] = "Невозможно отправить сообщение используя PHP Sendmail. Возможно, Ваш сервер не сконфигурирован для использования данного метода.";
$lang['email_send_failure_smtp'] = "Невозможно отправить сообщение используя PHP SMTP. Возможно, Ваш сервер не сконфигурирован для использования данного метода.";
$lang['email_sent'] = "Ваше сообщение отправленно с использованием протокола: %s";
$lang['email_no_socket'] = "Невозможно открыть сокет для Sendmail. Пожалуйста, проверьте настройки.";
$lang['email_no_hostname'] = "Вы не указали SMTP hostname.";
$lang['email_smtp_error'] = "Произошла следующая ошибка SMTP: %s";
$lang['email_no_smtp_unpw'] = "Ошибка: Вы должны указать a SMTP username и password.";
$lang['email_failed_smtp_login'] = "Ошибка при отправке AUTH LOGIN команды: %s";
$lang['email_smtp_auth_un'] = "Ошибка аутентификации username: %s";
$lang['email_smtp_auth_pw'] = "Ошибка аутентификации password: %s";
$lang['email_smtp_data_failure'] = "Невозможно отправить данные: %s";


/* End of file email_lang.php */
/* Location: ./system/language/english/email_lang.php */