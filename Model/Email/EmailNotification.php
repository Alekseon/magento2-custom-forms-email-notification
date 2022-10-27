<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Model\Email;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class EmailNotification
 * @package Alekseon\CustomFormsEmailNotification\Model
 */
class EmailNotification
{
    const XML_PATH_NEW_ENTITY_EMAIL_IDENTITY = 'alekseon_custom_forms/new_entity_notification_email/identity';
    const XML_PATH_NEW_ENTITY_EMAIL_TEMPLATE = 'alekseon_custom_forms/new_entity_notification_email/template';
    const XML_PATH_NEW_ENTITY_EMAIL_RECEIVER = 'alekseon_custom_forms/new_entity_notification_email/to';

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
     * @param array $templateParams
     * @return bool
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendNotificationEmail(array $templateParams = []): bool
    {
        $emailsConfig = $this->scopeConfig->getValue(self::XML_PATH_NEW_ENTITY_EMAIL_RECEIVER);
        if (!$emailsConfig) {
            return false;
        }

        $emails = explode(',', $emailsConfig);

        return $this->sendEmailTemplate(
            $emails,
            self::XML_PATH_NEW_ENTITY_EMAIL_TEMPLATE,
            self::XML_PATH_NEW_ENTITY_EMAIL_IDENTITY,
            $templateParams
        );
    }

    /**
     * @param array|string $emails
     * @param string|int $templateId
     * @param string $sender
     * @param array $templateParams
     * @return bool
     * @throws LocalizedException
     * @throws MailException
     */
    protected function sendEmailTemplate($emails, $templateId, string $sender, array $templateParams = []): bool
    {
        $storeId = $this->storeManager->getDefaultStoreView()->getId();
        $templateId = $this->scopeConfig->getValue($templateId);

        if (!is_array($emails)) {
            $emails = [$emails];
        }

        $from = $this->senderResolver->resolve(
            $this->scopeConfig->getValue($sender, ScopeInterface::SCOPE_STORE, $storeId),
            $storeId
        );

        $email = array_pop($emails);

        if (!$email) {
            return false;
        }

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
