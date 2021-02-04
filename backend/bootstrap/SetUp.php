<?php

namespace backend\bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use core\repositories\UserRepository;
use core\repositories\interfaces\UserRepositoryInterface;

/**
 * Class SetUp
 * @package backend\bootstrap
 */
class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(UserRepositoryInterface::class, UserRepository::class);
    }
}
