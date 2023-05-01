<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Cron;

use Alekseon\CustomFormsEmailNotification\Model\EmailNotification;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 *
 */
class SendEmails
{
    /**
     * @var \Alekseon\CustomFormsEmailNotification\Model\EmailNotificationFactory
     */
    protected $emailNotificationFactory;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Alekseon\CustomFormsEmailNotification\Model\EmailNotificationFactory $emailNotificationFactory
     */
    public function __construct(
        \Alekseon\CustomFormsEmailNotification\Model\EmailNotificationFactory $emailNotificationFactory,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->emailNotificationFactory = $emailNotificationFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $emailNotificationCollection = $this->emailNotificationFactory->create()->getCollection();
        $emailNotificationCollection->addFieldToFilter('sent_status', EmailNotification::STATUS_PENDING);
        $emailNotificationCollection->setPageSize(
            $this->scopeConfig->getValue('alekseon_custom_forms/notification_email/sending_limit')
        );
        foreach ($emailNotificationCollection as $emailNotification) {
            $emailNotification->send(true);
        }
    }
}
