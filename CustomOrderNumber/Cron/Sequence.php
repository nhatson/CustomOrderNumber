<?php
namespace Bss\CustomOrderNumber\Cron;

use Magento\Framework\App\ResourceConnection as AppResource;

class Sequence 
{
	protected $connection;
    public function __construct (
    	AppResource $resource
    ) {
    	$this->connection = $resource->getConnection('DEFAULT_CONNECTION');
    }
 
    public function execute() 
    {
    	$this->connection->truncateTable('sequence_order_2');
    }
}
