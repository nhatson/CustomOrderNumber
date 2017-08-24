<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bss\CustomOrderNumber\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection as AppResource;
/**
 * Class Meta represents metadata for sequence as sequence table and store id
 */
class Sequence extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $connection;
    protected $helper;
    protected $triggerFactory;
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Framework\DB\Ddl\TriggerFactory $triggerFactory,
        AppResource $resource
    ) {
        $this->helper = $helper;
        $this->triggerFactory = $triggerFactory;
        $this->connection = $resource->getConnection('DEFAULT_CONNECTION');
    }
    protected function _construct()
    {
    }
    /**
     * Retrieves Metadata for entity by entity type and store id
     *
     * @param string $entityType
     * @param int $storeId
     * @return \Magento\SalesSequence\Model\Meta
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function lastIncrementId($table)
    {
        $this->connection->insert($table,[]);
        return $this->connection->lastInsertId($table);
    }
    public function setGlobal()
    {
        $timezone = $this->helper->timezone();
        date_default_timezone_set($timezone);
        $df = "Y-m-d H:i:s";
        $ts1 = strtotime(date($df));
        $ts2 = strtotime(gmdate($df));
        $ts3 = ($ts1-$ts2)/3600; 
        if ($ts3 >=0 ) {
            $sql = "SET time_zone = '+".$ts3.":00';"; 
        } else {
            $sql = "SET time_zone = '".$ts3.":00';"; 
        }
        $this->connection->query($sql);
        $sql= "SET GLOBAL event_scheduler = 0;";
        $this->connection->query($sql);
    }

    public function setEvent($storeId)
    {
        $this->connection->dropTrigger('sequence_order_0');
        die('bss');
        $triggerName = 'insert_user_trigger';
        $event = 'UPDATE';
        $trigger = $this->triggerFactory->create()
            ->setName($triggerName)
            ->setTime(\Magento\Framework\DB\Ddl\Trigger::TIME_AFTER)
            ->setEvent($event)
            ->setTable($setup->getTable('sitealluser'));

        $trigger->addStatement($this->buildStatement($event));

        $this->connection->dropTrigger($trigger->getName());
        $this->connection->createTrigger($trigger);
        die('bss');
        $orderReset = $this->helper->getOrderReset($storeId);
        $invoiceReset = $this->helper->getInvoiceReset($storeId);
        $shipmentReset = $this->helper->getShipmentReset($storeId);
        $creditmemoReset = $this->helper->getShipmentReset($storeId);
        if ($this->helper->isOrderEnable($storeId)) {
            $nameTable['sequence_order_'] = $orderReset;
        } else {
            $sql = "Drop event if exists sequence_order_".$storeId;
            $this->connection->query($sql);
        }
        if ($this->helper->isInvoiceEnable($storeId)) {
            $nameTable['sequence_invoice_'] = $invoiceReset;
        } else {
            $sql = "Drop event if exists sequence_invoice_".$storeId;
            $this->connection->query($sql);
        }
        if ($this->helper->isShipmentEnable($storeId)) {
            $nameTable['sequence_shipment_'] = $shipmentReset;
        } else {
            $sql = "Drop event if exists sequence_shipment_".$storeId;
            $this->connection->query($sql);
        }
        if ($this->helper->isCreditmemoEnable($storeId)) {
            $nameTable['sequence_creditmemo_'] = $orderReset;
        } else {
            $sql = "Drop event if exists sequence_creditmemo_".$storeId;
            $this->connection->query($sql);
        }
        foreach ($nameTable as $key => $value) 
        {
            switch ($value) 
            {
                case '0':
                    $sql = "Drop event if exists ".$key.$storeId;
                    $this->connection->query($sql);
                    break;
                
                case '1':
                    $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                        ON SCHEDULE EVERY 1 DAY
                        STARTS '2017-01-01 00:00:00'
                        DO TRUNCATE ".$key.$storeId.";";
                    $this->connection->query($sql);
                    break;
                case '2':
                    $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                        ON SCHEDULE EVERY 1 MONTH
                        STARTS '2017-01-01 00:00:00'
                        DO TRUNCATE ".$key.$storeId.";";
                    $this->connection->query($sql);
                    break;
                case '3':
                    $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                        ON SCHEDULE EVERY 1 YEAR
                        STARTS '2017-01-01 00:00:00'
                        DO TRUNCATE ".$key.$storeId.";";
                    $this->connection->query($sql);
                    break;
                default:
                    $sql = "Drop event if exists ".$key.$storeId."";
                    $this->connection->query($sql);
                    break;
            }
        }  
    }
}
