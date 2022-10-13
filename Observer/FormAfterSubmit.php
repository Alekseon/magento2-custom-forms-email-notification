<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Observer;

use Alekseon\CustomFormsEmailNotification\Model\Email\CustomerConfirmation;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FormAfterSubmit
 * @package Alekseon\CustomFormsEmailNotification\Observer
 */
class FormAfterSubmit implements ObserverInterface
{
    /**
     * @var
     */
    protected $customerConfirmation;

    /**
     * FormAfterSubmit constructor.
     * @param CustomerConfirmation $customerConfirmation
     */
    public function __construct(
        CustomerConfirmation $customerConfirmation
    )
    {
        $this->customerConfirmation = $customerConfirmation;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $formRecord = $observer->getFormRecord();
        $this->customerConfirmation->send($formRecord);
    }
}
