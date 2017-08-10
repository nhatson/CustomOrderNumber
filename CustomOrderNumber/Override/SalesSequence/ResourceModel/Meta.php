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
namespace Bss\CustomOrderNumber\Override\SalesSequence\ResourceModel;

class Meta extends \Magento\SalesSequence\Model\ResourceModel\Meta
{
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {

        $entityType = $object->getEntityType(); //order/invoice/creditmemo/shipment
        $storeId = $object->getStoreId(); //CURRENT STORE ID

        $activeProfile = $this->resourceProfile->loadActiveProfile($object->getId());
        $activeProfile->setPrefix('ORD-'); //SET CUSTOM PREFIX - DEFAULT: store_id
        $activeProfile->setSuffix('A'); //SET CUSTOM SUFFIX
        $activeProfile->setStartValue('2'); //SET START VALUE - DEFAULT: 1
        $activeProfile->setStep('2'); //SET INCREMENT STEP - DEFAULT: 1

        //[UPDATE] USEFUL FOR SHARED ORDER NUMBERS WITHIN MULTIPLE STORES
        $object->setSequenceTable('custom_table'); //SET CUSTOM INCREMENT TABLE - DEFAULT: sequence_{entity_type}_{store_id}


        //SET DATA TO active_profile
        $object->setData(
            'active_profile',
            $activeProfile
        );

        return $this;
    }
}
