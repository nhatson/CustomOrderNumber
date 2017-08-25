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
