<?php

namespace api\bootstrap;

use Yii;
use yii\di\Container;
use yii\base\Application;
use yii\base\BootstrapInterface;
use core\repositories\PostRepository;
use core\repositories\interfaces\PostRepositoryInterface;
use core\algorithms\{DefaultAlgorithm, NewDefaultAlgorithm, TopHundredPostsAlgorithm};

/**
 * Class SetUp
 * @package api\bootstrap
 */
class SetUp implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(DefaultAlgorithm::class, function (Container $container) {
            /** @var PostRepository $postRepository */
            $postRepository = $container->get(PostRepositoryInterface::class);
            return new DefaultAlgorithm($postRepository);
        });

        $container->setSingleton(TopHundredPostsAlgorithm::class, function (Container $container) {
            /** @var PostRepository $postRepository */
            $postRepository = $container->get(PostRepositoryInterface::class);
            return new TopHundredPostsAlgorithm($postRepository);
        });

        $container->setSingleton(NewDefaultAlgorithm::class, function (Container $container) {
            /** @var PostRepository $postRepository */
            $postRepository = $container->get(PostRepositoryInterface::class);
            return new NewDefaultAlgorithm($postRepository);
        });
    }
}