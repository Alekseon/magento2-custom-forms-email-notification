<?php

namespace Alekseon\CustomFormsEmailNotification\Model;

use Magento\Config\Model\ResourceModel\Config as ResourceConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Framework\UrlInterface;
use Magento\Sales\Model\Order;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Yotpo\Yotpo\Model\Logger as YotpoLogger;

class Config
{
    const MODULE_NAME = 'Alekseon_CustomFormsEmailNotification';

    const XML_PATH_NEW_ENTITY_EMAIL_IDENTITY = 'alekseon_custom_forms/new_entity_notification_email/identity';
    const XML_PATH_NEW_ENTITY_EMAIL_TEMPLATE = 'alekseon_custom_forms/new_entity_notification_email/template';
    const XML_PATH_NEW_ENTITY_EMAIL_RECEIVER = 'alekseon_custom_forms/new_entity_notification_email/to';

    private $allStoreIds = [0 => null, 1 => null];

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;


    /**
     * @method __construct
     * @param  StoreManagerInterface    $storeManager
     * @param  ScopeConfigInterface     $scopeConfig
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;

    }

    /**
     * @method getStoreManager
     * @return StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * @method isSingleStoreMode
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->storeManager->isSingleStoreMode();
    }

    /**
     * @method getWebsiteIdByStoreId
     * @param int $storeId
     * @return int
     * @throws NoSuchEntityException
     */
    public function getWebsiteIdByStoreId($storeId): int
    {
        return $this->storeManager->getStore($storeId)->getWebsiteId();
    }

    /**
     * @return mixed
     */
    public function getConfig($configPath, $scopeId = null, $scope = null)
    {
        if (!$scope && $this->isSingleStoreMode()) {
            return $this->scopeConfig->getValue($configPath);
        }
        return $this->scopeConfig->getValue($configPath, $scope ?: ScopeInterface::SCOPE_STORE, is_null($scopeId) ? $this->storeManager->getStore()->getId() : $scopeId);
    }

    /**
     * @return string
     */
    public function getIdentity($scopeId = null, $scope = null): string
    {
        return $this->getConfig(self::XML_PATH_NEW_ENTITY_EMAIL_IDENTITY, $scopeId, $scope);
    }

    /**
     * @return string
     */
    public function getTemplateId($scopeId = null, $scope = null): string
    {
        return $this->getConfig(self::XML_PATH_NEW_ENTITY_EMAIL_TEMPLATE, $scopeId, $scope);
    }

    /**
     * @return string
     */
    public function getReceivers($scopeId = null, $scope = null): array
    {
        $emailsConfig = $this->getConfig(self::XML_PATH_NEW_ENTITY_EMAIL_RECEIVER, $scopeId, $scope);

        return $emailsConfig ? explode(',', $emailsConfig) : [];
    }
}
