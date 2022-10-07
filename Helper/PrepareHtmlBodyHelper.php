<?php

namespace Alekseon\CustomFormsEmailNotification\Helper;

use Alekseon\CustomFormsBuilder\Model\FormRecord;
use Magento\Framework\App\Helper\AbstractHelper;

class PrepareHtmlBodyHelper extends AbstractHelper
{
    public function optionsFieldsByTemplateForm(FormRecord $formRecord, string $content): string
    {
        $resource = $formRecord->getResource();
        $resource->loadAllAttributes();
        $attributes = $resource->getAllLoadedAttributes();

        $valueFields = $formRecord->getData();

        foreach($attributes as $fieldId => $attribute) {
            $variable = '{{alekseon_widgets.' . $fieldId . '}}';

            if (str_contains($content, $variable)) {
                $replace = !empty($valueFields[$fieldId]) ? $valueFields[$fieldId] : null;
                $content = str_replace($variable, $replace, $content);
            }
        }
        return $content;
    }
}
