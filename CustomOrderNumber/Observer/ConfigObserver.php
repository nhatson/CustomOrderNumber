<?php
namespace Bss\CustomOrderNumber\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Bss\CustomOrderNumber\Helper\Data;

class ConfigObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $datetime;
    protected $request;
    protected $helper;
    protected $storeManager;
    protected $sequence;
    /**
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Data $helper,
        \Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
        ) {
            $this->datetime = $datetime;
            $this->request = $request;
            $this->helper = $helper;
            $this->storeManager = $storeManager;
            $this->sequence = $sequence;
        }

    public function execute(EventObserver $observer)
    {   
        $this->sequence->setGlobal();
        $storeId = 0;
        $this->sequence->setEvent($storeId);
        $stores = $this->storeManager->getStores();
        foreach($stores as $store) 
        {
            $storeId = $store->getStoreId();
            $this->sequence->setEvent($storeId);
        }
    }
}
