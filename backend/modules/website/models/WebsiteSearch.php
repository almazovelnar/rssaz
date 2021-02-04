<?php

namespace backend\modules\website\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\repositories\interfaces\WebsiteRepositoryInterface;
use core\repositories\CustomerRepository;

/**
 * Class WebsiteSearch
 * @package backend\modules\website\models
 */
class WebsiteSearch extends Model
{
    public ?string $customer = null;
    public ?string $name = null;
    public ?string $address = null;
    public ?string $date_from = null;
    public ?string $date_to = null;
    public ?string $status = null;

    private array $customers = [];

    private WebsiteRepositoryInterface $websiteRepository;
    private CustomerRepository $customerRepository;

    public function __construct(WebsiteRepositoryInterface $websiteRepository, CustomerRepository $customerRepository, $config = [])
    {
        parent::__construct($config);

        $this->websiteRepository = $websiteRepository;
        $this->customerRepository = $customerRepository;
    }

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [['customer', 'name', 'address', 'status'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = $this->websiteRepository->query()->with(['customer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) return $dataProvider;

        if ($this->customer) {
            $this->customers = array_map(fn($customer) => $customer['id'], $this->customerRepository->all($this->customer));
        }

        $query->andFilterWhere([
            'w.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'w.name', $this->name])
            ->andFilterWhere(['like', 'w.address', $this->address])
            ->andFilterWhere(['in', 'w.customer_id', $this->customers])
            ->andFilterWhere(['>=', 'toDateTime(w.created_at)', $this->date_from ? ($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'toDateTime(w.created_at)', $this->date_to ? ($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
