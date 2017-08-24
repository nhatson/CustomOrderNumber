<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ShipmentObserver implements ObserverInterface
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
        $shipmentInstance = $observer->getShipment();
        $storeId = $invoiceInstance->getOrder()->getStoreId();
        if($this->helper->isShipmentEnable($storeId))
        {
            if($this->helper->isShipmentSameOrder($storeId) && (!$this->helper->isOrderEnable($storeId)))
            {
                return;
            }
            if($this->helper->isShipmentSameOrder($storeId))
            {
                $orderIncrement = $shipmentInstance->getOrder()->getIncrementId();

                $replace = $this->helper->getShipmentReplace($storeId);
                $replaceWith = $this->helper->getShipmentReplaceWith($storeId);
                $resutl = str_replace($replace, $replaceWith, $orderIncrement);

            } else {
                $format = $this->helper->getShipmentFormat($storeId);

                $startValue = $this->helper->getShipmentStart($storeId);
                $step = $this->helper->getShipmentIncrement($storeId);

                $padding = $this->helper->getShipmentPadding($storeId);            
                $pattern = "%0".$padding."d";
                if ($this->helper->isIndividualShipmentEnable($storeId))
                {
                    $table = 'sequence_shipment_'.$storeId;
                } else {
                    $table = 'sequence_shipment_0';
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

            $shipmentInstance->setIncrementId($resutl); 
        }           
    }
}
