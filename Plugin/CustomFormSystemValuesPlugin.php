<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Plugin;

class CustomFormSystemValuesPlugin
{
    /**
     * @param \Alekseon\CustomFormsBuilder\Model\Form $form
     * @param $result
     * @return array
     */
    public function afterGetSystemValues(\Alekseon\CustomFormsBuilder\Model\Form $form, $result)
    {
        $result['admin_confirmation_email_template'] = [
            'provider' => \Alekseon\AlekseonEav\Model\Attribute\SystemValueProvider\ScopeConfigValue::class,
            'use_label' => 'Use Configuration Value',
            'params' => [
                'path' => 'alekseon_custom_forms/new_entity_notification_email/template',
            ],
        ];
        $result['admin_confirmation_email_send_to'] = [
            'provider' => \Alekseon\AlekseonEav\Model\Attribute\SystemValueProvider\ScopeConfigValue::class,
            'use_label' => 'Use Configuration Value',
            'params' => [
                'path' => 'alekseon_custom_forms/new_entity_notification_email/to',
            ],
        ];
        return $result;
    }
}
