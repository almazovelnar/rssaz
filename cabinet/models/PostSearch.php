<?php

namespace cabinet\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use cabinet\data\NewsDataProvider;
use core\entities\Customer\Customer;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Post;
use core\repositories\interfaces\WebsiteRepositoryInterface;

/**
 * Class PostSearch
 * @package cabinet\models
 */
class PostSearch extends Model
{
    const RANGES = [0, 1, 7, 14, 30];
    const LIMITS = [10, 30, 50];

    public int $range = 7;
    public ?string $title = null;
    public ?string $priority = null;
    public ?int $website_id;
    public int $limit = 10;
    public ?string $language = null;

    private Customer $customer;
    private WebsiteRepositoryInterface $websiteRepository;

    public function formName(): string
    {
        return '';
    }

    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        ?int $websiteId,
        array $config = []
    )
    {
        parent::__construct($config);

        $this->customer = Yii::$app->user->identity;
        $this->websiteRepository = $websiteRepository;
        $this->website_id = $websiteId;
    }

    public function rules()
    {
        return [
            ['range', 'required'],
            ['range', 'in', 'range' => self::RANGES],
            ['title', 'string', 'max' => 255],
            ['website_id', 'websiteExistsRule', 'params' => ['customer_id' => $this->customer->id]],
            ['limit', 'in', 'range' => self::LIMITS],
            ['priority', 'integer'],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function websiteExistsRule($attribute, $params): bool
    {
        try {
            $this->websiteRepository->getOneByCustomer($this->website_id, $params['customer_id']);
            return true;
        } catch (NotFoundException $e) {
            $this->addError($attribute, 'Vebsayt tapılmadı');
            return false;
        }
    }

    /**
     * @param array $params
     * @return NewsDataProvider
     * @throws NotFoundException
     */
    public function search(array $params)
    {
        $query = Post::find();

        $dataProvider = new NewsDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => ['created_at', 'views', 'clicks', 'ctr'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) return $dataProvider;

        $dataProvider->pagination->pageSize = $this->limit;

        if ($this->website_id) {
            $dataProvider->website = $this->websiteRepository->get($this->website_id);
        }

        if (!$this->language) {
            $this->language = $dataProvider->website->default_lang ?? Yii::$app->params['defaultLanguage'];
        }

        if ($this->range == 0) {
            $query->andWhere(new Expression('toDate(created_at) = today()'));
        } else {
            $query->andWhere(new Expression('toDate(created_at) >= (today() - INTERVAL '. (int) $this->range . ' DAY) AND toDate(created_at) < today()'));
        }

        $this->priority = (int) $this->priority;
        if (isset($this->priority)) {
            if ($this->priority === 0) {
                $query->andFilterWhere(['priority' => 0]);
            } else if ($this->priority > 0) {
                $query->andFilterWhere(['>', 'priority', 0]);
            }
        }

        $query->andFilterWhere([
            'website_id' => (int) $this->website_id,
            'lang' => $this->language,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
