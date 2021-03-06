<?php
/**
 * Copyright © 2015 Inchoo d.o.o.
 * created by Zoran Salamun(zoran.salamun@inchoo.net)
 */
namespace Bss\PushNotification\Controller\Adminhtml\Notification;

class Index extends \Magento\Backend\App\Action
{
	protected $resultPageFactory = false;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	) {
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		//Call page factory to render layout and page content
		$this->_setPageData();
        return $this->getResultPage();
	}

	/*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Bss_PushNotification::notification_manage');
	}

    public function getResultPage()
    {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }
        return $this->_resultPage;
    }

    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('Bss_PushNotification::notification');
        $resultPage->getConfig()->getTitle()->prepend((__('Posts')));

        //Add bread crumb
        $resultPage->addBreadcrumb(__('Bss'), __('Bss'));
        $resultPage->addBreadcrumb(__('PushNotification'), __('Manage Notification'));

        return $this;
    }
}