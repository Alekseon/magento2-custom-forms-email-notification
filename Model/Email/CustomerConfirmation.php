<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Model\Email;

use Alekseon\CustomFormsBuilder\Model\FormRecord;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerConfirmation
 * @package Alekseon\CustomFormsEmailNotification\Model\Email
 */
class CustomerConfirmation
{
    /**
     * @var TransportBuilder
     */
    protected TransportBuilder $transportBuilder;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var SenderResolverInterface
     */
    protected SenderResolverInterface $senderResolver;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * EmailNotification constructor.
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param SenderResolverInterface $senderResolver
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        SenderResolverInterface $senderResolver,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->senderResolver = $senderResolver;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @param FormRecord $formRecord
     */
    public function send(FormRecord $formRecord)
    {
        $form = $formRecord->getForm();

        if (!$form->getCustomerEmailNotificationEnable()) {
            return false;
        }

        $emailField = $form->getCustomerNotificationEmailField();
        if (!$emailField) {
            return false;
        }

        $email = $formRecord->getData((string) $emailField);

        if (!$email) {
            return false;
        }

        $templateId = $form->getCustomerNotificationTemplate();
        $sender = $form->getCustomerNotificationIdentity();

        $emails = [$email];
        if ($form->getCustomerNotificationCopyTo()) {
            $copyTo = explode(',', $form->getCustomerNotificationCopyTo());
            $emails = array_merge($copyTo, $emails);
        }

        $templateParams = [
            'form' => $form,
            'record' => $formRecord,
        ];

        return $this->sendEmailTemplate($emails, $templateId, $sender, $templateParams);
    }

    /**
     * @param $email
     * @param $templateId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function sendEmailTemplate($emails, $templateId,$sender, $templateParams = [])
    {
        $email = array_pop($emails);

        if (!$email) {
            return false;
        }

        $from = $this->senderResolver->resolve($sender);
        $storeId = $this->storeManager->getDefaultStoreView()->getId();

        $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                    'store' => $storeId
                ]
            )
            ->setTemplateVars($templateParams)
            ->setFrom($from)
            ->addTo($email);

        foreach ($emails as $email) {
            $this->transportBuilder->addBcc($email);
        }

        $transport = $this->transportBuilder->getTransport();

        try {
            $transport->sendMessage();
            return true;
        } catch ( \Exception $e) {
            $this->logger->critical($e->getMessage());
            return false;
        }
    }
}
