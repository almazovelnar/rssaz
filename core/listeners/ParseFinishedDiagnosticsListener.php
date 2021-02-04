<?php

namespace core\listeners;


use Yii;
use Exception;
use yii\mail\MailerInterface;
use core\helpers\ParseHelper;
use core\events\RssParseFinished;
use core\repositories\CustomerRepository;
use core\services\diagnostics\DiagnosticService;

/**
 * Class ParseFinishedDiagnosticsListener
 * @package core\listeners
 */
class ParseFinishedDiagnosticsListener
{
    private MailerInterface $mailer;
    private DiagnosticService $diagnosticService;
    private CustomerRepository $customerRepository;

    public function __construct(
        MailerInterface $mailer,
        DiagnosticService $diagnosticService,
        CustomerRepository $customerRepository
    )
    {
        $this->mailer = $mailer;
        $this->diagnosticService = $diagnosticService;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param RssParseFinished $event
     */
    public function handle(RssParseFinished $event): void
    {
        $parserDto = $event->getParserDto();
        $rss = $parserDto->getParsedRssEntity();
        $xmlContent = $parserDto->getParsedXmlContent();
        $rssFilename = null;

        try {
            if ($xmlContent !== null) {
                $rssFilename = ParseHelper::generateXmlFileName();
                file_put_contents(Yii::getAlias('@cabinet/web/brokenRss/' . $rssFilename), $xmlContent);
            }

            $this->diagnosticService->addHistory(
                $rss,
                $parserDto->getElapsedTime(),
                $parserDto->getRssParsedPosts()->count(),
                $parserDto->getParseItemErrors(),
                $rssFilename
            );

            if (!Yii::$app->config->get('sending_mail') || !$parserDto->hasParseItemErrors()) return;

            $customer = $this->customerRepository->get($rss->getCustomer());
            $this->mailer
                ->compose(['html' => 'rss/error/item-html', 'text' => 'rss/error/item-text'], ['rss' => $rss])
                ->setTo($customer->getEmail())
                ->setSubject('Rss lentində səhvlər aşkarlandı | ' . Yii::$app->name)
                ->send();

        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}
