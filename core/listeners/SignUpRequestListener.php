<?php

namespace core\listeners;

use Yii;
use RuntimeException;
use yii\mail\MailerInterface;
use core\useCases\events\SignUpRequested;

/**
 * Class SignUpRequestListener
 * @package core\listeners
 */
class SignUpRequestListener
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(SignUpRequested $event)
    {
        if (Yii::$app->config->get('sending_mail')) {
            $sent = $this->mailer
                ->compose(
                    ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                    ['user' => $event->user]
                )
                ->setTo($event->user->email)
                ->setSubject('Qeydiyyatınızı təsdiq edin | ' . Yii::$app->name)
                ->send();

            if (!$sent) throw new RuntimeException('Request mail sending error.');
        }
    }
}