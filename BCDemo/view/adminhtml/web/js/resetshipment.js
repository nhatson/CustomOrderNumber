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
        var shipmentSpan = $('#shipment_span');
        var urlShipment = $('#urlShipment').text();
        var storeIdShip = $('#storeIdShip').text();
        $('#resetnow_shipment').click(function () {
            var params = {storeId: storeIdShip};
            new Ajax.Request(urlShipment, {
                parameters:     params,
                loaderArea:     false,
                asynchronous:   true,
                onCreate: function() {
                    shipmentSpan.find('.success').hide();
                    shipmentSpan.find('.processing').show();
                    $('#shipment_message').text('');
                },
                onSuccess: function(response) {
                    shipmentSpan.find('.processing').hide();

                    var resultText = '';
                    if (response.status > 200) {
                        resultText = response.statusText;
                    } else {
                        resultText = 'Success';
                        shipmentSpan.find('.success').show();
                    }
                    $('#shipment_message').text(resultText);
                }
            });
        });
});
