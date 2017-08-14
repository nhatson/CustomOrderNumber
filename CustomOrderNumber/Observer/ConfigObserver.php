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

    /**
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        Logger $logger
        ) {
        $this->logger = $logger;
        $this->datetime = $datetime;
    }

    public function execute(EventObserver $observer)
    {   
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
        $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
        ->get('Magento\Framework\App\ResourceConnection');
        $connection= $this->_resources->getConnection();
        $sql = "UPDATE `sales_sequence_profile` SET `prefix` = 'CLG-' WHERE `meta_id` = 5;";
//         $sql = "CREATE EVENT new;
//                 ON SCHEDULE
//       EVERY 1 MINUTE
//         DO
// update 'sales_sequence_profile' 
// set prefix= 'aa' 
// where `meta_id` = 5;";
        $connection->query($sql);
    }
}
