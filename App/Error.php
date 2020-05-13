<?php

namespace App;

class Error
{
    public function info()
    {
        $status = $_SERVER['REDIRECT_STATUS'];
        $codes = array(
            400 => array('400 Плохой запрос', 'Запрос не может быть обработан из-за синтаксической ошибки.'),
            401 => array('401 Вы не авторизованны'),
            403 => array('403 Запрещено', 'Сервер отказывает в выполнении вашего запроса.'),
            404 => array('404 Не найдено', 'Запрашиваемая страница не найдена на сервере.'),
            405 => array('405 Метод не допускается', 'Указанный в запросе метод не допускается для заданного ресурса.'),
            408 => array('408 Время ожидания истекло', 'Ваш браузер не отправил информацию на сервер за отведенное время.'),
            500 => array('500 Внутренняя ошибка сервера', 'Запрос не может быть обработан из-за внутренней ошибки сервера.'),
            502 => array('502 Плохой шлюз', 'Сервер получил неправильный ответ при попытке передачи запроса.'),
            504 => array('504 Истекло время ожидания шлюза', 'Вышестоящий сервер не ответил за установленное время.'),
        );

        $title = $codes[$status][0];
        $message = $codes[$status][1];
        if ($title == false || strlen($status) != 3) {
            $message = 'Код ошибки HTTP не правильный.';
        }

        echo '<h1>Внимание! Обнаружена ошибка ' . $title . '!</h1>
        <p>' . $message . '</p>';
    }
}