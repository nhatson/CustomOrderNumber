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
    protected $datetime;
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Framework\DB\Ddl\TriggerFactory $triggerFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        AppResource $resource
    ) {
        $this->helper = $helper;
        $this->datetime = $datetime;
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
    public function counter($table, $startValue, $step, $pattern)
    {
        $this->connection->insert($table,[]);
        $lastIncrementId = $this->connection->lastInsertId($table);
        $currentId = ($lastIncrementId - $startValue)*$step + $startValue;
        $counter = sprintf($pattern, $currentId);
        return $counter;
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

        $search     = ['{d}','{dd}','{m}','{mm}','{yy}','{yyyy}','{storeId}','{counter}', '{rndNumbers}', '{rndLetters}', '{rndAlphanumeric}']; 
        $replace    = [$d, $dd, $m, $mm, $yy, $yyyy, $storeId, $counter, $rndNumbers, $rndLetters, $rndAlphanumeric ];

        $result = str_replace($search, $replace, $format);

        return $result;
    }

    function assign_rand_value($num) {
        switch($num) {
            case "1"  : $rand_value = "a"; break;
            case "2"  : $rand_value = "b"; break;
            case "3"  : $rand_value = "c"; break;
            case "4"  : $rand_value = "d"; break;
            case "5"  : $rand_value = "e"; break;
            case "6"  : $rand_value = "f"; break;
            case "7"  : $rand_value = "g"; break;
            case "8"  : $rand_value = "h"; break;
            case "9"  : $rand_value = "i"; break;
            case "10" : $rand_value = "j"; break;
            case "11" : $rand_value = "k"; break;
            case "12" : $rand_value = "l"; break;
            case "13" : $rand_value = "m"; break;
            case "14" : $rand_value = "n"; break;
            case "15" : $rand_value = "o"; break;
            case "16" : $rand_value = "p"; break;
            case "17" : $rand_value = "q"; break;
            case "18" : $rand_value = "r"; break;
            case "19" : $rand_value = "s"; break;
            case "20" : $rand_value = "t"; break;
            case "21" : $rand_value = "u"; break;
            case "22" : $rand_value = "v"; break;
            case "23" : $rand_value = "w"; break;
            case "24" : $rand_value = "x"; break;
            case "25" : $rand_value = "y"; break;
            case "26" : $rand_value = "z"; break;
            case "27" : $rand_value = "0"; break;
            case "28" : $rand_value = "1"; break;
            case "29" : $rand_value = "2"; break;
            case "30" : $rand_value = "3"; break;
            case "31" : $rand_value = "4"; break;
            case "32" : $rand_value = "5"; break;
            case "33" : $rand_value = "6"; break;
            case "34" : $rand_value = "7"; break;
            case "35" : $rand_value = "8"; break;
            case "36" : $rand_value = "9"; break;
        }
        return $rand_value;
    }

    function get_rand_alphanumeric($length) {
        if ($length>0) {
            $rand_id="";
            for ($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }

    function get_rand_numbers($length) {
        if ($length>0) {
            $rand_id="";
            for($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(27,36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }

    function get_rand_letters($length) {
        if ($length>0) {
            $rand_id="";
            for($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,26);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }
}
