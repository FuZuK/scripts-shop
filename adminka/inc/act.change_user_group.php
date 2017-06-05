<?
adminka::accessCheck('users_change_group');
$us = new Users\User(intval(@$_GET['user_id']));
if (!$us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Пользователь не найден.");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($us -> getGroup() -> level >= $u -> getGroup() -> level) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("У Вас недостаточно привилегий для смены должности этого пользователя.");
	doc::back("Назад", $set -> profile_page.$us -> id);
	include(FOOT);
}
$title .= ' - Смена должности '.$us -> login;
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$new_group_id = intval($_POST['new_group']);
	$new_group = $db -> farr("SELECT * FROM `users_groups` WHERE `id` = ?", array($new_group_id));
	if (!$new_group -> id)$error = 'Должность не найдена';
	elseif ($u -> getGroup() -> level <= $new_group -> level)$error = 'Вам запрещено выдавать ету должность!';
	else {
		if ($us -> getGroup() -> id != $new_group -> id) {
			adminka::adminsLog("Пользователи", "Должности", "Смена должности пользователя [user]".$us -> login."[/user] с \"".$us -> getGroup() -> name."\" на \"".$new_group -> name."\"");
		}
		$db -> q("UPDATE `users` SET `group` = ? WHERE `id` = ?", array($new_group -> id, $us -> id));
		header("Location: ".$set -> profile_page.$us -> id);
		exit();
	}
}
echo alerts::error();
$el = array();
$options = array();
$select_users_groups = $db -> q("SELECT * FROM `users_groups` WHERE `level` < ?", array($u -> getGroup() -> level));
while ($user_group_post = $select_users_groups -> fetch()) {
	$options[$user_group_post -> id] = $user_group_post -> name;
}
$el[] = array('type' => 'title', 'value' => 'Должность:', 'br' => true);
$el[] = array('type' => 'select', 'name' => 'new_group', 'options' => $options, 'selected' => $us -> getGroup() -> id, 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Сохранить', 'br' => true);
$sm = new SMX();
$sm -> assign('el', $el);
$sm -> display('form.tpl');
doc::back('Назад', $set -> profile_page.$us -> id);
include(FOOT);
?>