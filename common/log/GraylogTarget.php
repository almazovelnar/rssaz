<?php

namespace common\log;

use Yii;
use yii\helpers\VarDumper;
use yii\log\Target;
use Gelf;
use Psr\Log\LogLevel;

class GraylogTarget extends Target
{
    /**
     * @var string Graylog2 host
     */
    public $host = '127.0.0.1';

    /**
     * @var string Message host
     */
    public $messageHost = 'rss.az';

    /**
     * @var integer Graylog2 port
     */
    public $port = 12201;

    /**
     * @var string default facility name
     */
    public $facility = 'yii2-logs';

    /**
     * @var array default additional fields
     */
    public $additionalFields = [];

    /**
     * @var boolean whether to add authenticated user username to additional fields
     */
    public $addUsername = false;

    /**
     * @var int chunk size
     */
    public $chunkSize = Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN;

    /**
     * Sends log messages to Graylog2 input
     */
    public function export()
    {
        $transport = new Gelf\Transport\UdpTransport($this->host, $this->port, $this->chunkSize);
        $publisher = new Gelf\Publisher($transport);
        $gelfMsg = new Gelf\Message;
        // Set base parameters
        $gelfMsg->setLevel(LogLevel::ERROR)
            ->setShortMessage($this->getSubject())
            ->setHost($this->messageHost)
            ->setFacility($this->facility);

        $messages = array_map([$this, 'formatMessage'], $this->messages);
        $message = wordwrap(implode("\n", $messages), 70);
        $gelfMsg->setAdditional('error_message', $message);

        // Add username
        if (($this->addUsername) && (Yii::$app->has('user')) && ($user = Yii::$app->get('user')) && ($identity = $user->getIdentity(false))) {
            $gelfMsg->setAdditional('username', $identity->username);
        }
        // Add any additional fields the user specifies
        foreach ($this->additionalFields as $key => $value) {
            if (is_string($key) && !empty($key)) {
                if (is_callable($value)) {
                    $value = $value(Yii::$app);
                }
                if (!is_string($value) && !empty($value)) {
                    $value = VarDumper::dumpAsString($value);
                }
                if (empty($value)) {
                    continue;
                }
                $gelfMsg->setAdditional($key, $value);
            }
        }

        $publisher->publish($gelfMsg);
    }

    private function getSubject(): string
    {
        if (isset($this->messages[0][0]) && is_object($this->messages[0][0]))
            return ($this->messages[0][0])->getMessage();

        return 'An error occurred';
    }
}