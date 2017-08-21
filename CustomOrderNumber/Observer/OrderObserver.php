<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection as AppResource;

class OrderObserver implements ObserverInterface
{
    protected $helper;
    protected $connection;
    protected $storeManager;

    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        AppResource $resource
        ) {
            $this->helper = $helper;
            $this->connection = $resource->getConnection('DEFAULT_CONNECTION');
            $this->storeManager = $storeManager;
        }

    public function execute(Observer $observer)
    {   
        if($this->helper->isOrderEnable())
        {
            $storeIdd = $this->storeManager->getStore()->getStoreId();
            $storeId = '1';

            $format = $this->helper->getOrderFormat();

            $startValue = $this->helper->getOrderStart();
            $step = $this->helper->getOrderIncrement();

            $padding = $this->helper->getOrderPadding();

            $format = $this->helper->replace($format, $storeIdd);
            $explode = explode('{counter}', $format);

            $prefix = $explode[0];
            
            if (isset($explode[1])){
                $suffix = $explode[1];   
            } else {
                $suffix = "";
            }

            $pattern = "%s%'.0".$padding."d%s";

            $table = 'sequence_order_'.$storeId;
            // $sql = "SELECT * FROM ".$table." ORDER BY sequence_value DESC LIMIT 1";
            // $lastRow = $this->connection->fetchAll($sql);
            // $lastIncrementId = $lastRow['0']['sequence_value'];
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

            $orderInstance = $observer->getOrder();
            $orderInstance->setIncrementId($resutl); 
        }           
    }
}
