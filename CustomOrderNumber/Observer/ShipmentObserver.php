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
    protected $connection;

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
            $shipmentInstance = $observer->getShipment();

            if($this->helper->isShipmentSameOrder())
            {
                $orderIncrement = $shipmentInstance->getOrder()->getIncrementId();

                $replace = $this->helper->getShipmentReplace();
                $replaceWith = $this->helper->getShipmentReplaceWith();
                $resutl = str_replace($replace, $replaceWith, $orderIncrement);

            } 

            if(!$this->helper->isShipmentSameOrder()) 
            {
                $format = $this->helper->getShipmentFormat();

                $startValue = $this->helper->getShipmentStart();
                $step = $this->helper->getShipmentIncrement();

                $padding = $this->helper->getShipmentPadding();

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
            }

            $shipmentInstance->setIncrementId($resutl); 
        }           
    }
}
