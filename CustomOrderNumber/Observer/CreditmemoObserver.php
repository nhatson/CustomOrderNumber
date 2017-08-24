<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CreditmemoObserver implements ObserverInterface
{
    protected $helper;
    protected $sequence;

    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
        ) {
            $this->helper = $helper;
            $this->sequence = $sequence;
        }

    public function execute(Observer $observer)
    {   
        $creditmemoInstance = $observer->getCreditmemo();
        $storeId = $creditmemoInstance->getOrder()->getStoreId();
        if($this->helper->isCreditmemoEnable($storeId))
        {
            if($this->helper->isCreditmemoSameOrder($storeId) && (!$this->helper->isOrderEnable($storeId)))
            {
                return;
            }
            if($this->helper->isCreditmemoSameOrder($storeId))
            {
                $orderIncrement = $creditmemoInstance->getOrder()->getIncrementId();

                $replace = $this->helper->getCreditmemoReplace($storeId);
                $replaceWith = $this->helper->getCreditmemoReplaceWith($storeId);
                $resutl = str_replace($replace, $replaceWith, $orderIncrement);

            } else {
                $format = $this->helper->getCreditmemoFormat($storeId);

                $startValue = $this->helper->getCreditmemoStart($storeId);
                $step = $this->helper->getCreditmemoIncrement($storeId);

                $padding = $this->helper->getCreditmemoPadding($storeId);            
                $pattern = "%0".$padding."d";
                if ($this->helper->isIndividualCreditmemoEnable($storeId))
                {
                    $table = 'sequence_creditmemo_'.$storeId;
                } else {
                    $table = 'sequence_creditmemo_0';
                }

                $lastIncrementId = $this->sequence->lastIncrementId($table);
                if (!isset($lastIncrementId)) 
                {
                    return;
                }

                $currentId = ($lastIncrementId - $startValue)*$step + $startValue;
                $counter = sprintf($pattern, $currentId);
                $resutl = $this->helper->replace($format, $storeId, $counter);
            }          

            $creditmemoInstance->setIncrementId($resutl);
        }           
    }
}
