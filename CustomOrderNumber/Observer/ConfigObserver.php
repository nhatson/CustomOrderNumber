<?php
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Bss\CustomOrderNumber\Helper\Data;
use Magento\Framework\App\ResourceConnection as AppResource;

class ConfigObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $datetime;
    protected $request;
    protected $helper;
    protected $storeManager;
    /**
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Data $helper,
        AppResource $resource
        ) {
            $this->datetime = $datetime;
            $this->request = $request;
            $this->helper = $helper;
            $this->storeManager = $storeManager;
            $this->connection = $resource->getConnection('DEFAULT_CONNECTION');

        }

    public function execute(EventObserver $observer)
    {   
        $timezone = $this->helper->timezone();
        date_default_timezone_set($timezone);
        $df = "G:i:s";
        $ts1 = strtotime(date($df));
        $ts2 = strtotime(gmdate($df));
        $ts3 = ($ts1-$ts2)/3600; 
        if ($ts3 >=0 ) {
            $sql = "SET time_zone = '+".$ts3.":00';"; 
        } else {
            $sql = "SET time_zone = '".$ts3.":00';"; 
        }
        $this->connection->query($sql);
        $sql= "SET GLOBAL event_scheduler = 0;";
        $this->connection->query($sql);

        $storeId = 0;

        $orderReset = $this->helper->getOrderReset($storeId);
        $invoiceReset = $this->helper->getInvoiceReset($storeId);
        $shipmentReset = $this->helper->getShipmentReset($storeId);
        $creditmemoReset = $this->helper->getShipmentReset($storeId);
        $nameTable = array('sequence_order_' => $orderReset,
                            'sequence_invoice_' => $invoiceReset,
                            'sequence_shipment_' => $shipmentReset,
                            'sequence_creditmemo_' => $creditmemoReset);
        foreach ($nameTable as $key => $value) 
        {
            switch ($value) 
            {
                case '0':
                    $sql = "Drop event if exists ".$key.$storeId;
                    $this->connection->query($sql);
                    break;
                
                case '1':
                    $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                        ON SCHEDULE EVERY 1 DAY
                        STARTS '2017-01-01 00:00:00'
                        DO TRUNCATE ".$key.$storeId.";";
                    $this->connection->query($sql);
                    break;
                case '2':
                    $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                        ON SCHEDULE EVERY 1 MONTH
                        STARTS '2017-01-01 00:00:00'
                        DO TRUNCATE ".$key.$storeId.";";
                    $this->connection->query($sql);
                    break;
                case '3':
                    $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                        ON SCHEDULE EVERY 1 YEAR
                        STARTS '2017-01-01 00:00:00'
                        DO TRUNCATE ".$key.$storeId.";";
                    $this->connection->query($sql);
                    break;
                default:
                    $sql = "Drop event if exists ".$key.$storeId."";
                    $this->connection->query($sql);
                    break;
            }
        }  

        $stores = $this->storeManager->getStores();
        foreach($stores as $store) 
        {
            $storeId = $store->getStoreId();
            $orderReset = $this->helper->getOrderReset($storeId);
            $invoiceReset = $this->helper->getInvoiceReset($storeId);
            $shipmentReset = $this->helper->getShipmentReset($storeId);
            $creditmemoReset = $this->helper->getShipmentReset($storeId);
            $nameTable = array('sequence_order_' => $orderReset,
                                'sequence_invoice_' => $invoiceReset,
                                'sequence_shipment_' => $shipmentReset,
                                'sequence_creditmemo_' => $creditmemoReset);           
            foreach ($nameTable as $key => $value) 
            {
                switch ($value) 
                {
                    case '0':
                        $sql = "Drop event if exists ".$key.$storeId;
                        $this->connection->query($sql);
                        break;
                    
                    case '1':
                        $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                            ON SCHEDULE EVERY 1 DAY
                            STARTS '2017-01-01 00:00:00'
                            DO TRUNCATE ".$key.$storeId.";";
                        $this->connection->query($sql);
                        break;
                    case '2':
                        $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                            ON SCHEDULE EVERY 1 MONTH
                            STARTS '2017-01-01 00:00:00'
                            DO TRUNCATE ".$key.$storeId.";";
                        $this->connection->query($sql);
                        break;
                    case '3':
                        $sql = "CREATE OR REPLACE EVENT ".$key.$storeId."
                            ON SCHEDULE EVERY 1 YEAR
                            STARTS '2017-01-01 00:00:00'
                            DO TRUNCATE ".$key.$storeId.";";
                        $this->connection->query($sql);
                        break;
                    default:
                        $sql = "Drop event if exists ".$key.$storeId."";
                        $this->connection->query($sql);
                        break;
                }
            } 
        }
    }
}
