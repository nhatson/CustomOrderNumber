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
namespace Bss\CustomOrderNumber\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    protected $datetime; 

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime
    ) {
        $this->datetime = $datetime;
        parent::__construct($context);
    }

    /**
     * Retrieve Module Enable
     *
     * @return bool
     */
    public function replace($format, $storeId)
    {
        $timezone = $this->scopeConfig->getValue(
            'general/locale/timezone',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

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

        $search     = array('{d}','{dd}','{m}','{mm}','{yy}','{yyyy}','{storeId}'); 
        $replace    = array($d, $dd, $m, $mm, $yy, $yyyy, $storeId);

        $format = str_replace($search, $replace, $format);

        return $format;
    }

    public function isOrderEnable()
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/order/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Auto Hide Message
     *
     * @return int
     */
    public function getOrderFormat()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/order/format',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getOrderStart()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/order/start',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getOrderIncrement()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/order/increment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getOrderPadding()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/order/padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getOrderReset()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/order/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isInvoiceEnable()
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/invoice/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function isInvoiceSameOrder()
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/invoice/same_order',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Auto Hide Message
     *
     * @return int
     */
    public function getInvoiceFormat()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/format',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getInvoiceStart()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/start',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getInvoiceIncrement()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/increment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getInvoicePadding()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getInvoiceReset()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getInvoiceReplace()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/replace',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getInvoiceReplaceWith()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/replace_with',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isShipmentEnable()
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/shipment/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function isShipmentSameOrder()
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/shipment/same_order',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Auto Hide Message
     *
     * @return int
     */
    public function getShipmentFormat()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/format',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getShipmentStart()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/start',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getShipmentIncrement()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/increment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getShipmentPadding()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getShipmentReset()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getShipmentReplace()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/replace',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getShipmentReplaceWith()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/replace_with',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isCreditmemoEnable()
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/credit_memo/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function isCreditmemoSameOrder()
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/credit_memo/same_order',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Auto Hide Message
     *
     * @return int
     */
    public function getCreditmemoFormat()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/credit_memo/format',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getCreditmemoStart()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/credit_memo/start',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getCreditmemoIncrement()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/credit_memo/increment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getCreditmemoPadding()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/credit_memo/padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getCreditmemoReset()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/credit_memo/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getCreditmemoReplace()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/credit_memo/replace',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getCreditmemoReplaceWith()
    {
        return $this->scopeConfig->getValue(
            'ordernumber/credit_memo/replace_with',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
