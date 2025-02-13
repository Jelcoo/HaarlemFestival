<?php

namespace App\Services;

use App\Config\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailService
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer();

        if (Config::getKey('APP_ENV') === 'development') {
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        }

        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->Host = Config::getKey('MAIL_HOST');
        $this->mailer->Username = Config::getKey('MAIL_USER');
        $this->mailer->Password = Config::getKey('MAIL_PASSWORD');
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $this->mailer->Port = Config::getKey('MAIL_PORT');

        $this->mailer->setFrom(Config::getKey('MAIL_USER'), Config::getKey('APP_NAME'));
    }

    public function addRecipient(string $to): self
    {
        $this->mailer->addAddress($to);

        return $this;
    }

    public function addAttachment(string $path): self
    {
        $this->mailer->addAttachment($path);

        return $this;
    }

    public function setContent(string $subject, string $body): self
    {
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;

        return $this;
    }

    public function send()
    {
        $this->mailer->send();
    }
}
