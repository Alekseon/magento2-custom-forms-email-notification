<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Block\Adminhtml\Form\Edit\Tab;

use Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Exception\LocalizedException;

class ConfirmationEmail extends Form implements TabInterface
{
    const TITLE = 'Confirmation email';

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
        return (bool) $this->getDataObject()->getCanUseForWidget();
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

        $widgetFieldset = $form->addFieldset('confirmation_email_settings', ['legend' => __(self::TITLE)]);
        $this->addAllAttributeFields($widgetFieldset, $dataObject, ['included' => ['confirmation_email']]);

        $this->setForm($form);

        return parent::_prepareForm();
    }


    /**
     * Initialize form fileds values
     *
     * @return $this
     */
    protected function _initFormValues(): self
    {
        $this->getForm()->addValues($this->getDataObject()->getData());
        return parent::_initFormValues();
    }
}
