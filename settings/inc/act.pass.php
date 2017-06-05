<?
$title = 'Сменить пароль';
include_once(HEAD);
if (TextUtils::is_empty($u -> info -> keyword)) {
	alerts::msg_sess("Для начала укажите свое ключевое слово");
	$_SESSION['i_wont_change_pass_but_empty_keyword'] = true;
	header("Location: /settings/keyword");
	exit();
}
if (isset($_POST['save']) && ussec::check_p()) {
	$pass_old = $_POST['pass_old'];
	$pass_new = $_POST['pass_new'];
	$keyword = $_POST['keyword'];
	if (md5($pass_old) != $u -> pass)$error = 'Неверный старый пароль';
	elseif (TextUtils::lenNtrim($pass_new) < 6)$error = 'Новый пароль слишком короткий';
	elseif (TextUtils::length($pass_new) > 32)$error = 'Новый пароль слишком длинный';
	elseif (TextUtils::lenNtrim($keyword) < 1)$error = 'Укажите ключевое слово';
	elseif ($keyword != $u -> info -> keyword)$error = 'Неверное ключевое слово';
	else {
		$u -> setData('pass', md5($pass_new));
		alerts::msg_sess("Пароль успешно изменен");
		header("Location: /settings");
		exit();
	}
}
echo alerts::error();
new SMX(
	array('el' => array(
		array('type' => 'title', 'value' => 'Старый пароль:', 'br' => true), 
		array('type' => 'text', 'name' => 'pass_old', 'br' => true), 
		array('type' => 'title', 'value' => 'Новый пароль:', 'br' => true), 
		array('type' => 'text', 'name' => 'pass_new', 'br' => true, 'alert' => 'Не меньше 6-ти и не больше 32-х символов'), 
		array('type' => 'title', 'value' => 'Ваше ключевое слово:', 'br' => true), 
		array('type' => 'text', 'name' => 'keyword', 'br' => true), 
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
	)), 'form.tpl'
);
Doc::back("Настройки", "/settings");
?>