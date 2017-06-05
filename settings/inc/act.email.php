<?
$title = 'Мой E-mail';
include_once(HEAD);
if (isset($_GET['confirm_code']) && !empty($u -> info -> email_new)) {
	$confirm_code = $_GET['confirm_code'];
	if ($u -> info -> email_new_code != $confirm_code)
		$error = 'Неверный код подтверждения';
	elseif ($db -> res('SELECT COUNT(*) FROM `users_infos` WHERE `email` = ?', array($u -> info -> email_new)) > 0)
		$error = 'Этот e-mail уже используется другим пользователем';
	else {
		$u -> confirmNewEmail();
		alerts::msg_sess('E-mail успешно изменен');
		header("Location: /settings/email");
		exit();
	}
}
if (isset($_POST['save']) && ussec::check_p()) {
	if (!Captcha::validate())
		$error = 'Вы ошиблись при вводе кода с картинки';
	elseif (empty($u -> info -> email_new)) {
		$email_new = $_POST['email_new'];
		if (!filter_var($email_new, FILTER_VALIDATE_EMAIL))
			$error = 'Неверный формат e-mail';
		elseif ($email_new == $u -> info -> email)
			$error = 'Вы ввели свой старый e-mail';
		elseif ($db -> res('SELECT COUNT(*) FROM `users_infos` WHERE `email` = ?', array($email_new)) > 0)
			$error = 'Этот e-mail уже используется другим пользователем';
		else {
			$email_new_code = md5(mt_rand(1000, 9999));
			$db -> q('UPDATE `users_infos` SET `email_new` = ?, `email_new_code` = ? WHERE `id` = ?', array($email_new, $email_new_code, $u -> info -> id));
			$message = "Для продолжения смены e-mail введите код: ".$email_new_code." или перейдите по ссылке ниже:<br />";
			$link = "http://$_SERVER[HTTP_HOST]/settings/email?confirm_code=$email_new_code";
			$message .= "<a href='$link'>$link</a><br /><br />";
			$message .= "Если Вы не хотите продолжать процесc подтверждения - проигнорируйте это сообщение.<br /><br />";
			$message .= "Благодарим за использование нашего сервиса.<br />";
			$message .= "С уважением, команда ".SITE_NAME."!";
			$headers = "From: \"system@$_SERVER[HTTP_HOST]\" <system@$_SERVER[HTTP_HOST]>\n";
			$headers .= "Content-Type: text/html; charset=utf-8\n";
			$subject = "Подтверждение e-mail";
			mail($email_new, '=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers);
			alerts::msg_sess('Код подтверждения был отправлен на указанный вами e-mail');
			header("Location: ?");
			exit();
		}
	} elseif (isset($_POST['confirm_code'])) {
		$confirm_code = $_POST['confirm_code'];
		if ($u -> info -> email_new_code != $confirm_code)
			$error = 'Неверный код подтверждения';
		elseif ($db -> res('SELECT COUNT(*) FROM `users_infos` WHERE `email` = ?', array($u -> info -> email_new)) > 0)
			$error = 'Этот e-mail уже используется другим пользователем';
		else {
			$u -> confirmNewEmail();
			alerts::msg_sess('E-mail успешно изменен');
			header("Location: /settings/email");
			exit();
		}
	}
}
echo alerts::error();
echo "<div class='content'>\n";
echo "<span class='form_q'>Текущий e-mail:</span> <span class='form_a'>".TextUtils::DBFilter($u -> info -> email)."</span><br />\n";
echo "</div>\n";
echo "<div class='content_redi'>\n";
echo "Указывайте свой реальный e-mail. На введенный Вами e-mail будет отослан код для его подтверждения. Без кода подтверждения смена e-mail невозможна!";
echo "</div>\n";
$el = array(
	array('type' => 'title', 'value' => 'Новый e-mail:', 'br' => true), 
	array('type' => 'text', 'name' => 'email_new', 'value' => TextUtils::DBFilter($u -> info -> email_new), 'disabled' => !empty($u -> info -> email_new), 'br' => true)
);
if (!empty($u -> info -> email_new))
	$el = array_merge($el, array(
		array('type' => 'title', 'value' => 'Код подтверждения:', 'br' => true), 
		array('type' => 'text', 'name' => 'confirm_code', 'value' => '', 'br' => true)
	));
$el = array_merge($el, array(
	array('type' => 'ussec'), 
	array('type' => 'captcha', 'br' => true), 
	array('type' => 'submit', 'name' => 'save', 'value' => empty($u -> info -> email_new) ? 'Сменить' : 'Подтвердить')
));
new SMX(array('el' => $el), 'form.tpl');
doc::back("Настройки", "/settings");
include(FOOT);
?>