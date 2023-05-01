<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Setup;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\Scopes;
use Alekseon\AlekseonEav\Setup\EavDataSetupFactory;
use Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Class UpgradeData
 * @package Alekseon\CustomFormsEmailNotification\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavDataSetupFactory
     */
    protected EavDataSetupFactory $eavSetupFactory;
    /**
     * @var AttributeRepository
     */
    protected AttributeRepository $formAttributeRepository;

    /**
     * InstallData constructor.
     * @param EavDataSetupFactory $eavSetupFactory
     * @param AttributeRepository $formAttributeRepository
     */
    public function __construct(
        EavDataSetupFactory $eavSetupFactory,
        AttributeRepository $formAttributeRepository
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->formAttributeRepository = $formAttributeRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\TemporaryState\CouldNotSaveException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addCustomerEmailNotifactionAttributes();
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->updateCustomerEmailField();
            $this->addAdminConfirmationEmailTemplateAttribute();
        }
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\TemporaryState\CouldNotSaveException
     */
    protected function addCustomerEmailNotifactionAttributes()
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->createAttribute(
            'customer_email_notification_enable',
            [
                'frontend_input' => 'boolean',
                'frontend_label' => 'Send confirmation Email to customer',
                'visible_in_grid' => false,
                'sort_order' => 10,
                'scope' => Scopes::SCOPE_GLOBAL,
                'group_code' => 'customer_confirmation_email',
            ]
        );

        $eavSetup->createAttribute(
            'customer_notification_email_field',
            [
                'frontend_input' => 'select',
                'frontend_label' => 'Email field',
                'backend_type' => 'varchar',
                'source_model' => 'Alekseon\CustomFormsBuilder\Model\Attribute\Source\TextFormAttributes',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 20,
                'scope' => Scopes::SCOPE_GLOBAL,
                'group_code' => 'customer_confirmation_email',
            ]
        );

        $eavSetup->createAttribute(
            'customer_notification_identity',
            [
                'frontend_input' => 'select',
                'frontend_label' => 'Sender',
                'backend_type' => 'varchar',
                'source_model' => 'Alekseon\AlekseonEav\Model\Attribute\Source\EmailIdentity',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 30,
                'scope' => Scopes::SCOPE_STORE,
                'group_code' => 'customer_confirmation_email',
            ]
        );

        $eavSetup->createAttribute(
            'customer_notification_template',
            [
                'frontend_input' => 'select',
                'frontend_label' => 'Template',
                'backend_type' => 'varchar',
                'source_model' => 'Alekseon\AlekseonEav\Model\Attribute\Source\EmailTemplate',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 40,
                'scope' => Scopes::SCOPE_STORE,
                'group_code' => 'customer_confirmation_email',
            ]
        );

        $eavSetup->createAttribute(
            'customer_notification_copy_to',
            [
                'frontend_input' => 'text',
                'frontend_label' => 'Copy To',
                'backend_type' => 'text',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 50,
                'scope' => Scopes::SCOPE_STORE,
                'group_code' => 'customer_confirmation_email',
                'note' => __('Comma-separated'),
            ]
        );
    }

    protected function updateCustomerEmailField()
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);
        $eavSetup->updateAttribute(
            'customer_notification_email_field',
            [
                'frontend_label' => 'Customer Email Field',
                'group_code' => 'customer_email',
            ]
        );
    }

    protected function addAdminConfirmationEmailTemplateAttribute()
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->createAttribute(
            'admin_confirmation_email_template',
            [
                'frontend_input' => 'select',
                'frontend_label' => 'Template',
                'backend_type' => 'varchar',
                'source_model' => 'Alekseon\CustomFormsEmailNotification\Model\Attribute\Source\AdminConfirmationTemplate',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 40,
                'scope' => Scopes::SCOPE_STORE,
                'group_code' => 'confirmation_email',
            ]
        );
    }
}
