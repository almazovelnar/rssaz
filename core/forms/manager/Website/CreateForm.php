<?php

namespace core\forms\manager\Website;

use Yii;
use core\forms\CompositeForm;
use core\helpers\WebsiteHelper;
use core\services\api\PostReserver;
use core\entities\Customer\Website\Website;
use core\algorithms\manager\AlgorithmManager;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class CreateForm
 * @package core\forms\manager\Website
 *
 * @property RssForm[] $rss
 */
class CreateForm extends CompositeForm
{
    public ?string $customerId = null;
    public ?string $name = null;
    public ?string $address = null;
    public int $trafficLimit = 1000;
    public ?int $updateFrequency = 10;
    public ?float $rateMin = 1;
    /**
     * @var string|array
     */
    public $blockedDomains;
    /**
     * @var string|array
     */
    public $whiteListedDomains;
    public ?string $status = Website::STATUS_ACTIVE;
    public ?string $defaultLanguage = null;
    public array $algorithms = [];

    private WebsiteRepositoryInterface $websiteRepository;
    private PostReserver $postReserver;

    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        PostReserver $postReserver,
        array $config = [])
    {
        parent::__construct($config);

        $rss = [];
        foreach (Yii::$app->params['languages'] as $code => $label) {
            $rss[] = new RssForm($code);
        }
        $this->rss = $rss;

        $this->postReserver = $postReserver;
        $this->websiteRepository = $websiteRepository;
    }

    protected function internalForms()
    {
        return ['rss'];
    }

    public function rules()
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
            [['name', 'address'], 'checkForUniqueness'],
            ['defaultLanguage', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'customerId' => 'Customer',
            'rateMin' => 'Default rate',
        ];
    }

    public function checkForUniqueness(string $attribute, $params): bool
    {
        $value = htmlspecialchars($this->attributes[$attribute]);
        if ($this->websiteRepository->query()->where(['w.' . $attribute => $value])->first()) {
            $this->addError($attribute, $this->attributes[$attribute] . ' mövcuddur.');
            return false;
        }
        return true;
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