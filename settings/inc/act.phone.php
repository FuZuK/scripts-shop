<?
$title = 'Мой номер телефона';
include_once(HEAD);
if (isset($_POST['save']) && ussec::check_p()) {
	if (!Captcha::validate())
		$error = 'Вы ошиблись при вводе кода с картинки';
	elseif (TextUtils::is_empty($u -> info -> phone_new)) {
		$phone_new = $_POST['phone_new'];
		if (!is_numeric($phone_new) || TextUtils::lenNtrim($phone_new) != 12)
			$error = 'Неверный формат номера телефона';
		elseif ($phone_new == $u -> info -> phone)
			$error = 'Вы ввели свой старый номер телефона';
		elseif ($db -> res('SELECT COUNT(*) FROM `users_infos` WHERE `phone` = ?', array($phone_new)) > 0)
			$error = 'Этот номер телефона уже используется другим пользователем';
		else {
			$phone_new_code = mt_rand(10, 30).mt_rand(31, 70).mt_rand(71, 90);
			$message = "Код подтверждения: ".$phone_new_code;
			$result = SMSC_Api::send_sms($phone_new, $message, 0, 0, 0, 0, SITE_NAME);
			if (!isset($result -> error_code)) {
				$u -> info -> setData('phone_new', $phone_new);
				$u -> info -> setData('phone_new_code', $phone_new_code);
				$u -> info -> setData('phone_new_id_sms', $result -> id);
				alerts::msg_sess('Код подтверждения был отправлен на указанный вами номер телефона');
			} else {
				alerts::error_sess('Произошла ошибка. Попробуйте позже.');
			}
			header("Location: ?");
			exit();
		}
	} elseif (isset($_POST['confirm_code'])) {
		$confirm_code = $_POST['confirm_code'];
		if ($u -> info -> phone_new_code != $confirm_code)
			$error = 'Неверный код подтверждения';
		elseif ($db -> res('SELECT COUNT(*) FROM `users_infos` WHERE `phone` = ?', array($u -> info -> phone_new)) > 0)
			$error = 'Этот номер телефона уже используется другим пользователем';
		else {
			$u -> confirmNewPhoneNumber();
			alerts::msg_sess('Номер телефона успешно подтвержден');
			header("Location: ?");
			exit();
		}
	}
}
echo alerts::error();
if ($u -> info -> phone) {
	echo "<div class='content'>\n";
	echo "<span class='form_q'>Текущий номер телефона:</span> <span class='form_a'>".TextUtils::DBFilter($u -> info -> phone)."</span>\n";
	echo "</div>\n";
}
echo "<div class='content_redi'>\n";
echo "Указывайте свой реальный номер. На введенный Вами номер будет отослан код для его подтверждения. Без кода подтверждения смена номера телефона невозможна!";
echo "</div>\n";
$el = array(
	array('type' => 'title', 'value' => TextUtils::is_empty($u -> info -> phone) ? 'Номер телефона' : 'Новый номер:', 'br' => true), 
	array('type' => 'text', 'name' => 'phone_new', 'value' => TextUtils::DBFilter($u -> info -> phone_new), 'disabled' => !TextUtils::is_empty($u -> info -> phone_new), 'br' => true, 'alert' => 'В международном формате, например 79112223344')
);
if (!TextUtils::is_empty($u -> info -> phone_new))
	$el = array_merge($el, array(
		array('type' => 'title', 'value' => 'Код подтверждения:', 'br' => true), 
		array('type' => 'text', 'name' => 'confirm_code', 'value' => '', 'br' => true)
	));
$save_btn_text = 'Отправить код';
if (TextUtils::is_empty($u -> info -> phone) && TextUtils::is_empty($u -> info -> phone_new) || TextUtils::is_empty($u -> info -> phone))
	$save_btn_text = 'Отправить код';
if (!TextUtils::is_empty($u -> info -> phone_new_code))
	$save_btn_text = 'Подтвердить';
$el = array_merge($el, array(
	array('type' => 'ussec'), 
	array('type' => 'captcha', 'br' => true), 
	array('type' => 'submit', 'name' => 'save', 'value' => $save_btn_text)
));
new SMX(array('el' => $el), 'form.tpl');
if (!TextUtils::is_empty($u -> info -> phone_new)) {
	$sms = $db -> farr('SELECT * FROM `smsc_messages` WHERE `id_sms` = ?', array($u -> info -> phone_new_id_sms));
	echo "<hr>\n";
	echo "<div class='content'>\n";
	$result = SMSC_Api::get_status($sms -> id_sms, $sms -> phone);
	if (!isset($result -> error_code)) {
		echo "<span class='form_q'>Статус СМС:</span> <span class='form_a'>".SMSC_Api::$sms_statuses[$result -> status]."</span><br />\n";
	} else {
		echo "Произошла ошибка!<br />\n";
		echo "Код ошибки: {$result -> error_code} - {$result -> error}<br />\n";
	}
	echo "</div>\n";
}
doc::back("Настройки", "/settings");
?>