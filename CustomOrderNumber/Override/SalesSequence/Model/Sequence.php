<?php 
namespace Bss\CustomOrderNumber\Override\SalesSequence\Model;

/**
 * Class Meta represents metadata for sequence as sequence table and store id
 */
class Sequence extends \Magento\SalesSequence\Model\Sequence
{

    public function getCurrentValue()
    {
        
        die('333');
        return sprintf(
            "%s%'.05d%s",
            "aaa",
            "70",
            "xxxx"
        );
    }
}
