<?php

namespace core\forms\cabinet\Website;

use Yii;
use yii\base\Model;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class CreateForm
 * @package core\forms\cabinet\Website
 */
class CreateForm extends Model
{
    public ?string $name = null;
    public ?string $address = null;
    public int $trafficLimit = 1000;
    public ?string $language = null;
    public ?string $rssAddress = null;

    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(WebsiteRepositoryInterface $websiteRepository)
    {
        parent::__construct([]);
        $this->websiteRepository = $websiteRepository;
    }

    public function rules(): array
    {
        return [
            [['name', 'address', 'trafficLimit', 'language', 'rssAddress'], 'required'],
            ['name', 'match', 'pattern' => '/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/u'],
            [['name', 'address'], 'checkForUniqueness'],
            [['address', 'rssAddress'], 'url'],
            ['trafficLimit', 'number', 'min' => 1000, 'max' => 500000],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function checkForUniqueness(string $attribute, $params): bool
    {
        $value = htmlspecialchars($this->attributes[$attribute]);
        if ($this->websiteRepository->query()->where(['w.' . $attribute => $value])->first()) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' mövcuddur.');
            return false;
        }
        return true;
    }

    public function attributeLabels(): array
    {
        return [
            'language' => 'Saytın əsas dili',
            'name' => 'Saytın adı',
            'address' => 'Saytın ünvanı',
            'trafficLimit' => 'Günlük təqribi unikal giriş sayı (hosts)',
            'rssAddress' => 'Saytın RSS linki',
        ];
    }
}