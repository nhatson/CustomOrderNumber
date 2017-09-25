<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * =================================================================
 *
 * MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * BSS Commerce does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BSS Commerce does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   BSS
 * @package    Bss_CustomOrderNumber
 * @author     Extension Team
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ShipmentObserver implements ObserverInterface
{
    /**
     * Helper
     *
     * @var \Bss\CustomOrderNumber\Helper\Data
     */
    protected $helper;

    /**
     * Shipment Interface
     *
     * @var \Magento\Sales\Api\Data\ShipmentInterface
     */   
    protected $shipment;

    /**
     * Sequence
     *
     * @var \Bss\CustomOrderNumber\Model\ResourceModel\Sequence
     */
    protected $sequence;

    /**
     * Construct
     *
     * @param \Bss\CustomOrderNumber\Helper\Data $helper
     * @param \Magento\Sales\Api\Data\ShipmentInterface $shipment
     * @param \Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
     */
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Sales\Api\Data\ShipmentInterface $shipment,
        \Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
    ) {
            $this->helper = $helper;
            $this->shipment = $shipment;
            $this->sequence = $sequence;
    }

    /**
     * Set Increment Id
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {   
        $shipmentInstance = $observer->getShipment();
        $storeId = $shipmentInstance->getOrder()->getStoreId();
        if ($this->helper->isShipmentEnable($storeId)) {
            if ($this->helper->isShipmentSameOrder($storeId)) {
                $orderIncrement = $shipmentInstance->getOrder()->getIncrementId();
                $replace = $this->helper->getShipmentReplace($storeId);
                $replaceWith = $this->helper->getShipmentReplaceWith($storeId);
                $result = str_replace($replace, $replaceWith, $orderIncrement);
            } else {
                $format = $this->helper->getShipmentFormat($storeId);
                $startValue = $this->helper->getShipmentStart($storeId);
                $step = $this->helper->getShipmentIncrement($storeId);
                $padding = $this->helper->getShipmentPadding($storeId);            
                $pattern = "%0".$padding."d";

                if ($this->helper->isIndividualShipmentEnable($storeId)) {
                    if ($storeId == 1) {
                        $table = 'sequence_shipment_0';                        
                    } else {
                        $table = 'sequence_shipment_'.$storeId;
                    }
                } else {
                    $table = 'sequence_shipment_0'; 
                }

                $counter = $this->sequence->counter($table, $startValue, $step, $pattern);
                $result = $this->sequence->replace($format, $storeId, $counter, $padding);
            }
            try {
                if ($this->shipment->loadByIncrementId($result)->getId() !== null) {
                    $tableExtra = 'sequence_shipment_1';
                    $extra = $this->sequence->extra($tableExtra);
                    $result = $result.$extra;
                }
            } catch (\Exception $e) {
            }

            $shipmentInstance->setIncrementId($result);
        }           
    }
}
