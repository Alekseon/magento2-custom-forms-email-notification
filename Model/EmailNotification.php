<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Model;

use Alekseon\CustomFormsBuilder\Model\FormRecord;
use Alekseon\CustomFormsBuilder\Model\FormRepository;
use Alekseon\CustomFormsEmailNotification\Model\Email\AbstractSender;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Model
 */
class EmailNotification extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_MISSED = 'missed';
    const STATUS_ERRROR = 'error';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var
     */
    protected $formRecord;
    /**
     * @var FormRepository
     */
    protected $formRepository;
    /**
     * @var array|mixed
     */
    protected $emailSenders;

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
        \Alekseon\CustomFormsEmailNotification\Model\ResourceModel\EmailNotification\Collection $resourceCollection,
        FormRepository $formRepository,
        ScopeConfigInterface $scopeConfig,
        $emailSenders = []
    ) {
        $this->formRepository = $formRepository;
        $this->scopeConfig = $scopeConfig;
        $this->emailSenders = $emailSenders;
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

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFormRecord()
    {
        if ($this->formRecord == null) {
            $form = $this->formRepository->getById($this->getFormId());
            $this->formRecord = $form->getRecordById($this->getRecordId());
        }
        return $this->formRecord;
    }

    /**
     * @param FormRecord $formRecord
     * @return $this
     */
    public function setFormRecord(FormRecord $formRecord)
    {
        $this->setRecordId($formRecord->getId());
        $this->setFormId($formRecord->getFormId());
        $this->formRecord = $formRecord;
        return $this;
    }

    /**
     * @return false|AbstractSender
     */
    protected function getSenderModel()
    {
        $notificationType = $this->getNotificationType();
        if (isset($this->emailSenders[$notificationType])) {
            $emailSender = $this->emailSenders[$notificationType];
            return $emailSender;
        }
        return false;
    }

    /**
     * @param $forceSyncMode
     * @return void
     * @throws \Exception
     */
    public function send($forceSyncMode = false)
    {
        if (!$this->scopeConfig->getValue('alekseon_custom_forms/notification_email/async_sending') || $forceSyncMode) {
            $senderModel = $this->getSenderModel();
            $this->setSentStatus(self::STATUS_MISSED);
            if ($senderModel) {
                $fromRecord = $this->getFormRecord();
                $senderModel->setFormRecord($fromRecord);
                if ($senderModel->send()) {
                    $this->setSentStatus(self::STATUS_SENT);
                } else {
                    $this->setSentStatus(self::STATUS_ERRROR);
                }
            }
        } else {
            $this->setSentStatus(self::STATUS_PENDING);
        }

        $this->save();
    }
}
