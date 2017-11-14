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
 * @category   BSS
 * @package    Bss_PushNotification
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
define([
    "jquery",
    "jquery/ui"
], function ($) {
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyAfNgQwbGvK-gY9i9hytUZPEtfba1ggrLg",
    authDomain: "m2-push-notification.firebaseapp.com",
    databaseURL: "https://m2-push-notification.firebaseio.com",
    projectId: "m2-push-notification",
    storageBucket: "m2-push-notification.appspot.com",
    messagingSenderId: "397391577043"
  };
  firebase.initializeApp(config);

  const messaging = firebase.messaging();
  messaging.requestPermission()
    .then(function() {
      console.log('Have permission.');
      // return messaging.getToken();
      // TODO(developer): Retrieve an Instance ID token for use with FCM.
      // ...
    })
    // .then(function(token) {
    //     console.log(token);
    // })
    .catch(function(err) {
      console.log('Error Occured.');
      console.log(err);
    });
});
