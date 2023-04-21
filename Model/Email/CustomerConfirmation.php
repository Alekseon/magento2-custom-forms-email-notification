<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Model\Email;

/**
 * Class CustomerConfirmation
 * @package Alekseon\CustomFormsEmailNotification\Model\Email
 */
class CustomerConfirmation extends AbstractSender
{
    /**
     * @return array|false|string[]
     */
    public function getReceiverEmails()
    {
        $receiverEmails = [];
        $form = $this->formRecord->getForm();
        $emailField = $form->getCustomerNotificationEmailField();
        if ($emailField) {
            $email = $this->formRecord->getData((string)$emailField);
            if ($email) {
                $receiverEmails[] = $email;
            }
        }

        if (!empty($receiverEmails)) {
            if ($form->getCustomerNotificationCopyTo()) {
                $copyTo = explode(',', $form->getCustomerNotificationCopyTo());
                $receiverEmails = array_merge($copyTo, $receiverEmails);
            }
        }

        return $receiverEmails;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        $form = $this->formRecord->getForm();
        return $form->getCustomerNotificationIdentity();
    }

    /**
     * @return mixed
     */
    public function getTemplateId()
    {
        $form = $this->formRecord->getForm();
        return $form->getCustomerNotificationTemplate();
    }
}
