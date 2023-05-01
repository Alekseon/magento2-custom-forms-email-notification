<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Observer;

use Alekseon\CustomFormsEmailNotification\Model\EmailNotificationFactory;
use Alekseon\CustomFormsBuilder\Model\FormRecord;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FormRecordSaveAfter
 * @package Alekseon\CustomFormsEmailNotification\Observer
 */
class FormRecordSaveAfter implements ObserverInterface
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
    public function execute(Observer $observer): void
    {
        /** @var FormRecord $formRecord */
        $formRecord = $observer->getObject();
        $form = $formRecord->getForm();

        if ($formRecord->isObjectNew() && $form->getEnableEmailNotification()) {
            $emailNotification = $this->emailNotificationFactory->create();
            $emailNotification->setFormRecord($formRecord);
            $emailNotification->setNotificationType('new_record_notification');
            $emailNotification->send();
        }
    }
}
