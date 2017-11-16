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
    apiKey: "AIzaSyBDXz8DSnxphC7ywqhBGu9oZ0C35uO7QU8",
    authDomain: "test-e2f58.firebaseapp.com",
    databaseURL: "https://test-e2f58.firebaseio.com",
    projectId: "test-e2f58",
    storageBucket: "test-e2f58.appspot.com",
    messagingSenderId: "384501794118"
  };
  firebase.initializeApp(config);

  const messaging = firebase.messaging();
  messaging.requestPermission()
    .then(function() {
      console.log('Have permission.');
      return messaging.getToken();
      // TODO(developer): Retrieve an Instance ID token for use with FCM.
      // ...
    })
    .then(function(token) {
        console.log(token);
    })
    .catch(function(err) {
      console.log('Error Occured.');
      console.log(err);
    });
});
