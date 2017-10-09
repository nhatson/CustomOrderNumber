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

class Cron extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * ResourceConnection
     *
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $resourceConnection;

    /**
     * Helper
     *
     * @var \Bss\CustomOrderNumber\Helper\Data
     */
    protected $helper;

    /**
     * Meta
     *
     * @var \Magento\SalesSequence\Model\ResourceModel\Meta
     */
    protected $meta;

    /**
     * Construct
     *
     * @param \Bss\CustomOrderNumber\Helper\Data $helper
     * @param \Magento\SalesSequence\Model\ResourceModel\Meta $meta
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string $connectionName
     */
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\SalesSequence\Model\ResourceModel\Meta $meta,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        $this->helper = $helper;
        $this->meta = $meta;
        $this->resourceConnection = $context->getResources();
        parent::__construct($context, $connectionName);
    }

    /**
     * Abstract Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sales_sequence_meta', 'meta_id');
    }

    /**
     * Set CronOrder
     *
     * @param int $storeId
     * @param int $frequency
     * @return void
     */
    public function setCronOrder($storeId, $frequency)
    {
        $entityType = 'order';
        if ($this->helper->isOrderEnable($storeId)) {
            if ($this->helper->getOrderReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $storeId = 0;
                }
                $meta = $this->meta->loadByEntityTypeAndStore($entityType, $storeId);
                $sequenceTable = $meta->getSequenceTable();
                $this->resourceConnection->getConnection()->truncateTable($sequenceTable);
            }        
        }
    }

    /**
     * Set CronInvoice
     *
     * @param int $storeId
     * @param int $frequency
     * @return void
     */
    public function setCronInvoice($storeId, $frequency)
    {
        $entityType = 'invoice';
        if ($this->helper->isInvoiceEnable($storeId) && (!$this->helper->isInvoiceSameOrder($storeId))) {
            if ($this->helper->getInvoiceReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $storeId = 0;
                }
                $meta = $this->meta->loadByEntityTypeAndStore($entityType, $storeId);
                $sequenceTable = $meta->getSequenceTable();
                $this->resourceConnection->getConnection()->truncateTable($sequenceTable);
            }      
        }
    }

    /**
     * Set CronShipment
     *
     * @param int $storeId
     * @param int $frequency
     * @return void
     */
    public function setCronShipment($storeId, $frequency)
    {
        $entityType = 'shipment';
        if ($this->helper->isShipmentEnable($storeId) && (!$this->helper->isShipmentSameOrder($storeId))) {
            if ($this->helper->getShipmentReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $storeId = 0;
                }
                $meta = $this->meta->loadByEntityTypeAndStore($entityType, $storeId);
                $sequenceTable = $meta->getSequenceTable();
                $this->resourceConnection->getConnection()->truncateTable($sequenceTable);
            }      
        }
    }

    /**
     * Set CronCreditmemo
     *
     * @param int $storeId
     * @param int $frequency
     * @return void
     */
    public function setCronCreditmemo($storeId, $frequency)
    {
        $entityType = 'creditmemo';
        if ($this->helper->isShipmentEnable($storeId) && (!$this->helper->isShipmentSameOrder($storeId))) {
            if ($this->helper->getShipmentReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $storeId = 0;
                }
                $meta = $this->meta->loadByEntityTypeAndStore($entityType, $storeId);
                $sequenceTable = $meta->getSequenceTable();
                $this->resourceConnection->getConnection()->truncateTable($sequenceTable);
            }
        }
    }

    /**
     * Set Cron
     *
     * @param int $storeId
     * @param int $frequency
     * @return void
     */
    public function setCron($storeId, $frequency)
    {
        $this->setCronOrder($storeId, $frequency);
        $this->setCronInvoice($storeId, $frequency);
        $this->setCronShipment($storeId, $frequency);
        $this->setCronCreditmemo($storeId, $frequency);
    }
}
