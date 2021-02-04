<?php

namespace core\forms\cabinet\Website;

use yii\base\Model;
use core\entities\Customer\Website\Rss;

/**
 * Class RssForm
 * @package core\forms\cabinet\Website
 */
class RssForm extends Model
{
    public string $language;
    public ?string $rssAddress = null;
    private ?Rss $rss = null;

    public function __construct(
        string $language,
        Rss $rss = null,
        array $config = []
    )
    {
        $this->language = $language;
        if ($rss) {
            $address = parse_url($rss->rss_address);
            $this->rssAddress = $address['path'] ?? null;
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
            //['rssAddress', 'url'],
            ['rssAddress', 'match', 'pattern' => '/^\/[\/.a-zA-Z0-9-]+$/']
        ];
    }

    public function isEmpty(): bool
    {
        return empty($this->rssAddress);
    }
}