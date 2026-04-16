<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Model\Email;

use Magento\Store\Model\ScopeInterface;

/**
 * Class EmailNotification
 * @package Alekseon\CustomFormsEmailNotification\Model
 */
class EmailNotification extends AbstractSender
{
    /**
     * @return false|string[]
     */
    public function getReceiverEmails()
    {
        $form = $this->formRecord->getForm();
        $emails = $form->getAdminConfirmationEmailSendTo();
        return explode(',', $emails);
    }

    /**
     * @return false
     */
    public function getReplyToEmail()
    {
        $form = $this->formRecord->getForm();
        $emailField = $form->getCustomerNotificationEmailField();
        if ($emailField) {
            $email = $this->formRecord->getData((string)$emailField);
            if ($email) {
                return $email;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return (string) $this->scopeConfig->getValue(
            'alekseon_custom_forms/new_entity_notification_email/identity',
            ScopeInterface::SCOPE_STORE,
            $this->formRecord->getCreatedFromStoreId()
        );
    }

    /**
     * @return string
     */
    public function getTemplateId()
    {
        $form = $this->formRecord->getForm();
        return $form->getAdminConfirmationEmailTemplate();
    }
}
