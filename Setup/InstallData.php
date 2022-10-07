<?php

namespace Alekseon\CustomFormsEmailNotification\Setup;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\Scopes;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Alekseon\AlekseonEav\Setup\EavDataSetupFactory;
use Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository;

class InstallData implements InstallDataInterface
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
    public function __construct(EavDataSetupFactory $eavSetupFactory, AttributeRepository $formAttributeRepository)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->formAttributeRepository = $formAttributeRepository;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->createOrUpdateAttribute('enable_email_notification',
            [
                'frontend_input' => 'boolean',
                'frontend_label' => 'Notify admin By Email about new entities',
                'visible_in_grid' => true,
                'sort_order' => 10,
                'scope' => Scopes::SCOPE_GLOBAL,
                'group_code' => 'confirmation_email',
            ]
        );

        $eavSetup->createOrUpdateAttribute(
            'enable_customer_notification',
            [
                'frontend_input' => 'boolean',
                'frontend_label' => 'Send confirmation email to customer',
                'visible_in_grid' => true,
                'sort_order' => 10,
                'scope' => Scopes::SCOPE_GLOBAL,
                'group_code' => 'new_entity_notification_email',
            ]
        );

        $eavSetup->createOrUpdateAttribute(
            'field_email_notification',
            [
                'frontend_input' => 'select',
                'frontend_label' => 'Email field',
                'backend_type' => 'varchar',
                'source_model' => '\Alekseon\WidgetForms\Model\Attribute\Source\TextFormAttributes',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 20,
                'scope' => Scopes::SCOPE_GLOBAL,
                'group_code' => 'new_entity_notification_email',
            ]
        );

        $eavSetup->createOrUpdateAttribute(
            'cc_email_notification',
            [
                'frontend_input' => 'text',
                'frontend_label' => 'Cc',
                'visible_in_grid' => true,
                'is_required' => false,
                'sort_order' => 30,
                'group_code' => 'new_entity_notification_email',
                'scope' => Scopes::SCOPE_STORE,
            ]
        );

        $eavSetup->createOrUpdateAttribute(
            'template_email_notification',
            [
                'frontend_input' => 'select',
                'frontend_label' => 'Template',
                'backend_type' => 'varchar',
                'source_model' => 'Alekseon\CustomFormsEmailNotification\Model\Attribute\Source\Template',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 40,
                'group_code' => 'new_entity_notification_email',
                'scope' => Scopes::SCOPE_WEBSITE,
            ]
        );

        $eavSetup->createOrUpdateAttribute(
            'sender_email_notification',
            [
                'frontend_input' => 'select',
                'frontend_label' => 'Sender',
                'backend_type' => 'varchar',
                'source_model' => 'Alekseon\CustomFormsEmailNotification\Model\Attribute\Source\Identity',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 50,
                'group_code' => 'new_entity_notification_email',
                'scope' => Scopes::SCOPE_WEBSITE,
            ]
        );
    }

}
