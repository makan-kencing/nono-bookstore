<?php

namespace App\Mailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

readonly class MailService
{
    private PHPMailer $mailer;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host = getenv('MAIL_HOST');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = getenv('MAIL_USERNAME');
        $this->mailer->Password = getenv('MAIL_PASSWORD');
        $this->mailer->SMTPSecure = getenv('MAIL_ENCRYPTION') ?: PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = (int)getenv('MAIL_PORT');

        $this->mailer->setFrom(
            getenv('MAIL_FROM_ADDRESS'),
            getenv('MAIL_FROM_NAME')
        );
    }

    public function sendMail(string $to, string $subject, string $body): bool
    {
        try {
            $this->mailer->clearAllRecipients();
            $this->mailer->addAddress($to);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Mailer Error: {$e->getMessage()}");
            return false;
        }
    }
}
