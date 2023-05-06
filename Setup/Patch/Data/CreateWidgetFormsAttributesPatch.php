<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Setup\Patch\Data;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\Scopes;
use Alekseon\AlekseonEav\Setup\EavDataSetup;
use Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository;
use Alekseon\AlekseonEav\Setup\EavDataSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 *
 */
class CreateWidgetFormsAttributesPatch implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavDataSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var AttributeRepository
     */
    private $formAttributeRepository;

    /**
     * @param EavDataSetupFactory $eavSetupFactory
     * @param AttributeRepository $formAttributeRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavDataSetupFactory $eavSetupFactory,
        AttributeRepository $formAttributeRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->formAttributeRepository = $formAttributeRepository;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavDataSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $this->createCustomerNotificationEmailFieldAttribute($eavSetup);
        $this->createAdminNotificationAttributes($eavSetup);
        $this->createCustomerConfirmationAttributes($eavSetup);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    private function createCustomerConfirmationAttributes($eavSetup)
    {
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
    }

    /**
     * @param $eavSetup
     * @return void
     */
    private function createAdminNotificationAttributes($eavSetup)
    {
        $eavSetup->createAttribute(
            'enable_email_notification',
            [
                'frontend_input' => 'boolean',
                'frontend_label' => 'Notify admin By Email about new entities',
                'visible_in_grid' => true,
                'sort_order' => 10,
                'scope' => Scopes::SCOPE_GLOBAL,
                'group_code' => 'confirmation_email',
            ]
        );

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

    /**
     * @param $eavSetup
     * @return void
     */
    private function createCustomerNotificationEmailFieldAttribute($eavSetup)
    {
        $eavSetup->createOrUpdateAttribute(
            'customer_notification_email_field',
            [
                'frontend_label' => 'Customer Email Field',
                'group_code' => 'customer_email',
                'frontend_input' => 'select',
                'backend_type' => 'varchar',
                'source_model' => 'Alekseon\CustomFormsBuilder\Model\Attribute\Source\TextFormAttributes',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 20,
                'scope' => Scopes::SCOPE_GLOBAL,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->deleteAttribute('enable_email_notification');
        $eavSetup->deleteAttribute('customer_email_notification_enable');
        $eavSetup->deleteAttribute('customer_notification_email_field');
        $eavSetup->deleteAttribute('customer_notification_identity');
        $eavSetup->deleteAttribute('customer_notification_template');
        $eavSetup->deleteAttribute('customer_notification_copy_to');
        $eavSetup->deleteAttribute('admin_confirmation_email_template');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
