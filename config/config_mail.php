<?php

return $config_mail = [
    'mail' => [
        'SMTPDebug' => 'SMTP::DEBUG_SERVER',
        'Host' => 'smtp.yandex.ru',
        'SMTPAuth' => true,
        'Username' => 'mailbot@memory-lane.ru',
        'Password' => 'Mailer',
        'SMTPSecure' => 'PHPMailer::ENCRYPTION_SMTPS',
        'Port' => 465
        ]
];