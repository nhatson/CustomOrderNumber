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

class CreditmemoObserver implements ObserverInterface
{
    /**
     * @var \Bss\CustomOrderNumber\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Sales\Api\Data\CreditmemoInterface
     */
    protected $creditmemo;

    /**
     * @var \Bss\CustomOrderNumber\Model\ResourceModel\Sequence
     */
    protected $sequence;

    /**
     * @param \Bss\CustomOrderNumber\Helper\Data $helper
     * @param \Magento\Sales\Api\Data\CreditmemoInterface $creditmemo
     * @param \Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
     */
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Sales\Api\Data\CreditmemoInterface $creditmemo,
        \Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
    ) {
            $this->helper = $helper;
            $this->creditmemo = $creditmemo;
            $this->sequence = $sequence;
    }

    /**
     * Set Increment Id
     *
     * @param Observer $observer
     * @return incrementId
     */
    public function execute(Observer $observer)
    {   
        $creditmemoInstance = $observer->getCreditmemo();
        $storeId = $creditmemoInstance->getOrder()->getStoreId();
        if ($this->helper->isCreditmemoEnable($storeId)) {
            if ($this->helper->isCreditmemoSameOrder($storeId) && (!$this->helper->isOrderEnable($storeId))) {
                return;
            }
            
            if ($this->helper->isCreditmemoSameOrder($storeId)) {
                $orderIncrement = $creditmemoInstance->getOrder()->getIncrementId();
                $replace = $this->helper->getCreditmemoReplace($storeId);
                $replaceWith = $this->helper->getCreditmemoReplaceWith($storeId);
                $result = str_replace($replace, $replaceWith, $orderIncrement);
            } else {
                $format = $this->helper->getCreditmemoFormat($storeId);
                $startValue = $this->helper->getCreditmemoStart($storeId);
                $step = $this->helper->getCreditmemoIncrement($storeId);
                $padding = $this->helper->getCreditmemoPadding($storeId);            
                $pattern = "%0".$padding."d";

                if ($this->helper->isIndividualCreditmemoEnable($storeId)) {
                    if ($storeId == 1) {
                        $table = 'sequence_creditmemo_0';
                    } else {
                        $table = 'sequence_creditmemo_'.$storeId;                        
                    }
                } else {
                    $table = 'sequence_creditmemo_0';
                }

                $counter = $this->sequence->counter($table, $startValue, $step, $pattern);
                $result = $this->sequence->replace($format, $storeId, $counter, $padding);
            }

            if ($this->creditmemo->loadByIncrementId($result)->getId() !== null) {
                $tableExtra = 'sequence_creditmemo_1';
                $extra = $this->sequence->extra($tableExtra);
                $result = $result.$extra;
            }

            $creditmemoInstance->setIncrementId($result);
        }           
    }
}
