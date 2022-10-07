<?php

namespace Alekseon\CustomFormsEmailNotification\Plugin;

use Alekseon\CustomFormsEmailNotification\Helper\PrepareHtmlBodyHelper;

class EmailTemplatePlugin
{
    private PrepareHtmlBodyHelper $prepareHtmlBodyHelper;

    public function __construct(PrepareHtmlBodyHelper $prepareHtmlBodyHelper)
    {
        $this->prepareHtmlBodyHelper = $prepareHtmlBodyHelper;
    }

    public function aroundGetProcessedTemplate(\Magento\Framework\Mail\TemplateInterface $subject, callable $proceed, array $variables): string
    {
        $text = $proceed($variables);

        if(isset($variables['entity'])) {
            return $this->prepareHtmlBodyHelper->optionsFieldsByTemplateForm($variables['entity'], $text);
        }

        return $text;
    }
}
