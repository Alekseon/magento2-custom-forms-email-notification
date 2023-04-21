<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Cron;

use Alekseon\CustomFormsEmailNotification\Model\EmailNotification;

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
     * @param \Alekseon\CustomFormsEmailNotification\Model\EmailNotificationFactory $emailNotificationFactory
     */
    public function __construct(
        \Alekseon\CustomFormsEmailNotification\Model\EmailNotificationFactory $emailNotificationFactory,
        \Magento\Framework\App\State $appState
    )
    {
        $this->emailNotificationFactory = $emailNotificationFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        var_dump("execute");
        $emailNotificationCollection = $this->emailNotificationFactory->create()->getCollection();
        $emailNotificationCollection->addFieldToFilter('sent_status', EmailNotification::STATUS_PENDING);
        foreach ($emailNotificationCollection as $emailNotification) {
            var_dump($emailNotification->getId());
            $emailNotification->send(true);
        }
    }
}
