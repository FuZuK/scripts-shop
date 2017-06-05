<?
$input_page = true;
include('../core/st.php');
Users\User::if_user('no_reg');
$title = 'Авторизация';
include(HEAD);
// if (isset($_COOKIE['id_user']) && isset($_COOKIE['pass']) && $_COOKIE['id_user'] && $_COOKIE['pass']) {
// 	if ($db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ? AND `pass` = ?", array(intval($_COOKIE['id_user']), TextUtils::escape($_COOKIE['pass']))) == 1) {
// 		$u = new Users\User(intval($_COOKIE['id_user']));
// 		$_SESSION['id_user'] = $u -> id;
// 		$db -> q("UPDATE `user` SET `date_aut` = ?, `date_last` = ? WHERE `id` = ? LIMIT 1", array(time(), time(), $u -> id));
// 		$u -> type_input = 'cookie';
// 	} else {
// 		SetCookie('id_user', '');
// 		SetCookie('pass', '');
// 		$sys -> document();
// 		$sys -> panel_up();
// 		$sys -> title();
// 		echo alerts::error("Ошибка авторизации по COOKIE");
// 		? >
// 		<hr>
// 		<a href="?" class="back">Повторить попытку</a>
// 		<?
// 		$sys -> panel_down();
// 	}
// }
$login = NULL;
$email = NULL;
$sex = 1;
if (isset($_POST['sfsk'])) {
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	@$us_ank_auth = $db -> farr("SELECT * FROM `users_infos` WHERE `email` = ?", array($email));
	@$us_auth = $db -> farr("SELECT * FROM `users` WHERE `id` = ?", array($us_ank_auth -> id_user));
	if (!@$us_ank_auth -> id || @$us_auth -> pass != md5($pass))$error = 'Неверные e-mail или пароль';
	else {
		$u = new Users\User($us_auth -> id);
		$_SESSION['id_user'] = $u -> id;
		// сохранение данных в COOKIE
		// if (isset($_POST['in_cookies']) && $_POST['in_cookies']) {
		// 	setcookie('id_user', $u -> id, time() + 60*60*24*365, '/');
		// 	setcookie('pass', $pass, time() + 60*60*24*365, '/');
		// }

		$db -> q("INSERT INTO `loghist` SET `id_user` = ?, `time` = ?, `ip` = ?, `browser` = ?, `browser_full` = ?, `type` = ?", array($u -> id, time(), $ip, $browser, $_SERVER['HTTP_USER_AGENT'], 'log_pass'));
		header("Location: /cab");
		exit();
	}
}
echo alerts::error();
$elms = array();
$elms[] = array('type' => 'title', 'value' => 'E-mail:', 'br' => true);
$elms[] = array('type' => 'text', 'name' => 'email', 'value' => TextUtils::DBFilter($email), 'br' => true);
$elms[] = array('type' => 'title', 'value' => 'Пароль:', 'br' => true);
$elms[] = array('type' => 'password', 'name' => 'pass', 'value' => null);
$elms[] = array('type' => 'checkbox', 'name' => 'in_cookies', 'value' => '1', 'text' => 'Запомнить меня', 'labels' => true);
$elms[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Войти', 'br' => true);
$smarty = new SMX();
$smarty -> assign('method', 'POST');
$smarty -> assign('action', '');
$smarty -> assign('el', $elms);
$smarty -> display("form.tpl");
doc::back("На главную", "/");
include(FOOT);
?>