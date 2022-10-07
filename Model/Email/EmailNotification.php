<?php

namespace Alekseon\CustomFormsEmailNotification\Model\Email;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class EmailNotification
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
     * EmailNotification constructor.
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\SenderResolverInterface $senderResolver,
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
    public function sendNotificationEmail($receivers, $templateId, $sender, array $templateParams = []): bool
    {
       return $this->sendEmailTemplate(
            $receivers,
            $templateId,
            $sender,
            $templateParams
        );
    }

    /**
     * @param $emails
     * @param $templateId
     * @param $sender
     * @throws LocalizedException
     * @throws MailException
     */
    protected function sendEmailTemplate($emails, $templateId, $sender, $templateParams = [])
    {
        $storeId = $this->storeManager->getDefaultStoreView()->getId();

        $from = $this->senderResolver->resolve(
            $sender,
            $storeId
        );


        $email = array_pop($emails);

        if (!$email) {
            return false;
        }

        $this->transportBuilder
            ->setTemplateIdentifier($templateId)
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
