<?php

namespace core\services;

use Yii;
use stdClass;
use Elasticsearch\Client;
use core\forms\SearchForm;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use core\entities\Customer\Website\Post;

/**
 * Class SearchService
 * @package core\services
 */
class SearchService
{
    private string $language;
    private Client $client;

    /**
     * SearchService constructor.
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function __construct()
    {
        $this->language = Yii::$app->language;
        $this->client = Yii::$container->get(Client::class);
    }

    /**
     * @param SearchForm $form
     * @return array
     */
    public function search(SearchForm $form): array
    {
        $must = [];
        $must[] = [
            'multi_match' => [
                'query' => $form->q,
                'type' => 'phrase_prefix',
                'fields' => [
                    'title',
                    'content',
                ],
                'tie_breaker' => 0.3,
            ],
        ];

        $response = $this->client->search([
            'index' => 'rss',
            'type' => 'news',
            'body' => [
                'from' => $form->getRange(),
                'size' => $form->getLimit(),
                'sort' => ['created_at' => ['order' => 'desc']],
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'filter' => [
                            'term' => ['lang' => $this->language],
                        ],
                        'should' => [
                            'term' => ['status' => Post::STATUS_ACTIVE],
                        ],
                        "minimum_should_match" => 1,
                    ],
                ],
                'highlight' => [
                    'fields' => [
                        'title' => new stdClass(),
                        'content' => new stdClass(),
                    ],
                    'fragment_size' => 200,
                    'number_of_fragments' => 4,
                    'post_tags' => ['</span>'],
                    'pre_tags' => ['<span style="background:yellow">'],
                    'require_field_match' => false,
                ],
            ],
        ]);

        return array_map(fn (array $hit) => $hit['_source'], $response['hits']['hits']);
    }
}
