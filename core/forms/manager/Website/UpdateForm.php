<?php

namespace core\forms\manager\Website;

use Yii;
use core\forms\CompositeForm;
use core\helpers\WebsiteHelper;
use core\entities\Customer\Customer;
use core\services\api\PostReserver;
use core\algorithms\manager\AlgorithmManager;
use core\entities\Customer\Website\{Website, Rss};
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class UpdateForm
 * @package core\forms\manager\Website
 *
 * @property RssForm[] $rss
 */
class UpdateForm extends CompositeForm
{
    public string $name;
    public string $status;
    public string $address;
    public ?string $defaultLanguage = null;
    public ?float $rateMin;
    public int $customerId;
    public int $updateFrequency;
    public int $trafficLimit = 1000;
    public array $algorithms = [];
    /**
     * @var string|array
     */
    public $blockedDomains;
    /**
     * @var string|array
     */
    public $whiteListedDomains;
    public array $blockedDomainsList = [];
    public array $whiteListedDomainsList = [];
    public $customerName;

    private Website $_website;
    private PostReserver $postReserver;
    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(
        Website $website,
        WebsiteRepositoryInterface $websiteRepository,
        PostReserver $postReserver,
        array $config = []
    )
    {
        parent::__construct($config);

        $rss = [];
        $this->customerId = $website->customer_id;
        $customer = Customer::find()->andWhere(['id' => $website->customer_id])->one();
        $this->customerName = $customer ? $customer->getFullName() : null;
        $this->name = $website->name;
        $this->address = $website->address;
        $this->trafficLimit = $website->traffic_limit;
        $this->updateFrequency = $website->update_frequency;
        $this->defaultLanguage = $website->default_lang;
        $this->status = $website->status;
        $this->rateMin = $website->rate_min;

        $this->_website = $website;
        $this->postReserver = $postReserver;
        $this->websiteRepository = $websiteRepository;

        foreach ($website->blockedDomains as $blockedDomain) {
            $this->blockedDomains[] = $blockedDomain->blocked_id;
            $this->blockedDomainsList[$blockedDomain->blocked_id] = Website::find()
                ->where(['id' => $blockedDomain->blocked_id])
                ->firstOrFail()
                ->name;
        }

        foreach ($website->whiteListedDomains as $whiteListedDomain) {
            $this->whiteListedDomains[] = $whiteListedDomain->whitelisted_id;
            $this->whiteListedDomainsList[$whiteListedDomain->whitelisted_id] = Website::find()
                ->where(['id' => $whiteListedDomain->whitelisted_id])
                ->firstOrFail()
                ->name;
        }

        foreach ($website->algorithms as $algorithm) {
            $this->algorithms[$algorithm->algorithm] = $algorithm->algorithm;
        }

        if (empty($this->algorithms))
            $this->algorithms = ['default' => 'default'];

        foreach (Yii::$app->params['languages'] as $code => $label)
            $rss[] = new RssForm($code, Rss::find()->andWhere(['website_id' => $website->id, 'lang' => $code])->first());

        $this->rss = $rss;
    }

    protected function internalForms(): array
    {
        return ['rss'];
    }

    public function rules(): array
    {
        return [
            [['customerId', 'name', 'address', 'trafficLimit', 'updateFrequency', 'status', 'defaultLanguage', 'algorithms'], 'required'],
            ['name', 'match', 'pattern' => '/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/u'],
            ['trafficLimit', 'number', 'min' => 1000, 'max' => 500000],
            ['updateFrequency', 'integer'],
            ['status', 'in', 'range' => array_keys(WebsiteHelper::statusesList())],
            [['blockedDomains', 'whiteListedDomains'], 'each', 'rule' => ['integer']],
            ['address', 'url'],
            ['rateMin', 'double'],
            ['defaultLanguage', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function attributeLabels()
    {
        return [
            'customerId' => 'Customer',
            'rateMin' => 'Default rate',
        ];
    }

    public function getAvailableAlgorithms(): array
    {
        $list = (new AlgorithmManager($this->postReserver))->getAlgorithmList();
        $values = array_values($list);
        return array_combine($values, $values);
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