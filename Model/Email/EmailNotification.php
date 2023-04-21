<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
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
        $emailsConfig = (string) $this->scopeConfig->getValue(
            'alekseon_custom_forms/new_entity_notification_email/to',
            ScopeInterface::SCOPE_STORE,
            $this->formRecord->getCreatedFromStoreId()
        );
        $emails = explode(',', $emailsConfig);
        return $emails;
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
        return $this->scopeConfig->getValue('alekseon_custom_forms/new_entity_notification_email/template');
    }
}
