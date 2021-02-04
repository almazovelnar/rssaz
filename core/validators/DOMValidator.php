<?php

namespace core\validators;

use Yii;
use LibXMLError;
use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use core\exceptions\DOMValidatorException;

/**
 * Class DOMValidator
 * @package core\validators
 */
class DOMValidator
{
    private DOMDocument $handler;
    private Client $client;
    private array $errors = [];
    private ?string $xmlContent = null;

    public function __construct(DOMDocument $handler, Client $client)
    {
        $this->handler = $handler;
        $this->client = $client;

        libxml_use_internal_errors(true);
    }

    /**
     * Validate Incoming Feeds against Listing Schema
     *
     * @param string $feedAddress
     * @param string|null $language
     * @return bool
     */
    public function validateFeeds(string $feedAddress, ?string $language = null): bool
    {
        $this->errors = [];
        libxml_clear_errors();

        try {
            $request = $this->client->request('GET', $feedAddress, [
                'stream' => true,
                'stream_context' => ['ssl' => ['allow_self_signed' => true]],
            ]);

            if (empty(($content = $request->getBody()->getContents())))
                throw new DOMValidatorException("Empty rss feed detected. Feed url: {$feedAddress}", LIBXML_ERR_FATAL);

            $this->xmlContent = html_entity_decode($content);
            unset($content);

            if ($this->handler->loadXML($this->xmlContent, LIBXML_NOBLANKS) === false)
                throw new DOMValidatorException("Can't load the xml feed. Feed url: {$feedAddress}", LIBXML_ERR_WARNING);

            if (!isset($language)) {
                if (($domNode = $this->handler->getElementsByTagName('language')->item(0)) === null)
                    throw new DOMValidatorException("Channel language is undefined.", LIBXML_ERR_FATAL);
                $language = $domNode->nodeValue;
            }

            if (!file_exists(($schema = Yii::getAlias('@cabinet/web/xsd/' . $language . '.xsd'))))
                throw new DOMValidatorException("Schema language for current rss channel does not exists.", LIBXML_ERR_FATAL);

            return $this->handler->schemaValidate($schema);
        } catch (DOMValidatorException $e) {
            $this->addError($e->getMessage(), $e->getLibXmlErrorCode());
            return false;
        } catch (GuzzleException $e) {
            $this->addError("Internal network error occurred: {$e->getMessage()}. Feed url: {$feedAddress}", LIBXML_ERR_FATAL);
            return false;
        }
    }

    public function getXmlContent(): ?string
    {
        return $this->xmlContent;
    }

    public function getErrors(): array
    {
        return array_merge($this->errors, array_filter(libxml_get_errors(), fn (LibXMLError $e) => $e->code !== 1824));
    }

    private function addError(string $message, int $level): void
    {
        $err = new LibXMLError();
        $err->message = $message;
        $err->level = $level;
        $this->errors[] = $err;
    }
}
