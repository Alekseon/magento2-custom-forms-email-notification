<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Observer;

use Alekseon\CustomFormsEmailNotification\Model\EmailNotificationFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FormAfterSubmit
 * @package Alekseon\CustomFormsEmailNotification\Observer
 */
class FormAfterSubmit implements ObserverInterface
{
    /**
     * @var EmailNotificationFactory
     */
    protected $emailNotificationFactory;

    /**
     * @param EmailNotificationFactory $emailNotificationFactory
     */
    public function __construct(
        EmailNotificationFactory $emailNotificationFactory
    )
    {
        $this->emailNotificationFactory = $emailNotificationFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $formRecord = $observer->getFormRecord();
        $form = $formRecord->getForm();
        if ($form->getCustomerEmailNotificationEnable()) {
            $emailNotification = $this->emailNotificationFactory->create();
            $emailNotification->setFormId($form->getId());
            $emailNotification->setRecordId($formRecord->getId());
            $emailNotification->setNotificationType('customer_confirmation');
            $emailNotification->getResource()->save($emailNotification);
        }
    }
}
