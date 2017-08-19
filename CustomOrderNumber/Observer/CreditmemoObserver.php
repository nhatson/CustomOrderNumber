<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection as AppResource;

class CreditmemoObserver implements ObserverInterface
{
    protected $helper;

    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        AppResource $resource
        ) {
            $this->helper = $helper;
            $this->connection = $resource->getConnection('DEFAULT_CONNECTION');
        }

    public function execute(Observer $observer)
    {   

        if($this->helper->isCreditmemoEnable())
        {
            if($this->helper->isCreditmemoSameOrder() && (!$this->helper->isOrderEnable()))
            {
                return;
            }

            $storeId = '1';

            if($this->helper->isCreditmemoSameOrder() && $this->helper->isOrderEnable())
            {
                $format = $this->helper->getOrderFormat();
                $replace = $this->helper->getCreditmemoReplace();
                $replaceWith = $this->helper->getCreditmemoReplaceWith();
                $format = str_replace($replace, $replaceWith, $format);

                $startValue = $this->helper->getOrderStart();
                $step = $this->helper->getOrderIncrement();

                $padding = $this->helper->getOrderPadding();

            } 

            if(!$this->helper->isCreditmemoSameOrder()) 
            {
                $format = $this->helper->getCreditmemoFormat();

                $startValue = $this->helper->getCreditmemoStart();
                $step = $this->helper->getCreditmemoIncrement();

                $padding = $this->helper->getCreditmemoPadding();
            }

            $format = $this->helper->replace($format, $storeId);
            $explode = explode('{counter}', $format);

            $prefix = $explode[0];
            
            if (isset($explode[1])){
                $suffix = $explode[1];   
            } else {
                $suffix = "";
            }

            $pattern = "%s%'.0".$padding."d%s";

            $table = 'sequence_creditmemo_'.$storeId;
            $this->connection->insert($table,[]);

            $lastIncrementId = $this->connection->lastInsertId($table);
            if (!isset($lastIncrementId)) {
                return;
            }
            
            $currentId = ($lastIncrementId - $startValue)*$step + $startValue;
        
            $resutl = sprintf(
                $pattern,
                $prefix,
                $currentId,
                $suffix
            );

            $creditmemoInstance = $observer->getCreditmemo();
            $creditmemoInstance->setData("increment_id", $resutl)->save();
        }           
    }
}
