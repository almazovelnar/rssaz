<?php

namespace core\forms\cabinet\Website;

use Yii;
use core\forms\CompositeForm;
use core\entities\Customer\Website\{Rss, Website};

/**
 * Class UpdateForm
 * @package core\forms\cabinet\Website
 *
 * @property RssForm[] $rss
 */
class UpdateForm extends CompositeForm
{
    public string $name;
    public int $trafficLimit;
    public int $updateFrequency;
    public ?string $defaultLanguage = null;
    /**
     * @var array|string
     */
    public $blockedDomains;
    /**
     * @var array|string
     */
    public $whiteListedDomains;
    public array $blockedDomainsList = [];
    public array $whiteListedDomainsList = [];

    private Website $entity;

    public function __construct(Website $website, array $config = [])
    {
        parent::__construct($config);

        $this->name = $website->name;
        $this->trafficLimit = $website->traffic_limit;
        $this->updateFrequency = $website->update_frequency;
        $this->defaultLanguage = $website->default_lang;

        $this->entity = $website;

        foreach ($website->blockedDomains as $blockedDomain) {
            $this->blockedDomains[] = $blockedDomain->blocked_id;
            $this->blockedDomainsList[$blockedDomain->blocked_id] = Website::find()
                ->where(['id' => $blockedDomain->blocked_id])
                ->firstOrFail()
                ->getName();
        }

        foreach ($website->whiteListedDomains as $whiteListedDomain) {
            $this->whiteListedDomains[] = $whiteListedDomain->whitelisted_id;
            $this->whiteListedDomainsList[$whiteListedDomain->whitelisted_id] = Website::find()
                ->where(['id' => $whiteListedDomain->whitelisted_id])
                ->firstOrFail()
                ->getName();
        }

        $rss = [];
        foreach (Yii::$app->params['languages'] as $code => $label)
            $rss[] = new RssForm($code, Rss::find()->where(['website_id' => $website->id, 'lang' => $code])->first());

        $this->rss = $rss;
    }

    protected function internalForms(): array
    {
        return ['rss'];
    }

    public function rules(): array
    {
        return [
            [['name', 'trafficLimit', 'updateFrequency', 'defaultLanguage'], 'required'],
            ['name', 'match', 'pattern' => '/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/u'],
            // ['name', 'unique', 'targetClass' => Website::class, 'filter' => ['<>', 'id', $this->entity->id]],
            ['trafficLimit', 'number', 'min' => 1000, 'max' => 500000],
            ['updateFrequency', 'integer'],
            [['blockedDomains', 'whiteListedDomains'], 'each', 'rule' => ['integer']],
            ['defaultLanguage', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Saytın adı',
            'defaultLanguage' => 'Saytın əsas dili',
            'trafficLimit' => 'Günlük təqribi unikal giriş sayı (hosts)',
            'updateFrequency' => 'RSS lentin yenilənmə intensivliyi (dəqiqə)',
            'blockedDomains' => 'Bloklanmış domenlər',
            'whiteListedDomains' => 'İcazəli domenlər',
        ];
    }

    public function getBlockedDomains(): array
    {
        return array_filter(array_wrap($this->blockedDomains));
    }

    public function getWhiteListedDomains(): array
    {
        return array_filter(array_wrap($this->whiteListedDomains));
    }

    public function afterValidate()
    {
        foreach ($this->rss as $rssForm) {
            if (!$rssForm->isEmpty()) {
                parent::afterValidate();
                return;
            }
        }

        $this->addError('requiredRssAddress', 'Ən azı bir rss RSS ünvanı qeyd olunmalıdır');
    }
}