<?
namespace Users;
class Notifications {
	public static function send($type, $user_id, $message) {
		global $db;
		$smsc_configs = new \configs('smsc.dat');
		$us = new User($user_id);
		if ($us -> exists() && !$us -> isOnline() && !self::notificationExists($type, $user_id)) {
			$sended = false;
			$nts_sms_set_var = 'sms_'.$type;
			if (!\TextUtils::is_empty($us -> info -> phone) && $us -> getNotificationsSettings() -> $nts_sms_set_var == 1 && $us -> money >= $smsc_configs -> sms_price) {
				$result = \SMSC_Api::send_sms($us -> info -> phone, $message, 0, 0, 0, 0, SITE_NAME);
				if (!isset($result -> error_code)) {
					$us -> moneyMinus($smsc_configs -> sms_price);
				}
				$sended = true;
			}
			$nts_email_set_var = 'email_'.$type;
			if (!\TextUtils::is_empty($us -> info -> email) && $us -> getNotificationsSettings() -> $nts_email_set_var == 1) {
				$headers = "From: \"system@$_SERVER[HTTP_HOST]\" <system@$_SERVER[HTTP_HOST]>\n";
				$headers .= "Content-Type: text/html; charset=utf-8\n";
				$subject = "Оповещение с ".SITE_NAME;
				mail($us -> info -> email, '=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers);
				$sended = true;
			}
				// \alerts::msg_sess($nts_email_set_var);
			if ($sended)
				self::addNotificationToDB($type, $user_id);
		}
	}

	public static function notificationExists($type, $user_id, $readed = 0) {
		global $db;
		return $db -> res('SELECT COUNT(*) FROM `users_notifications` WHERE `id_user` = ? AND `type` = ? AND `read` = ?', array($user_id, $type, $readed)) == 0 ? false : true;
	}

	public static function addNotificationToDB($type, $user_id) {
		global $db;
		if (!self::notificationExists($type, $user_id, 1)) {
			$db -> q('INSERT INTO `users_notifications` (`id_user`, `type`) VALUES (?, ?)', array($user_id, $type));
		} else {
			$db -> q('UPDATE `users_notifications` SET `read` = "0" WHERE `id_user` = ? AND `type` = ?', array($user_id, $type));
		}
	}

	public static function readNotification($notification_id) {
		global $db;
		$db -> q('UPDATE `users_notifications` SET `read` = ? WHERE `id` = ?', array(1, $notification_id));
	}

	public static function readNotificaions() {
		global $db, $u;
		$q = $db -> q('SELECT * FROM `users_notifications` WHERE `read` = "0" AND `id_user` = ?', array($u -> id));
		while ($post = $q -> fetch())
			self::readNotification($post -> id);
	}
}
?>