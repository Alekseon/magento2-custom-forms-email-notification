<?php

namespace Alekseon\CustomFormsEmailNotification\Observer;

use Alekseon\CustomFormsBuilder\Model\Form;
use Alekseon\CustomFormsBuilder\Model\FormRecord;
use Alekseon\CustomFormsEmailNotification\Model\Config;
use Alekseon\CustomFormsEmailNotification\Model\Email\EmailNotification;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;

class FormRecordSaveAfter implements ObserverInterface
{
    /**
     * @var EmailNotification
     */
    protected $emailNotification;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @param EmailNotification $emailNotification
     */
    public function __construct(
        EmailNotification $emailNotification,
        Config $config
    ) {
        $this->emailNotification  =$emailNotification;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        /** @var FormRecord $formRecord */
        $formRecord = $observer->getObject();
        if (!$formRecord->isObjectNew()) {
           // return;
        }
            /** @var Form $form */
        $form = $formRecord->getForm();

        if ($form->getEnableEmailNotification()) {
           $this->notifyAdmin($formRecord, $form);
        }

        if ($form->getEnableCustomerNotification() &&  $form->getFieldEmailNotification()) {
            $this->notifyCustomer($formRecord, $form);
        }
    }

    /**
     * @param FormRecord $formRecord
     * @param Form $form
     * @throws LocalizedException
     * @throws MailException
     */
    private function notifyAdmin(FormRecord $formRecord, Form $form): void
    {
        $this->emailNotification->sendNotificationEmail(
            $this->config->getReceivers(),
            $this->config->getTemplateId(),
            $this->config->getIdentity(),
            [
                'form' => $form,
                'entity' => $formRecord,
            ]
        );
    }

    /**
     * @param FormRecord $formRecord
     * @param Form $form
     * @throws LocalizedException
     * @throws MailException
     */
    private function notifyCustomer(FormRecord $formRecord, Form $form): void
    {
        $receivers = [$formRecord->getData($form->getFieldEmailNotification())];
        $receivers[] = $form->getCcEmailNotification() ?? [];

        $this->emailNotification->sendNotificationEmail(
            $receivers,
            $form->getTemplateEmailNotification(),
            $form->getSenderEmailNotification(),
            [
                'form' => $form,
                'entity' => $formRecord,
            ]
        );
    }
}
