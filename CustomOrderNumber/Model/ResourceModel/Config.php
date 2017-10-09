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

/**
 * Class Sequence represents sequence in logic
 */
class Config extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
     /**
     * @const ALPHA_NUMERIC
     */
    const ALPHA_NUMERIC = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * AppResource
     *
     * @var \Magento\Framework\Model\ResourceModel\Db\Context AppResource
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
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string $connectionName
     */
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\SalesSequence\Model\ResourceModel\Meta $meta,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        $this->helper = $helper;
        $this->datetime = $datetime;
        $this->meta = $meta;
        $this->connection = $context->getResources()->getConnection();
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
     * @param string $table
     * @param int $startValue
     * @param int $step
     * @param string $pattern
     * @return int
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
