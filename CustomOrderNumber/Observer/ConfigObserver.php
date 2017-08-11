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

    /**
     * @param Logger $logger
     */
    public function __construct(
        Logger $logger
        ) {
        $this->logger = $logger;
    }

    public function execute(EventObserver $observer)
    {   
        $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
        ->get('Magento\Framework\App\ResourceConnection');
        $connection= $this->_resources->getConnection();
        $sql = "UPDATE `sales_sequence_profile` SET `prefix` = 'CX-' WHERE `meta_id` = 5;";
        $connection->query($sql);
    }
}
