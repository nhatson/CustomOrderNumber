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

class ResetShipment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * ResourceConnection
     *
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $resourceConnection;

    /**
     * Meta
     *
     * @var \Magento\SalesSequence\Model\ResourceModel\Meta
     */
    protected $meta;

    /**
     * Construct
     *
     * @param \Magento\SalesSequence\Model\ResourceModel\Meta $meta
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string $connectionName
     */
    public function __construct(
        \Magento\SalesSequence\Model\ResourceModel\Meta $meta,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
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
     * Reset Shipment
     *
     * @param int $storeId
     * @return void
     */
    public function resetShipment($storeId)
    {
        $entityType = 'shipment';
        $meta = $this->meta->loadByEntityTypeAndStore($entityType, $storeId);
        $sequenceTable = $meta->getSequenceTable();
        $this->resourceConnection->getConnection()->truncateTable($sequenceTable);
    }
}
