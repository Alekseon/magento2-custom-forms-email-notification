<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Observer;

use Alekseon\CustomFormsBuilder\Model\FormRecord;
use Alekseon\CustomFormsEmailNotification\Model\Email\EmailNotification;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FormRecordSaveAfter
 * @package Alekseon\CustomFormsEmailNotification\Observer
 */
class FormRecordSaveAfter implements ObserverInterface
{
    /**
     * @var EmailNotification
     */
    protected EmailNotification $emailNotification;

    /**
     * @param EmailNotification $emailNotification
     */
    public function __construct(EmailNotification $emailNotification)
    {
        $this->emailNotification = $emailNotification;
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
            $this->emailNotification->sendNotificationEmail(
                [
                    'form' => $form,
                    'record' => $formRecord,
                    'recordHtml' => '',
                ]
            );
        }
    }
}
