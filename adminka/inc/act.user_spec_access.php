<?
adminka::accessCheck('users_spec_access');
$us = new Users\User(intval(@$_GET['user_id']));
if (!@$us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Пользователь не найден.");
	doc::back("В админку", "/adminka");
	include(FOOT);
}
$title .= ' - Отдельные привилегии '.$us -> login;
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$db -> q("DELETE FROM `users_groups_accesses_is` WHERE `id_user` = ?", array($us -> id));
	$select_users_groups_accesses_all = $db -> q("SELECT * FROM `users_groups_accesses_all`");
	while ($users_groups_access_post = $select_users_groups_accesses_all -> fetch()) {
		if (@$_POST[$users_groups_access_post -> access]) {
			$db -> q("INSERT INTO `users_groups_accesses_is` (`id_user`, `id_access`) VALUES (?, ?)", array($us -> id, $users_groups_access_post -> id));
		}
	}
	$db -> q("UPDATE `users` SET `spec_access` = ? WHERE `id` = ?", array(intval(@$_POST['spec_access']), $us -> id));
	header("Location: ".$_SERVER['REQUEST_URI']);
	exit();
}
?>
<form action="" method="POST" class="content">
	<div class="wety">
		Выберите привилегии:<br />
	</div>
	<?
	$select_users_groups_accesses_all = $db -> q("SELECT * FROM `users_groups_accesses_all` ORDER BY `name` ASC");
	while ($users_groups_access_post = $select_users_groups_accesses_all -> fetch()) {
		?>
		<label for="<? echo $users_groups_access_post -> id?>"><input type="checkbox" name="<? echo TextUtils::escape($users_groups_access_post -> access)?>" id="<? echo $users_groups_access_post -> id?>" value="1"<? echo ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_is` WHERE `id_user` = ? AND `id_access` = ?", array($us -> id, $users_groups_access_post -> id))?" CHECKED":null)?>> <? echo TextUtils::escape($users_groups_access_post -> name)?></label>
		<?
	}
	?>
	<hr class="custom">
	<label for="spec_access"><input type="checkbox" name="spec_access" id="spec_access" value="1"<? echo ($us -> spec_access?" CHECKED":null)?>> Задействовать отдельные привилегии</label>
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Сохранить">
</form>
<?
doc::back("Профиль", $set -> profile_page.$us -> id);
doc::back("В админку", "/adminka");
include(FOOT);
?>