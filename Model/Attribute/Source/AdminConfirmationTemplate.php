<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Model\Attribute\Source;

use Alekseon\AlekseonEav\Model\Attribute\Source\EmailTemplate;

/**
 *
 */
class AdminConfirmationTemplate extends EmailTemplate
{
    /**
     * @return array|mixed|void
     */
    public function getOptions()
    {
        $options = [
            0 => __('From Configuration'),
        ];
        $templateOptions = parent::getOptions();
        foreach ($templateOptions as $value => $label) {
            $options[$value] = $label;
        }

        return $options;
    }
}
