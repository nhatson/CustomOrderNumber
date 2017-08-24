<?php
/**
 * Copyright © 2016 Bss. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Bss\CustomOrderNumber\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\ResourceConnection as AppResource;

class ResetOrder extends Action
{

    protected $resultJsonFactory;

    /**
     * @var Data
     */
    protected $connection;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        AppResource $resource
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->connection = $resource->getConnection('DEFAULT_CONNECTION');
        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('storeId');
        $table = 'sequence_order_'.$storeId;
        $resetOrder = $this->connection->truncateTable($table);;
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();
        
        return $result->setData(['success' => true, 'resetnow' => $resetOrder]);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bss_CustomOrderNumber::ordernumber');
    }
}
