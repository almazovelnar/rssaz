<?php

namespace core\listeners;

use Yii;
use RuntimeException;
use yii\mail\MailerInterface;
use core\entities\Customer\Customer;
use core\repositories\CustomerRepository;
use core\events\RssParseItemErrorDetected;

/**
 * Class RssParseItemErrorDetectedListener
 * @package core\listeners
 */
class RssParseItemErrorDetectedListener
{
    private MailerInterface $mailer;
    private CustomerRepository $customerRepository;

    public function __construct(MailerInterface $mailer, CustomerRepository $customerRepository)
    {
        $this->mailer = $mailer;
        $this->customerRepository = $customerRepository;
    }

    public function handle(RssParseItemErrorDetected $event)
    {
       //
    }
}