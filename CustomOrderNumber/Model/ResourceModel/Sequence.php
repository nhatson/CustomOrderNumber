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
 * Class Sequence represents sequence in logic
 */
class Sequence extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
     /**
     * @const ALPHA_NUMERIC
     */
    const ALPHA_NUMERIC = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * AppResource
     *
     * @var AppResource
     */
    protected $connection;

    /**
     * Helper
     *
     * @var \Bss\CustomOrderNumber\Helper\Data
     */
    protected $helper;

    /**
     * DateTime
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $datetime;

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
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     * @param \Magento\SalesSequence\Model\ResourceModel\Meta $meta
     * @param AppResource $resource
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string $connectionName
     */
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\SalesSequence\Model\ResourceModel\Meta $meta,
        AppResource $resource,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        $this->helper = $helper;
        $this->datetime = $datetime;
        $this->meta = $meta;
        $this->connection = $resource->getConnection('DEFAULT_CONNECTION');
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
     * Retrieve Sequence Table
     *
     * @param string $entityType
     * @param int $storeId
     * @return string
     */
    public function getSequenceTable($entityType, $storeId)
    {
        $meta = $this->meta->loadByEntityTypeAndStore($entityType, $storeId);
        $sequenTable = $meta->getSequenceTable();
        return $sequenTable;
    }

    /**
     * Retrieve counter
     *
     * @param string $entityType
     * @param int $storeId
     * @param int $startValue
     * @param int $step
     * @param string $pattern
     * @return int
     */
    public function counter($entityType, $storeId, $startValue, $step, $pattern)
    {
        $table = $this->getSequenceTable($entityType, $storeId);
        $this->connection->insert($table, []);
        $lastIncrementId = $this->connection->lastInsertId($table);
        $currentId = ($lastIncrementId - 1)*$step + $startValue;
        $counter = sprintf($pattern, $currentId);
        return $counter;
    }

    /**
     * Retrieve currentId
     *
     * @param string $format
     * @param int $storeId
     * @param string $counter
     * @param int $length
     * @return string
     */
    public function replace($format, $storeId, $counter, $length)
    {
        $timezone = $this->helper->timezone($storeId);

        if (isset($timezone)) {
            date_default_timezone_set($timezone); 
        }

        $date = $this->datetime->gmtDate();

        $dd = date('d', strtotime($date));
        $d = (int)$dd;
        $mm = date('m', strtotime($date));
        $m = (int)$mm;
        $yy = date('y', strtotime($date));
        $yyyy = date('Y', strtotime($date));
        $rndNumbers = $this->rndNumbers($length);
        $rndLetters = $this->rndLetters($length);
        $rndAlphanumeric = $this->rndAlphanumeric($length);

        $search     = ['{d}','{dd}','{m}','{mm}','{yy}','{yyyy}','{storeId}','{storeid}','{storeID}','{counter}',
            '{rndNumbers}', '{rndnumbers}', '{rndLetters}', '{rndletters}', '{rndAlphanumeric}', '{rndalphanumeric}'];
        $replace    = [$d, $dd, $m, $mm, $yy, $yyyy, $storeId, $storeId, $storeId, $counter, $rndNumbers, 
            $rndNumbers, $rndLetters, $rndLetters, $rndAlphanumeric, $rndAlphanumeric];

        $result = str_replace($search, $replace, $format);

        return $result;
    }

    /**
     * Get Extra
     *
     * @param string $entityType
     * @param int $storeId
     * @return int
     */
    public function extra($entityType, $storeId)
    {
        $table = $this->getSequenceTable($entityType, $storeId);
        $this->connection->insert($table, []);
        $extra = '-'.$this->connection->lastInsertId($table);
        return $extra;
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
                $table = $this->getSequenceTable($entityType, $storeId);
                $this->connection->truncateTable($table);
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
                $table = $this->getSequenceTable($entityType, $storeId);
                $this->connection->truncateTable($table);
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
                $table = $this->getSequenceTable($entityType, $storeId);
                $this->connection->truncateTable($table);
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
                $table = $this->getSequenceTable($entityType, $storeId);
                $this->connection->truncateTable($table);
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

    /**
     * Retrieve numbers
     *
     * @param int $length
     * @return int
     */
    public function rndNumbers($length)
    {
        $numbers ='';
        $i=0;
        while ($i<$length) {
            $position = rand(0, 9);
            $numbers=$numbers.substr(self::ALPHA_NUMERIC, $position, 1);
            $i++;
        }
        return $numbers;
    }

    /**
     * Retrieve letters
     *
     * @param int $length
     * @return string
     */
    public function rndLetters($length)
    {
        $letters ='';
        $i=0;
        while ($i<$length) {
            $position = rand(10, 35);
            $letters=$letters.substr(self::ALPHA_NUMERIC, $position, 1);
            $i++;
        }
        return $letters;
    }

    /**
     * Retrieve alphanumeric
     *
     * @param int $length
     * @return string
     */
    public function rndAlphanumeric($length)
    {
        $alphanumeric ='';
        $i=0;
        while ($i<$length) {
            $position = rand(0, 35);
            $alphanumeric=$alphanumeric.substr(self::ALPHA_NUMERIC, $position, 1);
            $i++;
        }
        return $alphanumeric;
    }
}
