<?php

namespace App\Emails\Providers;

class MailtrapProvider
{
    public function getConfig()
    {
        return [
            'protocol' => env("email.mailtrap.protocol", "smtp"),
            'SMTPHost' => env("email.mailtrap.SMTPHOST", ""),
            'SMTPPort' => (int) env("email.mailtrap.SMTPPORT", 587),
            'SMTPUser' => env("email.mailtrap.SMTPUSER", ""),
            'SMTPPass' => env("email.mailtrap.SMTPPASS", ""),
            'mailType' => 'html',
            'charset'  => 'utf-8',
            'newline'  => "\r\n",
        ];
    }
}