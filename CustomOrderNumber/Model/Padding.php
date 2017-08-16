<?php

namespace Bss\CustomOrderNumber\Plugin\SalesSequence\Model;

use Magento\SalesSequence\Model\Sequence;

class SequencePlugin
{
    public function beforeGetCurrentValue()
    {
    	// echo $pattern;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectManager->get('Bss\CustomOrderNumber\Helper\Data');
        $padding = $helper->getOrderPadding();
        $pattern = "%s%'.0".$padding."d%s";
        $this->pattern = $pattern;
        echo $this->pattern; echo "<pre>";
        echo $pattern; echo "<pre>";
    }
}
