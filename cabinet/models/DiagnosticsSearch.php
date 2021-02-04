<?php

namespace cabinet\models;

use core\entities\Customer\Website\Rss;
use core\entities\Customer\Website\Website;
use core\entities\Parse\Parse;
use core\helpers\DiagnosticsHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\repositories\interfaces\RssRepositoryInterface;
use yii\db\Expression;

/**
 * Class DiagnosticsSearch
 * @package cabinet\models
 */
class DiagnosticsSearch extends Model
{
    public $rss_id;
    public $date;
    public $status = null;
    private $rss;


    private RssRepositoryInterface $rssRepository;

    public function __construct(
        RssRepositoryInterface $rssRepository,
        $config = []
    )
    {
        parent::__construct($config);

        $this->rssRepository = $rssRepository;
    }

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            ['rss_id', 'required'],
            ['rss_id', 'integer'],
            ['status', 'in', 'range' => array_keys(DiagnosticsHelper::statusesList())],
            ['date', 'date', 'format' => 'php: Y-m-d'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rss_id' => 'RSS',
            'range' => 'Tarix',
        ];
    }

    public function search(Website $website, $params)
    {
        $query = Parse::find()->orderBy('created_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && $this->validate()) {
            $this->rss = $this->rssRepository->get($this->rss_id);
        } else {
            $this->rss = $this->rssRepository->getByWebsite($website->id, $website->default_lang);
        }

        if (!$this->date) {
            $this->date = date('Y-m-d');
        }

        $query->andWhere(new Expression('DATE(created_at) = :date', [':date' => $this->date]))
            ->andWhere(['rss_id' => $this->rss->id]);

        if ($this->status != null) {
            $query->andWhere(['status' => $this->status]);
        }

        return $dataProvider;
    }

    public function getRss()
    {
        return $this->rss;
    }

    public function getPostsCount()
    {
        return $this->rssRepository->getPostsCount($this->rss->id);
    }
}