<?
include('../core/st.php');
Users\User::if_user('no_reg');
$title = 'Регистрация';
include(HEAD);
$login = NULL;
$email = NULL;
$sex = 1;
if (isset($_POST['sfsk'])) {
	$login = $_POST['login'];
	$email = $_POST['email'];
	$sex = intval($_POST['sex']);
	if (TextUtils::length(trim($login)) < 5)$error = 'Логин слишком короткий';
	elseif (TextUtils::length($login) > 16)$error = 'Логин слишком длинный';
	elseif ($db -> res("SELECT COUNT(*) FROM `users` WHERE `login` = ?", array($login)))$error = 'Этот логин уже занят';
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))$error = 'Неверный формат e-mail';
	elseif ($db -> res("SELECT COUNT(*) FROM `users_infos` WHERE `email` = ?", array($email)))$error = 'Этот e-mail уже занят';
	elseif (!in_array($sex, array(0, 1)))$error = 'Неверный пол';
	elseif (!Captcha::validate())$error = 'Вы ошиблись при вводе кода с картинки';
	else {
		$db -> q("INSERT INTO `users` (`login`, `pass`) VALUES(?, ?)", array($login, md5($passgen)));
		$uid = $db -> lastInsertId();
		$subject = "Регистрация на Buy-Script.Ru";
		$message = file_get_contents(dr."core/templates/mail/reg.tpl");
		$message = str_replace(array("{SITE_LINK}", "{LOGO_SRC}", "{MSG}"), array("http://".$_SERVER['HTTP_HOST'], "http://".$_SERVER['HTTP_HOST']."/images/logo.png", "Спасибо за регистрацию на <b>".SITE_NAME."</b>!<br />Ваш пароль: <b>$passgen</b><br />Для входа используйте свой e-mail <b>$email</b><br /><a href='http://$_SERVER[HTTP_HOST]/auth'>Вход</a><br />"), $message);
		$headers = "From: \"system@$_SERVER[HTTP_HOST]\" <system@$_SERVER[HTTP_HOST]>\n";
		$headers .= "Content-Type: text/html; charset=utf-8\n";
		mail($email, '=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers);
		$db -> q("INSERT INTO `users_infos` SET `id_user` = ?, `email` = ?, `sex` = ?, `date_reg` = ?, `date_last` = ?", array($uid, $email, $sex, time(), time()));
		//$_SESSION['id_user'] = $uid;
		header("Location: /");
		alerts::msg_sess("Проверьте свою почту!");
		exit();
	}
}
echo alerts::error();
$elms = array();
$elms[] = array('type' => 'title', 'value' => 'Желаемый логин:', 'br' => true);
$elms[] = array('type' => 'text', 'name' => 'login', 'value' => TextUtils::DBFilter($login), 'br' => true);
$elms[] = array('type' => 'title', 'value' => 'E-mail:', 'br' => true);
$elms[] = array('type' => 'text', 'name' => 'email', 'value' => TextUtils::DBFilter($email), 'br' => true);
$elms[] = array('type' => 'title', 'value' => 'Пол:', 'br' => true);
$elms[] = array('type' => 'select', 'name' => 'sex', 'options' => array('1' => 'Мужской', 0 => 'Женский'), 'selected' => ($sex ? 1 : 0), 'br' => true);
$elms[] = array('type' => 'captcha', 'br' => true);
$elms[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Далее', 'br' => true);
$smarty = new SMX();
$smarty -> assign('method', 'POST');
$smarty -> assign('action', '');
$smarty -> assign('el', $elms);
$smarty -> display("form.tpl");
doc::back("Назад", "/");
include(FOOT);
?>