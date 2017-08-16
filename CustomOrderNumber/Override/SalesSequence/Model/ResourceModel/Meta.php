<?php 
namespace Bss\CustomOrderNumber\Override\SalesSequence\Model\ResourceModel;

/**
 * Class Meta represents metadata for sequence as sequence table and store id
 */
class Meta extends \Magento\SalesSequence\Model\ResourceModel\Meta
{
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectManager->get('Bss\CustomOrderNumber\Helper\Data');
        $entityType = $object->getEntityType();  //order/invoice/creditmemo/shipment
        $activeProfile = $this->resourceProfile->loadActiveProfile($object->getId());

        if($entityType == 'order' && $helper->isOrderEnable()){
            $format = $helper->getOrderFormat();
            $format = $helper->replace($format);

            $format = $helper->replace($format);

            $explode = explode('{counter}', $format);
            $prefix = $explode[0];
            if (isset($explode[1])){
                $suffix = $explode[1];   
            } else {
                $suffix = "";
            }

            $startValue = $helper->getOrderStart();
            $step = $helper->getOrderIncrement();

            $activeProfile->setPrefix($prefix);
            $activeProfile->setSuffix($suffix);
            $activeProfile->setStartValue($startValue);
            $activeProfile->setStep($step);
        }

        if($entityType == 'invoice' && $helper->isInvoiceEnable()){
            if($helper->isInvoiceSameOrder() && $helper->isOrderEnable()){
                
                $format = $helper->getOrderFormat();
                $replace = $helper->getInvoiceReplace();
                $replaceWith = $helper->getInvoiceReplaceWith();
                $format = str_replace($replace, $replaceWith, $format);

                $storeId = $object->getStoreId();
                $format = $helper->replace($format, $storeId);

                $explode = explode('{counter}', $format);
                $prefix = $explode[0];
                if (isset($explode[1])){
                    $suffix = $explode[1];   
                } else {
                    $suffix = "";
                }

                $startValue = $helper->getOrderStart();
                $step = $helper->getOrderIncrement();
                $activeProfile->setPrefix($prefix);
                $activeProfile->setSuffix($suffix);
                $activeProfile->setStartValue($startValue);
                $activeProfile->setStep($step);

            } 
            if(!$helper->isInvoiceSameOrder()) {
                $format = $helper->getInvoiceFormat();
                $explode = explode('{counter}', $format);
                $prefix = $explode[0];
                if (isset($explode[1])){
                    $suffix = $explode[1];   
                } else {
                    $suffix = "";
                }
                $startValue = $helper->getInvoiceStart();
                $step = $helper->getInvoiceIncrement();
                $activeProfile->setPrefix($prefix);
                $activeProfile->setSuffix($suffix);
                $activeProfile->setStartValue($startValue);
                $activeProfile->setStep($step);
            }         
        }

        if($entityType == 'shipment' && $helper->isShipmentEnable()){
            $format = $helper->getOrderFormat();
            $explode = explode('{counter}', $format);
            $prefix = $explode[0];
            if (isset($explode[1])){
                $suffix = $explode[1];   
            } else {
                $suffix = "";
            }
            $activeProfile->setPrefix($prefix);
            $activeProfile->setSuffix($suffix);
            $activeProfile->setStartValue('1');
            $activeProfile->setStep('1');
        }

        if($entityType == 'creditmemo' && $helper->isCreditMemoEnable()){
            $format = $helper->getOrderFormat();
            $explode = explode('{counter}', $format);
            $prefix = $explode[0];
            if (isset($explode[1])){
                $suffix = $explode[1];   
            } else {
                $suffix = "";
            }
            $activeProfile->setPrefix($prefix);
            $activeProfile->setSuffix($suffix);
            $activeProfile->setStartValue('1');
            $activeProfile->setStep('1');
        }

        $object->setData(
            'active_profile',
            $activeProfile
            );
        return $this;
    }
}
