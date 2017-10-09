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
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderObserver implements ObserverInterface
{
    /**
     * Helper
     *
     * @var \Bss\CustomOrderNumber\Helper\Data
     */
    protected $helper;

    /**
     * StoreManager Interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Quote
     *
     * @var \Magento\Backend\Model\Session\Quote
     */
    protected $session;

    /**
     * Order Interface
     *
     * @var \Magento\Sales\Api\Data\OrderInterface
     */
    protected $order;

    /**
     * Config
     *
     * @var \Bss\CustomOrderNumber\Model\ResourceModel\Config
     */
    protected $config;

    /**
     * Construct
     *
     * @param \Bss\CustomOrderNumber\Helper\Data $helper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Backend\Model\Session\Quote $session
     * @param \Magento\Sales\Api\Data\OrderInterface $order 
     * @param \Bss\CustomOrderNumber\Model\ResourceModel\Config $config
     */
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Model\Session\Quote $session,
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Bss\CustomOrderNumber\Model\ResourceModel\Config $config
    ) {
            $this->helper = $helper;
            $this->storeManager = $storeManager;
            $this->session = $session;
            $this->config = $config;
            $this->order = $order;
    }

    /**
     * Set Increment Id
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {   
        if ($this->helper->isOrderEnable()) {
            $storeId = $this->storeManager->getStore()->getStoreId();
            $sessionId = $this->session->getStoreId();
            if (isset($sessionId)) {
                $storeId = $sessionId;
            }
            $format = $this->helper->getOrderFormat($storeId);
            $startValue = $this->helper->getOrderStart($storeId);
            $step = $this->helper->getOrderIncrement($storeId);
            $padding = $this->helper->getOrderPadding($storeId);
            $pattern = "%0".$padding."d";
            $entityType = 'order';
            if ($this->helper->isIndividualOrderEnable($storeId)) {
                if ($storeId == 1) {
                    $table = $this->config->getSequenceTable($entityType, '0');
                } else {
                    $table = $this->config->getSequenceTable($entityType, $storeId);
                }
            } else {
                $table = $this->config->getSequenceTable($entityType, '0');
            }

            $counter = $this->config->counter($table, $startValue, $step, $pattern);
            $result = $this->config->replace($format, $storeId, $counter, $padding);
            try {
                if ($this->order->loadByIncrementId($result)->getId() !== null) {
                    $storeId = 1;
                    $extra = $this->config->extra($entityType, $storeId);
                    $result = $result.$extra;
                }
            } catch (\Exception $e) {
            }
            $orderInstance = $observer->getOrder();
            $orderInstance->setIncrementId($result);
        }           
    }
}
