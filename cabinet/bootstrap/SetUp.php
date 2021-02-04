<?php

namespace cabinet\bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use core\repositories\CustomerRepository;
use core\repositories\interfaces\UserRepositoryInterface;

/**
 * Class SetUp
 * @package cabinet\bootstrap
 */
class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;
        $container->setSingleton(UserRepositoryInterface::class, CustomerRepository::class);
    }
}