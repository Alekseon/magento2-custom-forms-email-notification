<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Plugin;

/**
 * Class RemoveAttributesFromGeneralTabPlugin
 * @package Alekseon\CustomFormsEmailNotification\Plugin
 */
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

        return [$generalFieldset, $formObject, $groups];
    }
}
