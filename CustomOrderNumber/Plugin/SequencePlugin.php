<?php

namespace Bss\CustomOrderNumber\Plugin;
use Magento\Framework\Exception\LocalizedException as Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\Context as DatabaseContext;
use Magento\SalesSequence\Model\ResourceModel\Profile as ResourceProfile;
use Magento\SalesSequence\Model\MetaFactory;
use Magento\SalesSequence\Model\Profile as ModelProfile;
class SequencePlugin 
{
 

    /**
     * @param DatabaseContext $context
     * @param MetaFactory $metaFactory
     * @param ResourceProfile $resourceProfile
     * @param string $connectionName
     */
    private $helper;
    private $object;
    public function __construct(
        \Bss\CustomOrderNumber\Helper\Data $helper,
        \Magento\Framework\Model\AbstractModel $object
        ) {
            $this->helper=$helper;
            $this->object = $object;
        }
    public function beforeGetCurrentValue($subject)
    {   
        
        // if (!isset($subject->lastIncrementId)) {
        //     return null;
        // }
        $entityType = $this->$object->getEntityType();
        // $activeProfile = $this->resourceProfile->loadActiveProfile($object->getId());
        // var_dump($activeProfile);
        die($entityType);
    }
}
