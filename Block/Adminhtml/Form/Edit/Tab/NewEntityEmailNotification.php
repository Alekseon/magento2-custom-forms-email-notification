<?php

namespace Alekseon\CustomFormsEmailNotification\Block\Adminhtml\Form\Edit\Tab;

use Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class NewEntityEmailNotification extends Form implements TabInterface
{
    const TITLE = 'Send confirmation email to customer';
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
    public function canShowTab()
    {
        return (bool) $this->getDataObject()->getCanUseForWidget();
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
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
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm(): self
    {
        $dataObject = $this->getDataObject();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $widgetFieldset = $form->addFieldset('confirmation_email_settings', ['legend' => __(self::TITLE)]);
        $this->addAllAttributeFields($widgetFieldset, $dataObject, ['included' => ['new_entity_notification_email']]);

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
