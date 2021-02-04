<?php

namespace core\listeners;

use Yii;
use RuntimeException;
use core\helpers\ParseHelper;
use yii\mail\MailerInterface;
use core\entities\Customer\Customer;
use core\events\RssParseErrorDetected;
use core\repositories\CustomerRepository;
use core\services\diagnostics\DiagnosticService;

/**
 * Class RssParseErrorDetectedListener
 * @package core\listeners
 */
class RssParseErrorDetectedListener
{
    private MailerInterface $mailer;
    private DiagnosticService $diagnosticService;
    private CustomerRepository $customerRepository;

    public function __construct(
        MailerInterface $mailer,
        DiagnosticService $diagnosticService,
        CustomerRepository $customerRepository // code to interface later.
    )
    {
        $this->mailer = $mailer;
        $this->diagnosticService = $diagnosticService;
        $this->customerRepository = $customerRepository;
    }

    public function handle(RssParseErrorDetected $event)
    {
        $rss = $event->getRss();
        $exception = $event->getException();
        $rssFilename = null;

        if ($exception->getXmlContent() !== null) {
            $rssFilename = ParseHelper::generateXmlFileName();
            file_put_contents(Yii::getAlias('@cabinet/web/brokenRss/' . $rssFilename), $exception->getXmlContent());
        }

        $this->diagnosticService->addHistory($rss, 0, 0, $exception->getErrors(), $rssFilename);

        if (Yii::$app->config->get('sending_mail')) {
            /** @var Customer $customer */
            $customer = $this->customerRepository->get($rss->getCustomer());
            if (Yii::$app->config->get('sending_mail') === strtolower('on')) {
                $sent = $this->mailer
                    ->compose(['html' => 'rss/error/main-html', 'text' => 'rss/error/main-text'], ['rss' => $rss])
                    ->setTo($customer->getEmail())
                    ->setSubject('Rss lentində səhvlər aşkarlandı | ' . Yii::$app->name)
                    ->send();

                if (!$sent) throw new RuntimeException('Request mail sending error.');
            }
        }
    }
}
