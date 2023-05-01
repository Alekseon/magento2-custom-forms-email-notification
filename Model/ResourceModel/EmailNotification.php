<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Model\ResourceModel;

/**
 *
 */
class EmailNotification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init('alekseon_custom_form_email_notification', 'entity_id');
    }
}
