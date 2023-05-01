<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Model\Email;

use Alekseon\CustomFormsBuilder\Model\FormRecord;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractSender
{
    protected $formRecord;
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var SenderResolverInterface
     */
    protected $senderResolver;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var State
     */
    protected $appState;

    /**
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
        LoggerInterface $logger,
        State $appState
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->senderResolver = $senderResolver;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->appState = $appState;
    }

    /**
     * @param FormRecord $formRecord
     * @return $this
     */
    public function setFormRecord(FormRecord $formRecord)
    {
        $this->formRecord = $formRecord;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateParams()
    {
        $form = $this->formRecord->getForm();
        $templateParams = [
            'form' => $form,
            'record' => $this->formRecord,
        ];
        return $templateParams;
    }

    abstract public function getReceiverEmails();

    abstract public function getSender();

    abstract public function getTemplateId();

    /**
     * @return false
     */
    public function getReplyToEmail()
    {
        return false;
    }

    /**
     * @return null
     */
    public function send()
    {
        return $this->appState->emulateAreaCode(Area::AREA_FRONTEND, [$this, 'sendEmailTemplate']);
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendEmailTemplate()
    {
        $templateId = $this->getTemplateId();
        $sender = $this->getSender();
        $templateParams = $this->getTemplateParams();
        $emails = $this->getReceiverEmails();
        $replyToEmail = $this->getReplyToEmail();

        $email = array_pop($emails);

        if (!$email || !$sender) {
            return false;
        }

        $storeId = $this->storeManager->getDefaultStoreView()->getId();
        $from = $this->senderResolver->resolve($sender);

        $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $storeId
                ]
            )
            ->setTemplateVars($templateParams)
            ->setFrom($from)
            ->addTo($email);

        if ($replyToEmail) {
            $this->transportBuilder->setReplyTo($replyToEmail);
        }

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

