<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

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
    }

}
