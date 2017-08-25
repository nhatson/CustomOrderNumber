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

class Frequency implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @const Frequency
     */
    const CRON_NEVER = '0';
    const CRON_DAILY = '1';
    const CRON_WEEKLY = '2';
    const CRON_MONTHLY = '3';
    const CRON_YEARLY = '4';

    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::CRON_NEVER, 'label' => __('Never')],
            ['value' => self::CRON_DAILY, 'label' => __('By Day')],
            ['value' => self::CRON_WEEKLY, 'label' => __('By Week')],
            ['value' => self::CRON_MONTHLY, 'label' => __('By Month')],
            ['value' => self::CRON_YEARLY, 'label' => __('By Year')],
        ];
    }
}
