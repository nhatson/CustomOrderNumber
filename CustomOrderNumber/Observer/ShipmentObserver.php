<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection as AppResource;

class ShipmentObserver implements ObserverInterface
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

        if($this->helper->isShipmentEnable())
        {
            if($this->helper->isShipmentSameOrder() && (!$this->helper->isOrderEnable()))
            {
                return;
            }

            $storeId = '1';

            if($this->helper->isShipmentSameOrder() && $this->helper->isOrderEnable())
            {
                $format = $this->helper->getOrderFormat();
                $replace = $this->helper->getShipmentReplace();
                $replaceWith = $this->helper->getShipmentReplaceWith();
                $format = str_replace($replace, $replaceWith, $format);

                $startValue = $this->helper->getOrderStart();
                $step = $this->helper->getOrderIncrement();

                $padding = $this->helper->getOrderPadding();

            } 

            if(!$this->helper->isShipmentSameOrder()) 
            {
                $format = $this->helper->getShipmentFormat();

                $startValue = $this->helper->getShipmentStart();
                $step = $this->helper->getShipmentIncrement();

                $padding = $this->helper->getShipmentPadding();
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

            $table = 'sequence_shipment_'.$storeId;
            // $this->connection->insert($table,[]);
            $sql = "SELECT * FROM ".$table." ORDER BY sequence_value DESC LIMIT 1";
            $result1 = $this->connection->fetchAll($sql);
            $lastId = $result1['0']['sequence_value'];
            
            // $this->connection->truncateTable($table);

            $currentId = ($lastId - $startValue)*$step + $startValue;
        
            $resutl = sprintf(
                $pattern,
                $prefix,
                $currentId,
                $suffix
            );
            $shipmentInstance = $observer->getShipment();
            $shipmentInstance->setData("increment_id",$resutl)->save();
        }           
    }
}
