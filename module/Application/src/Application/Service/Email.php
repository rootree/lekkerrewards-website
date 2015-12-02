<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\Mail;
use Application\Model\Entity\Customer as CustomerEntity;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

class Email
{
    /**
     * @var \Zend\I18n\Translator\Translator
     */
    private $translator;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $viewRenderer;

    public function __construct($translator, $config, $viewRenderer)
    {
        $this->translator = $translator;
        $this->config = $config;
        $this->viewRenderer = $viewRenderer;
    }


    /**
     * @param CustomerEntity $customerEntity
     * @param \Application\Model\Entity\MerchantBranch $merchantBranchEntity
     * @throws \Exception
     * @throws \phpmailerException
     */
    public function sendGreetingMessage($customerEntity, $merchantBranchEntity, $openPassword)
    {


        $checkInContent = $this->viewRenderer->render(
            'email/greeting',
            [
                'company' => $this->config['company'],
                'customer' => $customerEntity,
                'merchantBranch' => $merchantBranchEntity,
                'openPassword' => $openPassword,
            ]
        );

        $content = $this->viewRenderer->render(
            'layout/email',
            [
                'content' => $checkInContent
            ]
        );


        $mail = new \PHPMailer;

        // $mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.yandex.ru';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'support@lekkerrewards.nl';                 // SMTP username
        $mail->Password = 'adrenalinL1';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom($this->config['company']['email'], $this->config['company']['emailRobot']);
        $mail->addAddress(
            $this->getEmail($customerEntity->getEMail()),
            $customerEntity->getName()
        );     // Add a recipient
        $mail->addReplyTo($this->config['company']['email'], $this->config['company']['nameForFeedback']);
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = sprintf(
            $this->translator->translate('Добро пожаловать в %s'),
            $merchantBranchEntity->getFkMerchant()->getName()
        );
        $mail->Body    = $content;
        $mail->AltBody = $content;
        $mail->CharSet = 'utf-8';

        $mail->send();
    }

    /**
     * @param \Zend\Stdlib\Parameters $feedbackForm
     */
    public function sendFeedbackMessage($feedbackForm)
    {


/*        $mail = new Mail\Message();
        $mail->setFrom('no-replay', 'Robot');*/

        $content = $this->viewRenderer->render(
            'layout/email',
            [
                'content' => print_r($feedbackForm->toArray(), 1)
            ]
        );
        // make a header as html
       /* $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html,));

        // instance mail
        $mail->setBody($body);
        $mail->addTo(
            $this->config['company']['emailForFeedback'],
            $this->config['company']['nameForFeedback']
        );
        $mail->setSubject($this->translator->translate('Сообщение от пользователей'));
        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);*/


        $mail = new \PHPMailer;

        // $mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        //$mail->Host = 'mail.lekkerrewards.nl';  // Specify main and backup SMTP servers
        $mail->Host = 'smtp.yandex.ru';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'support@lekkerrewards.nl';                 // SMTP username
        $mail->Password = 'adrenalinL1';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom($this->config['company']['email'], $this->config['company']['emailRobot']);
        $mail->addAddress($this->config['company']['emailForFeedback'], $this->config['company']['nameForFeedback']);     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo($this->config['company']['email'], $this->config['company']['nameForFeedback']);
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $this->translator->translate('Сообщение от пользователей');
        $mail->Body    = $content;
        $mail->AltBody = $content;
        $mail->CharSet = 'utf-8';

        $mail->send();
    }

