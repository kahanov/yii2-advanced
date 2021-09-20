<?php

namespace common\services;

use yii;
use frontend\forms\ContactForm;
use yii\mail\MailerInterface;

/**
 * Contact service
 */
class ContactService
{
    private $adminEmail;
    private $mailer;

    /**
     * ContactService constructor.
     * @param $adminEmail
     * @param MailerInterface $mailer
     */
    public function __construct($adminEmail, MailerInterface $mailer)
    {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    /**
     * @param ContactForm $form
     */
    public function send(ContactForm $form): void
    {
        $sent = $this->mailer
            ->compose(
                ['html' => 'contact/html', 'text' => 'contact/text'],
                ['user' => [
                    'name' => $form->name,
                    'email' => $form->email,
                ],
                    'body' => $form->body
                ]
            )
            ->setTo($this->adminEmail)
            ->setSubject($form->subject)
            ->send();

        if (!$sent) {
            throw new \RuntimeException(Yii::t('common', 'Ошибка отправки сообщения'));
        }
    }
}
