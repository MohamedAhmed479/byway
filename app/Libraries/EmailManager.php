<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailManager
{
    protected $email;

    public function __construct()
    {
        $provider = env('email.provider', 'mailtrap');

        $providerClass = "\\App\\Emails\\Providers\\" . ucfirst($provider) . "Provider";
        if (!class_exists($providerClass)) {
            throw new \Exception("Provider class {$providerClass} not found");
        }

        $providerInstance = new $providerClass();
        $config = $providerInstance->getConfig();

        $this->email = new Email();
        $this->email->initialize($config);
    }

    public function send($to, $subject, $body, $from = null, $fromName = null){
        $from = $from ?? env("email.mailtrap.SMTPUSER");
        $fromName = $fromName ?? env("app.Name");

        $this->email->setFrom($from, $fromName);
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($body);
        return $this->email->send();
    }
}