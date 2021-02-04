<?php

namespace core\algorithms\manager;

use Yii;
use yii\base\InvalidConfigException;
use Tightenco\Collect\Support\Collection;
use core\entities\Customer\Website\Website;
use yii\di\{Container, NotInstantiableException};
use core\services\api\{PostReserver, WebsiteDto};
use core\algorithms\{DefaultAlgorithm, NewDefaultAlgorithm, TopHundredPostsAlgorithm};
use core\exceptions\{AlgorithmDoesntExistException, CantInitializeAlgorithmException, CodeNotFoundException};

/**
 * Class AlgorithmManager
 * @package core\algorithms\manager
 */
class AlgorithmManager
{
    private Container $container;
    private PostReserver $postReserver;
    private array $algorithms = [
        'default' => DefaultAlgorithm::class,
        'top-100' => TopHundredPostsAlgorithm::class,
        'new-default' => NewDefaultAlgorithm::class,
    ];

    public function __construct(PostReserver $postReserver)
    {
        $this->postReserver = $postReserver;
        $this->container = Yii::$container;
    }

    /**
     * @param Website $website
     * @param WebsiteDto $websiteDto
     * @return Collection
     * @throws AlgorithmDoesntExistException
     * @throws CantInitializeAlgorithmException
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     * @throws CodeNotFoundException
     */
    public function getData(Website $website, WebsiteDto $websiteDto): Collection
    {
        if (($code = $websiteDto->getCode()) === null)
            throw new CodeNotFoundException($website->getName() . ' has not generated code.');

        $blockCount = $code->getBlockCount();
        $algorithms = $websiteDto->getAlgorithms();
        $algorithm = $this->algorithms['default'];
        if (!empty($algorithms)) {
            $algoIdentity = $algorithms[array_rand($algorithms, 1)]->algorithm;
            if (!array_key_exists($algoIdentity, $this->algorithms))
                throw new AlgorithmDoesntExistException("Algorithm with identity ({$algoIdentity}) doesn't exist !");
            $algorithm = $this->algorithms[$algoIdentity];
        }

        if (!$this->container->has($algorithm))
            throw new CantInitializeAlgorithmException("{$algorithm} class was not registered in container");

        $abstract = $this->container->get($algorithm)->setBlockCount($blockCount)->setWebsiteDto($websiteDto);
        $posts = call_user_func([$abstract, 'handle'], $website);

        if (count($posts) < $blockCount) {
            $reserved = $this->postReserver->getPosts($posts, $blockCount, $websiteDto);
            $abstract->setProfilerInformation('reserved_post_count', $reserved->get('reservedCount'));
            $posts = $reserved->get('posts');
        }

        shuffle($posts);

        $abstract->setProfilerInformation('used_algorithm', $abstract->getIdentity());
        $abstract->setProfilerInformation('total_banners_count', count($posts));

        return collect([
            'algorithm' => $abstract->getIdentity(),
            'posts'     => $posts,
            'profiler'  => $abstract->getProfilerInformation(),
        ]);
    }

    public function getAlgorithmList(): array
    {
        return array_keys($this->algorithms);
    }
}
