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
        $storeId = $object->getStoreId();
        $entityType = $object->getEntityType();  //order/invoice/creditmemo/shipment
        $activeProfile = $this->resourceProfile->loadActiveProfile($object->getId());

        if($entityType == 'order' && $helper->isOrderEnable()){
            $format = $helper->getOrderFormat();

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

                $format = $helper->replace($format, $storeId);

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
            if($helper->isShipmentSameOrder() && $helper->isOrderEnable()){
                
                $format = $helper->getOrderFormat();
                $replace = $helper->getShipmentReplace();
                $replaceWith = $helper->getShipmentReplaceWith();
                $format = str_replace($replace, $replaceWith, $format);

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
            
            if(!$helper->isShipmentSameOrder()) {
                $format = $helper->getShipmentFormat();

                $format = $helper->replace($format, $storeId);

                $explode = explode('{counter}', $format);
                $prefix = $explode[0];
                if (isset($explode[1])){
                    $suffix = $explode[1];   
                } else {
                    $suffix = "";
                }

                $startValue = $helper->getShipmentStart();
                $step = $helper->getShipmentIncrement();

                $activeProfile->setPrefix($prefix);
                $activeProfile->setSuffix($suffix);
                $activeProfile->setStartValue($startValue);
                $activeProfile->setStep($step);
            }         
        }

        if($entityType == 'creditmemo' && $helper->isCreditMemoEnable()){
            if($helper->isCreditMemoSameOrder() && $helper->isOrderEnable()){
                
                $format = $helper->getOrderFormat();
                $replace = $helper->getCreditMemoReplace();
                $replaceWith = $helper->getCreditMemoReplaceWith();
                $format = str_replace($replace, $replaceWith, $format);

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

            if(!$helper->isCreditMemoSameOrder()) {
                $format = $helper->getCreditMemoFormat();

                $format = $helper->replace($format, $storeId);

                $explode = explode('{counter}', $format);
                $prefix = $explode[0];
                if (isset($explode[1])){
                    $suffix = $explode[1];   
                } else {
                    $suffix = "";
                }

                $startValue = $helper->getCreditMemoStart();
                $step = $helper->getCreditMemoIncrement();

                $activeProfile->setPrefix($prefix);
                $activeProfile->setSuffix($suffix);
                $activeProfile->setStartValue($startValue);
                $activeProfile->setStep($step);
            }         
        }

        $object->setData(
            'active_profile',
            $activeProfile
            );
        
        return $this;
    }
}
