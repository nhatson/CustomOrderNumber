<?php
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Psr\Log\LoggerInterface as Logger;

class ConfigObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $logger;
    protected $datetime;
    protected $storeManager;
    protected $request;
    /**
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Logger $logger,
        \Magento\Framework\App\Request\Http $request
        ) {
            $this->logger = $logger;
            $this->datetime = $datetime;
            $this->storeManager = $storeManager;
            $this->request=$request;
        }

    public function execute(EventObserver $observer)
    {   
//         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

// $currentCustomer = $objectManager->get('Magento\Customer\Model\Session')->getCustomer();
//         if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getStore())) // store level
//         {
//             $store_id = Mage::getModel('core/store')->load($code)->getId();
//         }
//         elseif (strlen($code = Mage::getSingleton('adminhtml/config_data')->getWebsite())) // website level
//         {
//             $website_id = Mage::getModel('core/website')->load($code)->getId();
//             $store_id = Mage::app()->getWebsite($website_id)->getDefaultStore()->getId();
//         }
//         else // default level
//         {
            echo $this->request->getParam('store', 1);

        // die($this->request->getParam('store', 1));
        // $date = $this->datetime->gmtDate();
        // $date2 = $this->datetime->gmtTimestamp();
        // echo '<pre>';
        // print_r($date);
        // echo '<pre>';print_r($date2);
        // die();

// CREATE OR REPLACE EVENT aaaaa
// ON SCHEDULE EVERY 1 SECOND
// DO
// update cart set product_id = 2 WHERE created_at <= DATE_SUB(NOW(), INTERVAL 1 SECOND) ;
        // $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
        // ->get('Magento\Framework\App\ResourceConnection');
        // $connection= $this->_resources->getConnection();
        // $sql = "SELECT  `sequence_value` FROM `sequence_order_1` WHERE `sequence_value` = '10'";
        // $queryResult = mysqli_query($connection, $sql);
        // $row = mysqli_fetch_array($queryResult);
        // var_dump($row);
        // die('bss');
        // $sql = "UPDATE `sales_sequence_profile` SET `prefix` = 'CLG-' WHERE `meta_id` = 5;";

//         $sql = "CREATE EVENT new;
//                 ON SCHEDULE
//       EVERY 1 MINUTE
//         DO
// update 'sales_sequence_profile' 
// set prefix= 'aa' 
// where `meta_id` = 5;";
        // $connection->query($sql);
    }
}
