<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Observer;

use Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Field\Messages;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FormAfterSubmit
 * @package Alekseon\CustomFormsEmailNotification\Observer
 */
class AddSampleDataWarning implements ObserverInterface
{
    protected $moduleManager;

    /**
     * @param \Magento\Framework\Module\Manager $moduleManager
     */
    public function __construct(\Magento\Framework\Module\Manager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->moduleManager->isEnabled('Magento_CustomerSampleData')) {
            $form = $observer->getForm();
            $messages = $form->getElements()->searchById('messages');
            /** @var Messages $messagesRender */
            $messagesRender = $messages->getRenderer();
            $messagesRender->addError(
                'Email sending is disabled when the Magento_CustomerSampleData module is installed.
                Read more
                <a href="https://github.com/Alekseon/magento2-widget-forms/wiki/Emails-Not-Sent-with-Magento_CustomerSampleData">
                here
                </a>.'
            );
        }
    }
}
