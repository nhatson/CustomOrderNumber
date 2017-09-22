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
     /**
     * @const ALPHA_NUMERIC
     */
    const ALPHA_NUMERIC = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var AppResource
     */
    protected $connection;

    /**
     * @var \Bss\CustomOrderNumber\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $datetime;

    /**
     * @param \Bss\CustomOrderNumber\Helper\Data $helper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     * @param AppResource $resource
     */
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        AppResource $resource
    ) {
        $this->helper = $helper;
        $this->datetime = $datetime;
        $this->connection = $resource->getConnection('DEFAULT_CONNECTION');
    }

    /**
     *
     */
    protected function _construct()
    {
    }

    /**
     * Retrieve counter
     *
     * @param string $table
     * @param int $startValue
     * @param int $step
     * @param string $pattern
     * @return counter
     */
    public function counter($table, $startValue, $step, $pattern)
    {
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
     * @return currentId
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
     * @param string $table
     * @return $extra
     */
    public function extra($table)
    {
        $this->connection->insert($table, []);
        $extra = '-'.$this->connection->lastInsertId($table);
        return $extra;
    }

    /**
     * Set CronOrder
     *
     * @param int $storeId
     * @param int $frequency
     * @return $this
     */
    public function setCronOrder($storeId, $frequency)
    {
        if ($this->helper->isOrderEnable($storeId)) {
            if ($this->helper->getOrderReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $this->connection->truncateTable('sequence_order_0');  
                } else {
                    $this->connection->truncateTable('sequence_order_'.$storeId);  
                }
            }        
        }
    }

    /**
     * Set CronInvoice
     *
     * @param int $storeId
     * @param int $frequency
     * @return $this
     */
    public function setCronInvoice($storeId, $frequency)
    {
        if ($this->helper->isInvoiceEnable($storeId) && (!$this->helper->isInvoiceSameOrder($storeId))) {
            if ($this->helper->getInvoiceReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $this->connection->truncateTable('sequence_invoice_0');  
                } else {
                    $this->connection->truncateTable('sequence_invoice_'.$storeId);  
                }
            }      
        }
    }

    /**
     * Set CronShipment
     *
     * @param int $storeId
     * @param int $frequency
     * @return $this
     */
    public function setCronShipment($storeId, $frequency)
    {
        if ($this->helper->isShipmentEnable($storeId) && (!$this->helper->isShipmentSameOrder($storeId))) {
            if ($this->helper->getShipmentReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $this->connection->truncateTable('sequence_shipment_0');
                } else {
                    $this->connection->truncateTable('sequence_shipment_'.$storeId);                   
                }
            }      
        }
    }

    /**
     * Set CronCreditmemo
     *
     * @param int $storeId
     * @param int $frequency
     * @return $this
     */
    public function setCronCreditmemo($storeId, $frequency)
    {
        if ($this->helper->isCreditmemoEnable($storeId) && (!$this->helper->isCreditmemoSameOrder($storeId))) {
            if ($this->helper->getCreditmemoReset($storeId) == $frequency) {
                if ($storeId == 1) {
                    $this->connection->truncateTable('sequence_creditmemo_0');
                } else {
                    $this->connection->truncateTable('sequence_creditmemo_'.$storeId);                     
                }
            } 
        } 
    }

    /**
     * Retrieve numbers
     *
     * @param int $length
     * @return numbers
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
     * @return letters
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
     * @return alphanumeric
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
