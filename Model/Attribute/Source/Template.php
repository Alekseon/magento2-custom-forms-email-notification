<?php

namespace Alekseon\CustomFormsEmailNotification\Model\Attribute\Source;

use Magento\Framework\Registry;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;
use Alekseon\AlekseonEav\Model\Attribute\Source\AbstractSource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Email\Model\Template\Config;

/**
 * Source for template
 */
class Template extends AbstractSource
{
    const DEFAULT_TEMPLATE_FOR_CUSTOMER ='alekseon_custom_forms_new_entity_template_customer';
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var CollectionFactory
     */
    protected $templatesFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var Config
     */
    private Config $emailConfig;

    /**
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Email\Model\ResourceModel\Template\CollectionFactory $templatesFactory
     */
    public function __construct(
        Registry $coreRegistry,
        CollectionFactory $templatesFactory,
        ScopeConfigInterface $scopeConfig,
        Config $emailConfig
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->templatesFactory = $templatesFactory;
        $this->scopeConfig = $scopeConfig;
        $this->emailConfig = $emailConfig;
    }


    /**
     * Generate list of email templates
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        /** @var $collection \Magento\Email\Model\ResourceModel\Template\Collection */
        if (!($collection = $this->coreRegistry->registry('config_system_email_template'))) {
            $collection = $this->templatesFactory->create();
            $collection->load();
            $this->coreRegistry->register('config_system_email_template', $collection);
        }

        return $collection->toOptionArray();
    }

    /**
     * @return array|mixed
     */
    public function getOptions()
    {
        $emailTemplates = $this->toOptionArray();
        $options = [];

        $defaultEmailsTemplateId = self::DEFAULT_TEMPLATE_FOR_CUSTOMER;

        if ($defaultEmailsTemplateId) {
            $templateLabel = $this->emailConfig->getTemplateLabel($defaultEmailsTemplateId);
            $options[$defaultEmailsTemplateId] = __('%1 (Default)', $templateLabel->getText());
        }

        foreach ($emailTemplates as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }
}
