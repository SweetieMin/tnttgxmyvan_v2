<?php

namespace App\Helpers;

use App\Models\MailSetting;

class MailConfig
{
    public static function apply()
    {
        $s = MailSetting::first();

        if (!$s) return;

        config([
            'mail.default' => $s->mailer,
            
            "mail.mailers.smtp.host" => $s->host,
            "mail.mailers.smtp.port" => $s->port,
            "mail.mailers.smtp.username" => $s->username,
            "mail.mailers.smtp.password" => $s->password,
            "mail.mailers.smtp.encryption" => $s->encryption,

            "mail.from.address" => $s->from_address,
            "mail.from.name" => $s->from_name,
        ]);
    }
}
