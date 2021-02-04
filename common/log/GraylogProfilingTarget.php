<?php

namespace common\log;

use Yii;
use Gelf\Message;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\log\Target;
use core\services\api\BannerInformationDto;

/**
 * Class GraylogProfilingTarget
 * @package common\log
 */
class GraylogProfilingTarget extends Target
{

    private const QUERY_BEGIN = 80;
    private const QUERY_END   = 96;
    /**
     * @var array
     */
    private $profiler = [
        'sql'     => [],
        'actions' => []
    ];

    private $phpType = 0;
    private $mysqlType = 0;
    private $queryExecutionTotalTime = 0;
    private $queryCount = 0;

    /**
     *
     */
    public function export()
    {
        $this->formatMessages();

        $this->send(Yii::$app->request->getUrl());
    }

    /**
     *
     */
    private function formatMessages()
    {
        $sqlTimes = [];
        $actionTimes = [];
        foreach ($this->messages as $message) {
            $msg = $message[0];

            if (is_array($msg)) {
                $messageString = isset($msg['msg']) ? $msg['msg'] : '';
                if (isset($msg['data'])) {
                    $options['extra'] = $msg['data'];
                }
            } elseif (is_a($msg, Exception::class)) {
                $messageString = $msg->getMessage();
            } else {
                $messageString = $msg;
            }

            if (in_array($message[2], ['yii\\db\\Command::query', 'yii\\db\\Connection::open'])) {
                if ($message[2] == 'yii\\db\\Command::query') {
                    $this->queryCount++;
                }
                if ($message[1] == self::QUERY_BEGIN) {
                    $sqlTimes[md5($messageString)] = [
                        'name' => $messageString,
                        'startTime' => $message[3]
                    ];
                } else {
                    $sqlTimes[md5($messageString)]['endTime'] = $message[3];
                }
            } else {
                if ($message[1] == self::QUERY_BEGIN) {
                    $actionTimes[md5($messageString)] = [
                        'name' => $messageString,
                        'startTime' => $message[3]
                    ];
                } else {
                    $actionTimes[md5($messageString)]['endTime'] = $message[3];
                }
            }
        }

        foreach ($sqlTimes as $row) {
            if (isset($row['startTime'])) {
                if (isset($row['endTime'])) {
                    $this->queryExecutionTotalTime += $row['endTime'] - $row['startTime'];
                }

                $this->profiler['sql'][] = [$row['name'], round($this->queryExecutionTotalTime, 5)];
            }
        }

        foreach ($actionTimes as $row) {
            if (isset($row['startTime'])) {
                $this->queryExecutionTotalTime += $row['endTime'] - $row['startTime'];

                $this->profiler['actions'][] = [$row['name'], round($this->queryExecutionTotalTime, 5)];
            }
        }

        $this->queryExecutionTotalTime = round($this->queryExecutionTotalTime, 5);

        $pageExecutionTime = Yii::getLogger()->getElapsedTime();

        if ($pageExecutionTime >= 0 && $pageExecutionTime <= 1) {
            $this->phpType = 1;
        } elseif ($pageExecutionTime >= 1 && $pageExecutionTime <= 3) {
            $this->phpType = 3;
        } elseif ($pageExecutionTime >= 3 && $pageExecutionTime <= 5) {
            $this->phpType = 5;
        } elseif ($pageExecutionTime >= 5 && $pageExecutionTime <= 10) {
            $this->phpType = 10;
        } else {
            $this->phpType = 500;
        }

        if ($this->queryCount >= 0 && $this->queryCount <= 10) {
            $this->mysqlType = 10;
        } elseif ($this->queryCount >= 10 && $this->queryCount <= 30) {
            $this->mysqlType = 30;
        } elseif ($this->queryCount >= 30 && $this->queryCount <= 50) {
            $this->mysqlType = 50;
        } elseif ($this->queryCount >= 50 && $this->queryCount <= 100) {
            $this->mysqlType = 100;
        } else {
            $this->mysqlType = 500;
        }
    }

    /**
     * @param string $subject
     * @throws InvalidConfigException
     */
    private function send(string $subject)
    {
        $messageHandler = new Message();
        $messageHandler->setShortMessage($subject);
        $messageHandler->setHost(Yii::$app->params['graylogHost']);
        $messageHandler->setLevel(3);
        $messageHandler->setFacility('phpdebug');

        $uri = Yii::$app->request->getUrl();
        $messageHandler->setAdditional('response_time', Yii::getLogger()->getElapsedTime());
        $messageHandler->setAdditional('request_uri', $uri);
        $messageHandler->setAdditional('request_uri_hash', md5($uri));
        $messageHandler->setAdditional('query_time', $this->queryExecutionTotalTime);
        $messageHandler->setAdditional('query_count', $this->queryCount);
        $messageHandler->setAdditional('profile', print_r($this->profiler['sql'], true));
        $messageHandler->setAdditional('php_type', $this->phpType);
        $messageHandler->setAdditional('mysql_type', $this->mysqlType);
        $messageHandler->setAdditional('profile_page', print_r($this->profiler['actions'], true));
        $messageHandler->setAdditional('ip_address', Yii::$app->request->getUserIP());

        if (!empty(($info = BannerInformationDto::getInformation()))) {
            $messageHandler->setAdditional('banner_count_information', print_r($info, true));
            $messageHandler->setAdditional('used_algorithm', $info['used_algorithm']);
            $messageHandler->setAdditional('total_banners_count', $info['total_banners_count']);
        }

        $publisher = new Publisher();
        $publisher->addTransport(new UdpTransport('l.ria.az',12666));
        $publisher->publish($messageHandler);
    }
}
