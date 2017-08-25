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

            $format = $this->helper->getOrderFormat($storeId);
            $startValue = $this->helper->getOrderStart($storeId);
            $step = $this->helper->getOrderIncrement($storeId);
            $padding = $this->helper->getOrderPadding($storeId);
            $pattern = "%0".$padding."d";

            if ($this->helper->isIndividualOrderEnable($storeId))
            {
                $table = 'sequence_order_'.$storeId;
            } else {
                $table = 'sequence_order_0';
            }

            $counter = $this->sequence->counter($table, $startValue, $step, $pattern);
            $resutl = $this->sequence->replace($format, $storeId, $counter, $padding);

            $orderInstance = $observer->getOrder();
            $orderInstance->setIncrementId($resutl); 
        }           
    }
}
