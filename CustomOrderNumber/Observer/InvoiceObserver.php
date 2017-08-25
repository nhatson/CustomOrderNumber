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

class InvoiceObserver implements ObserverInterface
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
        $invoiceInstance = $observer->getInvoice();
        $storeId = $invoiceInstance->getOrder()->getStoreId();
        if($this->helper->isInvoiceEnable($storeId))
        {
            if($this->helper->isInvoiceSameOrder($storeId) && (!$this->helper->isOrderEnable($storeId)))
            {
                return;
            }    
            if($this->helper->isInvoiceSameOrder($storeId))
            {          
                $orderIncrement = $invoiceInstance->getOrder()->getIncrementId();

                $replace = $this->helper->getInvoiceReplace($storeId);
                $replaceWith = $this->helper->getInvoiceReplaceWith($storeId);
                $resutl = str_replace($replace, $replaceWith, $orderIncrement);

            } else {
                $format = $this->helper->getInvoiceFormat($storeId);
                $startValue = $this->helper->getInvoiceStart($storeId);
                $step = $this->helper->getInvoiceIncrement($storeId);

                $padding = $this->helper->getInvoicePadding($storeId);
                $pattern = "%0".$padding."d";
                if ($this->helper->isIndividualInvoiceEnable($storeId))
                {
                    $table = 'sequence_invoice_'.$storeId;
                } else {
                    $table = 'sequence_invoice_0';
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

            $invoiceInstance->setIncrementId($resutl);       
        }           
    }
}
