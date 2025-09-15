<?php

namespace App\Mailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

readonly class MailService
{
    /**
     * @throws Exception
     */
    public function makeMailer(): PHPMailer
    {
        $mailer = new PHPMailer(true);

        $mailer->isSMTP();
        $mailer->Host = getenv('MAIL_HOST');
        $mailer->SMTPAuth = true;
        $mailer->Username = getenv('MAIL_USERNAME');
        $mailer->Password = getenv('MAIL_PASSWORD');
        $mailer->SMTPSecure = getenv('MAIL_ENCRYPTION') ?: PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port = (int)getenv('MAIL_PORT');

        $mailer->setFrom(
            getenv('MAIL_FROM_ADDRESS'),
            getenv('MAIL_FROM_NAME')
        );

        return $mailer;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @return bool
     */
    public function sendMail(string $to, string $subject, string $body): bool
    {
        try {
            $mailer = $this->makeMailer();

            $mailer->clearAllRecipients();
            $mailer->addAddress($to);

            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = $body;
            $mailer->addCustomHeader('X-PM-Message-Stream', 'outbound');

            return $mailer->send();
        } catch (Exception $e) {
            error_log("Mailer Error: {$e->getMessage()}");
            return false;
        }
    }
}
