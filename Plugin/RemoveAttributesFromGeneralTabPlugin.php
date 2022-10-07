<?php

namespace Alekseon\CustomFormsEmailNotification\Plugin;

class RemoveAttributesFromGeneralTabPlugin
{
    /**
     * @param $generalTabBlock
     * @param $generalFieldset
     * @param $formObject
     * @param array $groups
     * @return array
     */
    public function beforeAddAllAttributeFields($generalTabBlock, $generalFieldset, $formObject, $groups = []): array
    {
        $groups['excluded'][] = 'confirmation_email';
        $groups['excluded'][] = 'new_entity_notification_email';
        return [$generalFieldset, $formObject, $groups];
    }
}