    /**
     * @param $isFirstTime
     * @param \Application\Model\Entity\MerchantBranch $merchantBranchEntity
     * @param $points
     * @param \Application\Model\Entity\Customer $customerEntity
     * @throws \Exception
     * @throws \phpmailerException
     */
    public function sendCheckInMessage($isFirstTime, $merchantBranchEntity, $points, $customerEntity)
    {

        $checkInContent = $this->viewRenderer->render(
            'email/check-in',
            [
                'company' => $this->config['company'],
                'merchantBranch' => $merchantBranchEntity,
                'isFirstTime' => $isFirstTime,
                'points' => $points
            ]
        );

        $content = $this->viewRenderer->render(
            'layout/email',
            [
                'content' => $checkInContent
            ]
        );

        $mail = new \PHPMailer;

        // $mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.yandex.ru';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'support@lekkerrewards.nl';                 // SMTP username
        $mail->Password = 'adrenalinL1';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom($this->config['company']['email'], $this->config['company']['emailRobot']);
        $mail->addAddress(
            $this->getEmail($customerEntity->getEMail()),
            $customerEntity->getName()
        );     // Add a recipient
        $mail->addReplyTo($this->config['company']['email'], $this->config['company']['nameForFeedback']);
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = sprintf(
            $this->translator->translate('Отметка в %s'),
            $merchantBranchEntity->getFkMerchant()->getName()
        );
        $mail->Body    = $content;
        $mail->AltBody = $content;
        $mail->CharSet = 'utf-8';

        $mail->send();
    }

    /**
     * @param CustomerEntity $customerEntity
     * @param \Application\Model\Entity\MerchantBranch $merchantBranchEntity
     * @throws \Exception
     * @throws \phpmailerException
     */
    public function sendRegistrationMessage($customerEntity)
    {

        $checkInContent = $this->viewRenderer->render(
            'email/registration',
            [
                'company' => $this->config['company'],
                'customer' => $customerEntity,
            ]
        );

        $content = $this->viewRenderer->render(
            'layout/email',
            [
                'content' => $checkInContent
            ]
        );


        $mail = new \PHPMailer;

        // $mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.yandex.ru';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'support@lekkerrewards.nl';                 // SMTP username
        $mail->Password = 'adrenalinL1';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom($this->config['company']['email'], $this->config['company']['emailRobot']);
        $mail->addAddress(
            $this->getEmail($customerEntity->getEMail()),
            $customerEntity->getName()
        );     // Add a recipient
        $mail->addReplyTo(
            $this->config['company']['email'],
            $this->config['company']['nameForFeedback']
        );
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = sprintf(
            $this->translator->translate('Регистрация в %s'),
            $this->config['company']['name']
        );
        $mail->Body    = $content;
        $mail->AltBody = $content;
        $mail->CharSet = 'utf-8';

        $mail->send();
    }

    private function getEmail($email)
    {
        if (!$this->config['isSendingEmailsIsEnabled']) {
            return 'ivan.chura@gmail.com';
        }
        return $email;
    }

    /**
     * @param CustomerEntity $customerEntity
     * @param \Application\Model\Entity\MerchantBranch $merchantBranchEntity
     * @param \Application\Model\Entity\RewardHistory $rewardHistory
     * @throws \Exception
     * @throws \phpmailerException
     */
    public function sendRedeemMessage($customerEntity, $merchantBranchEntity, $rewardHistory)
    {
        if (!$this->config['isSendingEmailsIsEnabled']) {
            return;
        }

        $checkInContent = $this->viewRenderer->render(
            'email/redeem',
            [
                'company' => $this->config['company'],
                'merchantBranch' => $merchantBranchEntity,
                'reward' => $rewardHistory->getName(),
                'visits' => $rewardHistory->getPoints(),
            ]
        );

        $content = $this->viewRenderer->render(
            'layout/email',
            [
                'content' => $checkInContent
            ]
        );


        $mail = new \PHPMailer;

        // $mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.yandex.ru';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'support@lekkerrewards.nl';                 // SMTP username
        $mail->Password = 'adrenalinL1';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom($this->config['company']['email'], $this->config['company']['emailRobot']);
        $mail->addAddress(
            $this->getEmail($customerEntity->getEMail()),
            $customerEntity->getName()
        );     // Add a recipient
        $mail->addReplyTo($this->config['company']['email'], $this->config['company']['nameForFeedback']);
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = sprintf(
            $this->translator->translate('Новая награда в %s'),
            $merchantBranchEntity->getFkMerchant()->getName()
        );
        $mail->Body    = $content;
        $mail->AltBody = $content;
        $mail->CharSet = 'utf-8';

        $mail->send();
    }

}