<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderObserver implements ObserverInterface
{
    protected $helper;
    protected $sequence;
    protected $storeManager;

    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
        ) {
            $this->helper = $helper;
            $this->sequence = $sequence;
            $this->storeManager = $storeManager;
        }

    public function execute(Observer $observer)
    {   
        if($this->helper->isOrderEnable())
        {
            $storeId = $this->storeManager->getStore()->getStoreId();

            $format = $this->helper->getOrderFormat();

            $startValue = $this->helper->getOrderStart();
            $step = $this->helper->getOrderIncrement();

            $padding = $this->helper->getOrderPadding();
            $pattern = "%0".$padding."d";
            if ($this->helper->isIndividualOrderEnable())
            {
                $table = 'sequence_order_'.$storeId;
            } else {
                $table = 'sequence_order_0';
            }

            $lastIncrementId = $this->sequence->lastIncrementId($table);

            if (!isset($lastIncrementId)) {
                return;
            }

            $currentId = ($lastIncrementId - $startValue)*$step + $startValue;
            $counter = sprintf($pattern, $currentId);
        
            $resutl = $this->helper->replace($format, $storeId, $counter);

            $orderInstance = $observer->getOrder();
            $orderInstance->setIncrementId($resutl); 
        }           
    }
}
