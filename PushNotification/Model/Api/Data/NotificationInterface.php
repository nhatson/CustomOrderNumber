<?php
namespace Bss\PushNotification\Model\Api\Data;
interface NotificationInterface
{
	public function getId();
	public function setId();
	
	public function getName();
	public function setName();
	
	public function getNotificationContent();
	public function setNotificationContent();
	
}