<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Model;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Model
 */
class EmailNotification extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_PENDING = 'pending';

    /**
     * @param \Alekseon\AlekseonEav\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\EmailNotification $resource
     * @param ResourceModel\EmailNotification\Collection $resourceCollection
     */
    public function __construct(
        \Alekseon\AlekseonEav\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\CustomFormsEmailNotification\Model\ResourceModel\EmailNotification $resource,
        \Alekseon\CustomFormsEmailNotification\Model\ResourceModel\EmailNotification\Collection $resourceCollection
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * @return EmailNotification
     */
    public function beforeSave()
    {
        if (!$this->getSentStatus()) {
            $this->setSentStatus(self::STATUS_PENDING);
        }
        return parent::beforeSave();
    }
}
