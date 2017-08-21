<?php
/**
 * Copyright © 2016 Bss. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Bss\CustomOrderNumber\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Bss\CustomOrderNumber\Helper\Data;

class ResetCreditmemo extends Action
{

    protected $resultJsonFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Data $helper
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        try {
            $this->_getSyncSingleton()->collectRelations();
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
        }

        $resetCreditmemo = $this->helper->resetCreditmemo();
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();
        
        return $result->setData(['success' => true, 'resetnow' => $resetCreditmemo]);
    }

    /**
     * Return product relation singleton
     *
     * @return \Bss\CustomOrderNumber\Model\Relation
     */
    protected function _getSyncSingleton()
    {
        return $this->_objectManager->get('Bss\CustomOrderNumber\Model\Relation');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bss_CustomOrderNumber::config');
    }
}
