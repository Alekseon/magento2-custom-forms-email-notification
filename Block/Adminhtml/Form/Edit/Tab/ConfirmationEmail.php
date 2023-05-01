<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsEmailNotification\Block\Adminhtml\Form\Edit\Tab;

use Alekseon\AlekseonEav\Api\Data\AttributeInterface;
use Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Exception\LocalizedException;

class ConfirmationEmail extends Form implements TabInterface
{
    const TITLE = 'Confirmation email';

    const DEFAULT_CUSTOMER_CONFIRATION_TEMPLATE_CONFIG_PATH = 'alekseon_custom_forms/customer_confirmation_email/template';
    const DEFAULT_ADMIN_CONFIRATION_TEMPLATE_CONFIG_PATH = 'alekseon_custom_forms/new_entity_notification_email/template';

    protected $dataObject;

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden(): bool
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getDataObject()
    {
        if (null === $this->dataObject) {
            return $this->_coreRegistry->registry('current_form');
        }
        return $this->dataObject;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm(): self
    {
        $dataObject = $this->getDataObject();

        $form = $this->_formFactory->create();

        $customerEmailFieldset = $form->addFieldset('customer_email',
            ['legend' => __('Customer Email')]
        );

        $this->addAllAttributeFields($customerEmailFieldset, $dataObject, ['included' => ['customer_email']]);

        $adminConfirmationEmailFieldset = $form->addFieldset('confirmation_email_settings',
            ['legend' => __('Admin Confirmation Email')]
        );

        $this->addAllAttributeFields($adminConfirmationEmailFieldset, $dataObject, ['included' => ['confirmation_email']]);

        if ($dataObject->getCanUseForWidget()) {
            $customerConfirmationEmailFieldset = $form->addFieldset('customer_confirmation_email_settings',
                ['legend' => __('Customer Confirmation Email')]
            );

            $this->addAllAttributeFields($customerConfirmationEmailFieldset, $dataObject, ['included' => ['customer_confirmation_email']]);
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param $formFieldset
     * @param AttributeInterface $attribute
     */
    public function addAttributeField($formFieldset, AttributeInterface $attribute)
    {
        if ($attribute->getAttributeCode() == 'customer_notification_template') {
            $attribute->getSourceModel()->setPath($this->_scopeConfig->getValue(self::DEFAULT_CUSTOMER_CONFIRATION_TEMPLATE_CONFIG_PATH));
        }

        if ($attribute->getAttributeCode() == 'admin_confirmation_email_template') {
            $attribute->getSourceModel()->setPath($this->_scopeConfig->getValue(self::DEFAULT_ADMIN_CONFIRATION_TEMPLATE_CONFIG_PATH));
        }

        return parent::addAttributeField($formFieldset, $attribute);
    }

    /**
     * Initialize form fileds values
     *
     * @return $this
     */
    protected function _initFormValues(): self
    {
        $data = $this->getDataObject()->getData();

        if (!$this->getDataObject()->getCustomerNotificationTemplate()) {
            $data['customer_notification_template'] = $this->_scopeConfig->getValue(self::DEFAULT_CUSTOMER_CONFIRATION_TEMPLATE_CONFIG_PATH);
        }

        if (!$this->getDataObject()->getCustomerNotificationIdentity()) {
            $data['customer_notification_identity'] = 'general';
        }

        $this->getForm()->addValues($data);
        return parent::_initFormValues();
    }
}
