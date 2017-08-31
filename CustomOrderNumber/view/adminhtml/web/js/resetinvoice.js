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
define([
    "jquery",
    "prototype"
], function ($) {
        var invoiceSpan = jQuery('#invoice_span');
        var urlInvoice = jQuery('#urlInvoice').text();
        var storeIdInv = jQuery('#storeIdInv').text();
        jQuery('#resetnow_invoice').click(function () {
            var params = {storeId: storeIdInv};
            new Ajax.Request(urlInvoice, {
                parameters:     params,
                loaderArea:     false,
                asynchronous:   true,
                onCreate: function() {
                    invoiceSpan.find('.success').hide();
                    invoiceSpan.find('.processing').show();
                    jQuery('#invoice_message').text('');
                },
                onSuccess: function(response) {
                    invoiceSpan.find('.processing').hide();

                    var resultText = '';
                    if (response.status > 200) {
                        resultText = response.statusText;
                    } else {
                        resultText = 'Success';
                        invoiceSpan.find('.success').show();
                    }
                    jQuery('#invoice_message').text(resultText);
                }
            });
        });
});
