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
    
    public function setCron($storeId, $frequency)
    {
        if ($this->helper->isOrderEnable($storeId)) {
            if ($this->helper->getOrderReset($storeId) == $frequency) {
                $this->connection->truncateTable('sequence_order_'.$storeId);  
            }        
        }
        if ($this->helper->isInvoiceEnable($storeId) && (!$this->helper->isInvoiceSameOrder($storeId))) {
            if ($this->helper->getInvoiceReset($storeId) == $frequency) {
                $this->connection->truncateTable('sequence_invoice_'.$storeId);
            }      
        }
        if ($this->helper->isShipmentEnable($storeId) && (!$this->helper->isShipmentSameOrder($storeId))) {
            if ($this->helper->getShipmentReset($storeId) == $frequency) {
                $this->connection->truncateTable('sequence_shipment_'.$storeId);
            }      
        }
        if ($this->helper->isCreditmemoEnable($storeId) && (!$this->helper->isCreditmemoSameOrder($storeId))) {
            if ($this->helper->getCreditmemoReset($storeId) == $frequency) {
                $this->connection->truncateTable('sequence_creditmemo_'.$storeId);  
            } 
        } 
    }
}
