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

    public function timezone()
    {
        return $this->scopeConfig->getValue(
            'general/locale/timezone',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function replace($format, $storeId, $counter)
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
        $random = rand();
        $search     = ['{d}','{dd}','{m}','{mm}','{yy}','{yyyy}','{storeId}','{counter}', '{random}']; 
        $replace    = [$d, $dd, $m, $mm, $yy, $yyyy, $storeId, $counter, $random];

        $result = str_replace($search, $replace, $format);

        return $result;
    }

    public function isOrderEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/order/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
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
    public function getOrderReset($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/order/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId 
        );
    }

    public function isIndividualOrderEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/order/individual',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function isInvoiceEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/invoice/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function isInvoiceSameOrder($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/invoice/same_order',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * Get Auto Hide Message
     *
     * @return int
     */
    public function getInvoiceFormat($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/format',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getInvoiceStart($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/start',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getInvoiceIncrement($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/increment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getInvoicePadding($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function isIndividualInvoiceEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/invoice/individual',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getInvoiceReset($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getInvoiceReplace($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/replace',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getInvoiceReplaceWith($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/invoice/replace_with',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function isShipmentEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/shipment/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function isShipmentSameOrder($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/shipment/same_order',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * Get Auto Hide Message
     *
     * @return int
     */
    public function getShipmentFormat($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/format',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getShipmentStart($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/start',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getShipmentIncrement($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/increment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getShipmentPadding($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function isIndividualShipmentEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/shipment/individual',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getShipmentReset($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getShipmentReplace($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/replace',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getShipmentReplaceWith($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/shipment/replace_with',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function isCreditmemoEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/creditmemo/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function isCreditmemoSameOrder($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/creditmemo/same_order',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * Get Auto Hide Message
     *
     * @return int
     */
    public function getCreditmemoFormat($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/creditmemo/format',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getCreditmemoStart($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/creditmemo/start',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getCreditmemoIncrement($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/creditmemo/increment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getCreditmemoPadding($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/creditmemo/padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function isIndividualCreditmemoEnable($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            'ordernumber/creditmemo/individual',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getCreditmemoReset($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/creditmemo/reset',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getCreditmemoReplace($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/creditmemo/replace',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
    public function getCreditmemoReplaceWith($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'ordernumber/creditmemo/replace_with',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
}
