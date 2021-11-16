<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Psr\Container\ContainerInterface;
use Smarty;

class SendMail
{
    public $mail;
    /**
     * @var \Smarty
     */
    private $view;
    private $siteName;
    private $siteUrl;
    private $contactName;
    private $contactEmail;

    public function __construct(ContainerInterface $container, Smarty $view)
    {
        $this->settings = $container->get('settings');
        $this->view = $view;
        $smtp = $this->settings['smtp'];

        $this->contactName = $smtp['name'];
        $this->contactEmail = $smtp['email'];
        $this->siteName = $_ENV['SITE_NAME'];
        $this->siteUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

        $mail = new PHPMailer(true);

        if (gethostname() == 'localhost') {
            $mail->isMail();
        } else {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Password = $smtp['password'];
            $mail->Username = $smtp['email'];
            $mail->Host = $smtp['host'];
        }

        $mail->setFrom($smtp['email'], $smtp['name']);
        $mail->addReplyTo($smtp['email'], $this->smtp['name']);

        // Content
        $mail->isHTML(true);

        $this->mail = $mail;
    }

    public function send(array $data)
    {

        $this->mail->clearAllRecipients();

        extract($data);

        try {
            $this->mail->addAddress($email, $name);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $message;
            $this->mail->send();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mail->ErrorInfo];
        }
    }

    public function sendContactMail(array $form)
    {

        $data['email'] = $this->contactEmail;
        $data['name'] = $this->contactName;
        $data['subject'] = "Contact Us: " . $form['subject'];
        $data['message'] =
            "<strong>Feedback Form:<br/>" .
            "<p><strong>Name:</strong> " . $form['name'] . "</p>" .
            "<p><strong>Email:</strong> " . $form['email'] . "</p>" .
            "<p><strong>Suject:</strong> " . $form['subject'] . "</p>" .
            "<p><strong>Message:</strong> " . $form['message'] . "</p>";

        return $this->send($data);
    }

    public function sendNewsletter($user, $params = array()): array
    {
        $data['email'] = $user->email;
        $data['name'] = $user->fullName;

        $message = "";

        if ($params['useGeneralHeader']) $message .= $this->siteSettings->generalEmailHeader;
        $message .= $params['message'];
        if ($params['useGeneralFooter']) $message .= $this->siteSettings->generalEmailFooter;

        $search = ['#name#', '#username#', '#email#', '#date_register#', '#site_url#', '#site_name#', '#site_email#', '#this_year#'];
        $replace = [$user->fullName, $user->userName, $user->email, $user->createdAt, $this->siteUrl, $this->siteName, $this->contactEmail, date('Y')];

        $message = str_replace($search, $replace, $message);

        $data['subject'] = $params['subject'] . " - " . $this->siteName;
        $data['message'] = $message;

        return $this->send($data);
    }

    private function sendTemplatedMail($details = array(), $replacements = array())
    {
        [
            'template' => $template,
            'email' => $email,
            'name' => $name,
            'subject' => $subject
        ] = $details;

        try {

            $message = $this->view->fetch("email-templates/$template.tpl");

            $data['email'] = $email;
            $data['name'] = $name;

            $search = ['#site_url#', '#site_name#', '#site_email#', '#this_year#'];
            $replace = [$this->siteUrl, $this->siteName, $this->contactEmail, date('Y')];

            foreach ($replacements as $key => $value) {
                $search[] = $key;
                $replace[] = $value;
            }

            $message = str_replace($search, $replace, $message);

            $data['subject'] = $subject . " - " . $this->siteName;
            $data['message'] = $message;

            return $this->send($data);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error sending mail.'];
        }
    }

    public function sendPasswordResetEmail($email, $name, $resetLink)
    {
        return $this->sendTemplatedMail(
            2,
            $email,
            $name,
            ['#name#' => $name, '#email#' => $email, '#reset_link#' => $resetLink]
        );
    }

    public function sendRegistrationEmail($email, $name, $phone, $password)
    {
        return $this->sendTemplatedMail(
            [
                'template' => 'registration',
                'email' => $email,
                'name' => $name,
                'subject' => "Registration"
            ],
            ['#name#' => $name, '#email#' => $email, '#phone#' => $phone, '#password#' => $password]
        );
    }

    public function sendResultCardPurchase($cardDetails, $examination, $email)
    {
        $replacements = ['#email#' => $email, "examination" => $examination];

        foreach ($cardDetails as $key => $value) {
            $replacements["#" . $key . "#"] = $value;
        }

        return $this->sendTemplatedMail(
            [
                'template' => 'result-card-purchase',
                'email' => $email,
                'subject' => "Result Card Purchase - {$examination}"
            ],
            ['#email#' => $email]
        );
    }

    public function sendTransactionLog($details, $email)
    {
        $replacements = ['#email#' => $email];

        foreach ($details as $key => $value) {
            $replacements["#" . $key . "#"] = $value;
        }

        return $this->sendTemplatedMail(
            [
                'template' => 'transaction-log',
                'email' => $email,
                'subject' => "Transaction Log - {$this->siteName}"
            ],
            ['#email#' => $email]
        );
    }
}
