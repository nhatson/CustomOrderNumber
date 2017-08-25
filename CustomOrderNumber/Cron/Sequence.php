<?php
namespace Bss\CustomOrderNumber\Cron;

class Sequence 
{
	protected $sequence;
	protected $storeManager;
    public function __construct (
    	\Magento\Store\Model\StoreManagerInterface $storeManager,
    	\Bss\CustomOrderNumber\Model\ResourceModel\Sequence $sequence
    ) {
    	$this->storeManager = $storeManager;
    	$this->sequence = $sequence;
    }	

 	public function cronYYYY() 
    {
    	$frequency = 0;
    	$storeId = 0;
    	$this->sequence->setCron($storeId, $frequency);
    	$stores = $this->storeManager->getStores();
    	foreach($stores as $store) {
    		$storeId = $store->getStoreId();
    		$this->sequence->setCron($storeId, $frequency);
    	}
    	return $this;
    }

    public function cronDaily() 
    {
    	$frequency = 1;
    	$storeId = 0;
    	$this->sequence->setCron($storeId, $frequency);
    	$stores = $this->storeManager->getStores();
    	foreach($stores as $store) {
    		$storeId = $store->getStoreId();
    		$this->sequence->setCron($storeId, $frequency);
    	}
    	return $this;
    }

    public function cronWeekly() 
    {
    	$frequency = 2;
    	$storeId = 0;
    	$this->sequence->setCron($storeId, $frequency);
    	$stores = $this->storeManager->getStores();
    	foreach($stores as $store) {
    		$storeId = $store->getStoreId();
    		$this->sequence->setCron($storeId, $frequency);
    	}
    	return $this;
    }

    public function cronMonthly() 
    {
    	$frequency = 3;
    	$storeId = 0;
    	$this->sequence->setCron($storeId, $frequency);
    	$stores = $this->storeManager->getStores();
    	foreach($stores as $store) {
    		$storeId = $store->getStoreId();
    		$this->sequence->setCron($storeId, $frequency);
    	}
    	return $this;
    }

    public function cronYearly() 
    {
    	$frequency = 4;
    	$storeId = 0;
    	$this->sequence->setCron($storeId, $frequency);
    	$stores = $this->storeManager->getStores();
    	foreach($stores as $store) {
    		$storeId = $store->getStoreId();
    		$this->sequence->setCron($storeId, $frequency);
    	}
    	return $this;
    }
}
