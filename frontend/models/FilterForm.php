<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\{Request, Response, Cookie};
use core\entities\Customer\Website\Website;

/**
 * Class FilterForm
 * @package frontend\models
 */
class FilterForm extends Model
{
    public const PERIOD_HOUR = 'hour';
    public const PERIOD_DAY = 'day';
    public const PERIOD_WEEK = 'week';

    public ?string $period = null;
    private array $filters = [];

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->filters['period'] = self::PERIOD_DAY;
    }

    public static function getPeriods(): array
    {
        return [
            self::PERIOD_HOUR  => Yii::t('filters', 'hour'),
            self::PERIOD_DAY   => Yii::t('filters', 'day'),
            self::PERIOD_WEEK  => Yii::t('filters', 'week'),
        ];
    }

    public function getDefaultPeriod(): ?string
    {
        return $this->filters['period'];
    }

    public function getDefaultPeriodParams(): ?array
    {
        return ['period' => self::PERIOD_DAY];
    }

    public function formName(): string
    {
        return '';
    }

    public static function getSources(): array
    {
        return ArrayHelper::merge(
            [0 => Yii::t('filters', 'sources')],
            ArrayHelper::map(Website::find()->where(['!=', 'name', 'rss.az'])->asArray()->all(), 'id', 'name')
        );
    }

    public function rules(): array
    {
        return [
            [['period'], 'required'],
            ['period', 'string'],
            ['period', 'in', 'range' => array_keys(self::getPeriods())],
        ];
    }

    public function getFilters(Request $request): array
    {
        $collection = $request->cookies;

        if ($collection->has('filter_period')) {
            $this->filters['period'] = $collection->getValue('filter_period');
        }

        return $this->filters;
    }

    public function rememberFilters(Response $response): bool
    {
        foreach ($this as $filter => $value) {
            $response->cookies->add(new Cookie([
                'name' => 'filter_' . $filter,
                'value' => $value,
                'expire' => time() + (86400 * 365),
            ]));
        }
        return true;
    }
}