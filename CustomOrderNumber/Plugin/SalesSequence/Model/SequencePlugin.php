<?php

namespace Bss\CustomOrderNumber\Override\SalesSequence\Model;

use Magento\SalesSequence\Model\Sequence;

class SequencePlugin extends Sequence
{
    public function aroundGetCurrentValue()
    {
    	// $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	// $helper = $objectManager->get('Bss\CustomOrderNumber\Helper\Data');
    	// $padding = $helper->getOrderPadding();
    	// $pattern = "%s%'.0".$padding."d%s";
    	// $this->pattern = $pattern;
    	return sprintf(
            "%s%'.05d%s",
            "aaa",
            "60",
            "xxxx"
        );
    }
}
