<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeAddAllAttributeFields($generalTabBlock, $generalFieldset, $formObject, $groups = []): array
    {
        $groups['excluded'][] = 'confirmation_email';
        $groups['excluded'][] = 'customer_confirmation_email';
        $groups['excluded'][] = 'customer_email';

        return [$generalFieldset, $formObject, $groups];
    }
}
