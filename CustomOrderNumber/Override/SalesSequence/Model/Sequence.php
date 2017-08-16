<?php 
namespace Bss\CustomOrderNumber\Override\SalesSequence\Model;

/**
 * Class Meta represents metadata for sequence as sequence table and store id
 */
class Sequence extends \Magento\SalesSequence\Model\Sequence
{
    protected $helper;

    public function __construct(
        \Bss\CookieNotice\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }



    public function getCurrentValue()
    {
        $padding = $this->helper->getOrderPadding();
        die($padding);
        return sprintf(
            "%s%'.05d%s",
            "aaa",
            "70",
            "xxxx"
        );
    }
    public function getNextValue()
    {
    }

    /**
     * Calculate current value depends on start value
     *
     * @return string
     */
    private function calculateCurrentValue()
    {
    }

}
