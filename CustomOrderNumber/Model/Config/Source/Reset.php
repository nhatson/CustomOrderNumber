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
namespace Bss\CustomOrderNumber\Model\Config\Source;

class Reset implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @const Position
     */
    const POSITION_BOTTOM_LEFT = '0';
    const POSITION_TOP_LEFT = '1';
    const POSITION_BOTTOM_RIGHT = '2';
    const POSITION_TOP_RIGHT = '3';

    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::POSITION_BOTTOM_LEFT, 'label' => __('Never')],
            ['value' => self::POSITION_TOP_LEFT, 'label' => __('By Day')],
            ['value' => self::POSITION_BOTTOM_RIGHT, 'label' => __('By Month')],
            ['value' => self::POSITION_TOP_RIGHT, 'label' => __('By Year')],
        ];
    }
}
