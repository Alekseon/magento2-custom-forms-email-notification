<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Setup\Patch\Data;

use Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class RemoveFromConfigurationTemplateValues implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var AttributeRepository
     */
    private $formAttributeRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AttributeRepository $formAttributeRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeRepository $formAttributeRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->formAttributeRepository = $formAttributeRepository;
    }


    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $setup = $this->moduleDataSetup;
        $templateAttribute = $this->formAttributeRepository
            ->getByAttributeCode('admin_confirmation_email_template', true);

        if ($templateAttribute->getId()) {
            $setup->getConnection()->delete(
                $setup->getTable('alekseon_custom_form_entity_varchar'),
                [
                    'attribute_id = ?' => $templateAttribute->getId(),
                    'value = ?' => '0'
                ]
            );
            $setup->getConnection()->delete(
                $setup->getTable('alekseon_custom_form_entity_varchar'),
                [
                    'attribute_id = ?' => $templateAttribute->getId(),
                    'value = ?' => ''
                ]
            );
        }

        $this->moduleDataSetup->getConnection()->endSetup();
        return $this;
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
