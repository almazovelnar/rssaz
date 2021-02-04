<?php

namespace core\listeners;

use Yii;
use RuntimeException;
use yii\mail\MailerInterface;
use core\useCases\events\PasswordResetRequested;

/**
 * Class PasswordResetRequestListener
 * @package core\listeners
 */
class PasswordResetRequestListener
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(PasswordResetRequested $event)
    {
        if (Yii::$app->config->get('sending_mail')) {
            $sent = $this
                ->mailer
                ->compose(
                    ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                    ['user' => $event->user]
                )
                ->setTo($event->user->email)
                ->setSubject('Şifrəmi unutdum | ' . Yii::$app->name)
                ->send();

            if (!$sent) throw new RuntimeException('Request mail sending error.');
        }
    }
}