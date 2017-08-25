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

namespace Bss\CustomOrderNumber\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\ResourceConnection as AppResource;

class ResetCreditmemo extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var AppResource
     */
    protected $connection;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param AppResource $resource
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
     * Truncate Table
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('storeId');
        $table = 'sequence_creditmemo_'.$storeId;     
        $resetCreditmemo = $this->connection->truncateTable($table);
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();
        
        return $result->setData(['success' => true, 'resetnow' => $resetCreditmemo]);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bss_CustomOrderNumber::config');
    }
}
