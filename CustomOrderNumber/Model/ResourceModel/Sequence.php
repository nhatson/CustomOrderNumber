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
        $this->connection->insert($table,[]);
        $lastIncrementId = $this->connection->lastInsertId($table);
        $currentId = ($lastIncrementId - $startValue)*$step + $startValue;
        $counter = sprintf($pattern, $currentId);
        return $counter;
    }

    /**
     * Set Cron
     *
     * @param int $storeId
     * @param int $frequency
     * @return $this
     */
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

        if(isset($timezone)){
            date_default_timezone_set($timezone); 
        }

        $date = $this->datetime->gmtDate();

        $dd = date('d', strtotime($date));
        $d = (int)$dd;
        $mm = date('m', strtotime($date));
        $m = (int)$mm;
        $yy = date('y', strtotime($date));
        $yyyy = date('Y', strtotime($date));
        $rndNumbers = $this->get_rand_numbers($length);
        $rndLetters = $this->get_rand_numbers($length);
        $rndAlphanumeric = $this->get_rand_alphanumeric($length);

        $search     = ['{d}','{dd}','{m}','{mm}','{yy}','{yyyy}','{storeId}','{counter}',
            '{rndNumbers}', '{rndLetters}', '{rndAlphanumeric}'];
        $replace    = [$d, $dd, $m, $mm, $yy, $yyyy, $storeId, $counter, $rndNumbers, $rndLetters, $rndAlphanumeric ];

        $result = str_replace($search, $replace, $format);

        return $result;
    }

    /**
     * Retrieve value
     *
     * @param int $num
     * @return randValue
     */
    public function assign_rand_value ($num)
    {
        switch($num) {
            case "1"  :
                $randValue = "a";
                break;
            case "2"  :
                $randValue = "b";
                break;
            case "3"  :
                $randValue = "c";
                break;
            case "4"  :
                $randValue = "d";
                break;
            case "5"  : $randValue = "e"; break;
            case "6"  : $randValue = "f"; break;
            case "7"  : $randValue = "g"; break;
            case "8"  : $randValue = "h"; break;
            case "9"  : $randValue = "i"; break;
            case "10" : $randValue = "j"; break;
            case "11" : $randValue = "k"; break;
            case "12" : $randValue = "l"; break;
            case "13" : $randValue = "m"; break;
            case "14" : $randValue = "n"; break;
            case "15" : $randValue = "o"; break;
            case "16" : $randValue = "p"; break;
            case "17" : $randValue = "q"; break;
            case "18" : $randValue = "r"; break;
            case "19" : $randValue = "s"; break;
            case "20" : $randValue = "t"; break;
            case "21" : $randValue = "u"; break;
            case "22" : $randValue = "v"; break;
            case "23" : $randValue = "w"; break;
            case "24" : $randValue = "x"; break;
            case "25" : $randValue = "y"; break;
            case "26" : $randValue = "z"; break;
            case "27" : $randValue = "0"; break;
            case "28" : $randValue = "1"; break;
            case "29" : $randValue = "2"; break;
            case "30" : $randValue = "3"; break;
            case "31" : $randValue = "4"; break;
            case "32" : $randValue = "5"; break;
            case "33" : $randValue = "6"; break;
            case "34" : $randValue = "7"; break;
            case "35" : $randValue = "8"; break;
            case "36" : $randValue = "9"; break;
        }
        return $randValue;
    }

    /**
     * Retrieve value
     *
     * @param int $length
     * @return rand Value
     */
    public function get_rand_alphanumeric($length)
    {
        if ($length>0) {
            $rand_id="";
            for ($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1, 36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }

    /**
     * Retrieve value
     *
     * @param int $length
     * @return rand Value
     */
    public function get_rand_numbers($length)
    {
        if ($length>0) {
            $rand_id="";
            for ($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(27, 36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }
    /**
     * Retrieve value
     *
     * @param int $length
     * @return rand Value
     */
    public function get_rand_letters($length)
    {
        if ($length>0) {
            $rand_id="";
            for($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1, 26);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }
}
