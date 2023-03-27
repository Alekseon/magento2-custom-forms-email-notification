<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Model\ResourceModel\EmailNotification;

/**
 *
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(
            'Alekseon\CustomFormsEmailNotification\Model\EmailNotification',
            'Alekseon\CustomFormsEmailNotification\Model\ResourceModel\EmailNotification'
        );
    }
}
