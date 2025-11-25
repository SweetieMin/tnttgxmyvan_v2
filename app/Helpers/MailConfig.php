<?php

namespace App\Helpers;

use App\Models\MailSetting;

class MailConfig
{
    public static function apply()
    {
        $s = MailSetting::first();

        config([
            'mail.default' => $s ? $s->mailer : env('MAIL_MAILER', 'log'),

            "mail.mailers.smtp.host" => $s ? $s->host : env('MAIL_HOST', '127.0.0.1'),
            "mail.mailers.smtp.port" => $s ? $s->port : env('MAIL_PORT', 2525),
            "mail.mailers.smtp.username" => $s ? $s->username : env('MAIL_USERNAME'),
            "mail.mailers.smtp.password" => $s ? decrypt($s->password) : env('MAIL_PASSWORD'),

            "mail.mailers.smtp.encryption" => $s ? $s->encryption : env('MAIL_ENCRYPTION'),

            "mail.from.address" => $s ? $s->from_address : env('MAIL_FROM_ADDRESS', 'hello@example.com'),
            "mail.from.name" => $s ? $s->from_name : env('MAIL_FROM_NAME', 'Example'),
        ]);
    }
}
