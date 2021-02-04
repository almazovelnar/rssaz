<?php

namespace core\forms\manager\Website;

use yii\base\Model;
use core\entities\Customer\Website\Rss;

/**
 * Class RssForm
 * @package core\forms\manager\Website
 */
class RssForm extends Model
{
    public ?string $language = null;
    public ?string $rssAddress = null;

    private ?Rss $rss = null;

    public function __construct(
        string $language,
        Rss $rss = null,
        array $config = [])
    {
        $this->language = $language;
        if ($rss) {
            $this->rssAddress = $rss->rss_address;
            $this->rss = $rss;
        }
        parent::__construct($config);
    }

    public function formName(): string
    {
        return parent::formName() . '_' . $this->language;
    }

    public function rules(): array
    {
        return [
            ['rssAddress', 'url']
        ];
    }

    public function isEmpty(): bool
    {
        return empty($this->rssAddress);
    }
}