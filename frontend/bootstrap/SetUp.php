<?php

namespace frontend\bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use frontend\repositories\PostRepository;
use core\repositories\interfaces\PostRepositoryInterface;

/**
 * Class SetUp
 * @package frontend\bootstrap
 */
class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(PostRepositoryInterface::class, PostRepository::class);
    }
}