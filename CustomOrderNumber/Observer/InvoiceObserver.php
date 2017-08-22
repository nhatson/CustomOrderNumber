<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection as AppResource;

class InvoiceObserver implements ObserverInterface
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
        if($this->helper->isInvoiceEnable())
        {
            if($this->helper->isInvoiceSameOrder() && (!$this->helper->isOrderEnable()))
            {
                return;
            }

            $invoiceInstance = $observer->getInvoice();
            
            if($this->helper->isInvoiceSameOrder())
            {          
                $orderIncrement = $invoiceInstance->getOrder()->getIncrementId();

                $replace = $this->helper->getInvoiceReplace();
                $replaceWith = $this->helper->getInvoiceReplaceWith();
                $resutl = str_replace($replace, $replaceWith, $orderIncrement);

            } else {
                $storeId = $invoiceInstance->getOrder()->getStoreId();

                $format = $this->helper->getInvoiceFormat();

                $startValue = $this->helper->getInvoiceStart();
                $step = $this->helper->getInvoiceIncrement();

                $padding = $this->helper->getInvoicePadding();
                $format = $this->helper->replace($format, $storeId);
                $explode = explode('{counter}', $format);

                $prefix = $explode[0];

                if (isset($explode[1])){
                    $suffix = $explode[1];   
                } else {
                    $suffix = "";
                }

                $pattern = "%s%'.0".$padding."d%s";
                if ($this->helper->isIndividualInvoiceEnable())
                {
                    $table = 'sequence_invoice_'.$storeId;
                } else {
                    $table = 'sequence_invoice_0';
                }

                $this->connection->insert($table,[]);
                $lastIncrementId = $this->connection->lastInsertId($table);

                if (!isset($lastIncrementId)) 
                {
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

            $invoiceInstance->setIncrementId($resutl);       
        }           
    }
}
