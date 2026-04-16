<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Setup\Patch\Data;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\Scopes;
use Alekseon\AlekseonEav\Setup\EavDataSetup;
use Alekseon\AlekseonEav\Setup\EavDataSetupFactory;
use Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AdminNotificationEmailSendEmailToAttribute implements DataPatchInterface, PatchRevertableInterface
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
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavDataSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->deleteAttribute('admin_confirmation_email_send_to');

        $eavSetup->createAttribute(
            'admin_confirmation_email_send_to',
            [
                'frontend_input' => 'text',
                'frontend_label' => 'Send Email To',
                'backend_type' => 'text',
                'visible_in_grid' => false,
                'is_required' => false,
                'sort_order' => 50,
                'scope' => Scopes::SCOPE_STORE,
                'group_code' => 'confirmation_email',
                'note' => __('Comma-separated'),
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);
        $eavSetup->deleteAttribute('admin_confirmation_email_send_to');

        $this->moduleDataSetup->getConnection()->endSetup();
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
    public function getAliases()
    {
        return [];
    }
}
