<?php

namespace Alekseon\CustomFormsEmailNotification\Model\Attribute\Source;

use Alekseon\AlekseonEav\Model\Attribute\Source\AbstractSource;
use Magento\Config\Model\Config\Source\Email\Identity as MageIdentity;

class Identity extends AbstractSource
{

    /**
     * @var MageIdentity
     */
    private $identity;


    /**
     * @param MageIdentity $identity
     */
    public function __construct(MageIdentity $identity)
    {
        $this->identity = $identity;
    }



    public function getOptions()
    {
        $emailIdentities = $this->identity->toOptionArray();
        $options = [];


        foreach ($emailIdentities as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }
}
