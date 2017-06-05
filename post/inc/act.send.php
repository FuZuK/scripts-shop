<?
$us = new Users\User(intval($_GET['user_id']));
if (@!$us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Пользователь не найден.");
	doc::back("Назад", "/post");
	include(FOOT);
}
$title .= ' - Написать письмо';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$mess = $_POST['mess'];
	if (TextUtils::length(trim($mess)) < 1)$error = 'Введите сообщение';
	elseif (TextUtils::length($mess) > 1024)$error = 'Сообщение слишком длинное';
	elseif (blist::in($u -> id, $us -> id, 1))$error = 'Этот пользователь находится в Вашем Черном списке и Вы не сможете ему написать';
	elseif (blist::in($us -> id, $u -> id, 1))$error = 'Этот пользователь добавил Вас в свой Черный список и Вы не сможете ему написать';
	else {
		$cont_id = mailing::send_mess($u -> id, $us -> id, $mess);
		Users\Notifications::send('new_mess', $us -> id, 'У вас новое личное сообщение на '.SITE_NAME);
		header("Location: /post/cont/$cont_id");
		alerts::msg_sess("Сообщение успешно отправлено");
		exit();
	}
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => "<span class='ank_q'>Кому:</span> <span class='form_a'>" . $us -> login(0) . "</span>", 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'mess', 'value' => '', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить');
$el[] = array('type' => 'hp_smiles');
$el[] = array('type' => 'hp_tags');
new SMX(array('el' => $el, 'fastSend' => true, 'action' => '/post/send/' . $us -> id), 'form.tpl');
Doc::back("Назад", "/post");
include(FOOT);
?>