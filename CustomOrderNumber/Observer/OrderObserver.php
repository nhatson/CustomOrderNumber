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

    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        AppResource $resource
        ) {
            $this->helper = $helper;
            $this->connection = $resource->getConnection('DEFAULT_CONNECTION');
        }

    public function execute(Observer $observer)
    {   

        if($this->helper->isOrderEnable())
        {
            $storeId = '1';

            $format = $this->helper->getOrderFormat();

            $startValue = $this->helper->getOrderStart();
            $step = $this->helper->getOrderIncrement();

            $padding = $this->helper->getOrderPadding();

            $format = $this->helper->replace($format, $storeId);
            $explode = explode('{counter}', $format);

            $prefix = $explode[0];
            
            if (isset($explode[1])){
                $suffix = $explode[1];   
            } else {
                $suffix = "";
            }

            $pattern = "%s%'.0".$padding."d%s";

            $table = 'sequence_order_'.$storeId;
            $this->connection->insert($table,[]);
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

            $orderInstance = $observer->getOrder();
            $orderInstance->setData("increment_id", $resutl)->save();
        }           
    }
}
