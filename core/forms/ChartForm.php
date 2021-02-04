<?php

namespace core\forms;

use yii\base\Model;
use yii\web\Cookie;
use yii\web\Request;
use yii\web\Response;

/**
 * Class ChartForm
 * @package core\forms
 */
class ChartForm extends Model
{
    public ?array $legends = [];
    private ?array $chartLegends = [];

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            ['legends', 'each', 'rule' => ['string']],
        ];
    }

    public function validateLegends($attribute, $params)
    {
        if (!in_array($this->$attribute, ['true', 'false', null])) {
            $this->addError($attribute, 'Not Found!');
        }
    }

    public function getLegends(Request $request): array
    {
        $collection = $request->cookies;

        if ($collection->has('chart_legends')) {
            $this->chartLegends = $collection->getValue('chart_legends');
        }

        return $this->chartLegends;
    }

    public function rememberLegends(Response $response, Request $request)
    {
        $legends = $request->cookies->getValue('chart_legends');
        $newLegends = [];

        if (!empty($legends)) {
            if (count($legends) != count($this['legends'])) {
                $legends = [];
            }
        }

        foreach ($this['legends'] as $key => $legend){
            if (!$legend) {
                if ($legends)
                    $newLegends[] = $legends[$key];
                else
                    $newLegends[] = 'not';
            } else {
                $newLegends[] = $legend;
            }
        }

        $response->cookies->add(new Cookie([
            'name' => 'chart_legends',
            'value' => $newLegends,
            'expire' => time() + 86400,
        ]));

        return true;
    }
}