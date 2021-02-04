<?php

namespace core\services\diagnostics;

use LibXMLError;
use yii\web\View;
use RuntimeException;
use core\exceptions\RssParseItemException;
use core\entities\{Customer\Website\Rss, Parse\Parse};

/**
 * Class DiagnosticService
 * @package core\services\diagnostics
 */
class DiagnosticService
{
    private array $lines = [];

    public function addHistory(
        Rss $rss,
        float $elapsedTime,
        int $successfullyAdded,
        array $errs,
        ?string $rssFilename = null
    )
    {
        $parse = Parse::create($rss->id, $rss->website_id, $successfullyAdded, $rssFilename, $elapsedTime);

        if (empty($errs)) {
            $parse->setStatus(LIBXML_ERR_NONE);
        } else {
            foreach ($errs as $err) {
                if ($err instanceof RssParseItemException) {
                    $parse->addItemError($err->getMessage(), $this->renderItem($err));
                } elseif ($err instanceof LibXMLError) {
                    $fragment = null;
                    if (isset($err->line) && $err->line) {
                        $this->getLines($rss->rss_address);
                        if (isset($this->lines[$err->line - 1])) {
                            $fragment = trim($this->lines[$err->line - 1]);
                        }
                    } else {
                        $err->line = null;
                    }
                    $column = $err->column ?? null;
                    $parse->addValidationError($err->message, $err->level, $err->line, $column, $fragment);
                }
            }
        }

        if (!$parse->save()) {
            throw new RuntimeException('Parse saving error');
        }
    }

    private function getLines($source)
    {
        if (empty($this->lines)) {
            $this->lines = file($source);
        }
    }

    private function renderItem(RssParseItemException $e)
    {
        return (new View())->render('@common/diagnostics/item', ['error' => $e]);
    }
}