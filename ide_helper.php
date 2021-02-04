<?php

namespace yii\web {

    use yii\queue\Queue;
    use core\components\Config;
    use kak\clickhouse\Connection;
    use core\components\Storage\Storage;
    use yii\redis\Connection as RedisConnection;

    /**
     * Class Application
     *
     * @package yii\web
     * @property Config $config
     * @property Queue $queue
     * @property UrlManager $frontendUrlManager
     * @property UrlManager $cabinetUrlManager
     * @property UrlManager $backendUrlManager
     * @property UrlManager $apiUrlManager
     * @property Storage $storage
     * @property Connection $clickhouse
     * @property RedisConnection $redis
     */
    class Application {}
}