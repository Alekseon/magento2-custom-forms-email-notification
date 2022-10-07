<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsEmailNotification\Plugin;

use Alekseon\CustomFormsEmailNotification\Helper\PrepareHtmlBodyHelper;
use Magento\Framework\Mail\TemplateInterface;

/**
 * Class EmailTemplatePlugin
 * @package Alekseon\CustomFormsEmailNotification\Plugin
 */
class EmailTemplatePlugin
{
    /**
     * @var PrepareHtmlBodyHelper
     */
    private PrepareHtmlBodyHelper $prepareHtmlBodyHelper;

    /**
     * @param PrepareHtmlBodyHelper $prepareHtmlBodyHelper
     */
    public function __construct(PrepareHtmlBodyHelper $prepareHtmlBodyHelper)
    {
        $this->prepareHtmlBodyHelper = $prepareHtmlBodyHelper;
    }

    /**
     * @param TemplateInterface $subject
     * @param callable $proceed
     * @param array $variables
     * @return string
     */
    public function aroundGetProcessedTemplate(TemplateInterface $subject, callable $proceed, array $variables): string
    {
        $text = $proceed($variables);

        if(isset($variables['entity'])) {
            return $this->prepareHtmlBodyHelper->optionsFieldsByTemplateForm($variables['entity'], $text);
        }

        return $text;
    }
}
