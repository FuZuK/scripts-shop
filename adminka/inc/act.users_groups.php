<?
adminka::accessCheck('edit_users_groups');
$title .= ' - Настройки должностей';
include(HEAD);
switch(@$_GET['mod']):
case 'group_edit':
if ($db -> res("SELECT COUNT(*) FROM `users_groups` WHERE `id` = ?", array(intval(@$_GET['group_id'])))) {
	$group = $db -> farr("SELECT * FROM `users_groups` WHERE `id` = ?", array(intval($_GET['group_id'])));
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$new_name = $_POST['new_name'];
		$level = intval($_POST['level']);
		if (TextUtils::length(trim($new_name)) < 1)$error = 'Введите название должности';
		elseif ($db -> res("SELECT COUNT(*) FROM `users_groups` WHERE `name` = ? AND `id` != ?", array($new_name, $group -> id)))$error = 'Должность с таким названием уже есть';
		else {
			$db -> q("UPDATE `users_groups` SET `name` = ?, `level` = ? WHERE `id` = ?", array($new_name, $level, $group -> id));
			header("Location: ?act=users_groups");
			exit();
		}
	}
	echo alerts::error();
	?>
	<form action="" method="POST" class="content">
		<span class="form_q">Название:</span><br />
		<input type="text" name="new_name" class="rad_tlr rad_blr main_inp" value="<? echo TextUtils::DBFilter($group -> name)?>"><br />
		<span class="form_q">Уровень:</span><br />
		<input type="text" name="level" class="rad_tlr rad_blr main_inp" value="<? echo TextUtils::DBFilter($group -> level)?>"><br />
		<? echo ussec::input()?>
		<input type="submit" name="sfsk" value="Сохранить" class="rad_tlr rad_blr main_sub">
	</form>
	<?doc::back("Список должностей", "?act=users_groups")?>
	<?
	include(FOOT);
}
case 'group_delete':
if ($db -> res("SELECT COUNT(*) FROM `users_groups` WHERE `id` = ?", array(intval(@$_GET['group_id'])))) {
	$group = $db -> farr("SELECT * FROM `users_groups` WHERE `id` = ?", array(intval($_GET['group_id'])));
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$db -> q("DELETE FROM `users_groups` WHERE `id` = ?", array($group -> id));
		$db -> q("DELETE FROM `users_groups_accesses_is` WHERE `id_group` = ?", array($group -> id));
		$db -> q("UPDATE `users` SET `group` = ? WHERE `group` = ?", array(0, $group -> id));
		header("Location: ?act=users_groups");
		exit();
	}
	?>
	<form action="" method="POST" class="content">
		<span class="form_q">Вы действительно хотите удалить ету должность?</span><br />
		<? echo ussec::input()?>
		<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Да, хочу">
	</form>
	<?doc::back("Отмена", "?act=users_groups")?>
	<?
	include(FOOT);
}
case 'group_add':
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$level = intval($_POST['level']);
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название должности';
	elseif ($db -> res("SELECT COUNT(*) FROM `users_groups` WHERE `name` = ?", array($name)))$error = 'Должность с таким названием уже была добавлена ранее';
	else {
		$db -> q("INSERT INTO `users_groups` (`name`, `level`) VALUES (?, ?)", array($name, $level));
		header("Location: ?act=users_groups");
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Название:</span><br />
	<input type="text" name="name" class="rad_tlr rad_blr main_inp" value=""><br />
	<span class="form_q">Уровень:</span><br />
	<input type="text" name="level" class="rad_tlr rad_blr main_inp" value="0"><br />
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" value="Добавить" class="rad_tlr rad_blr main_sub">
</form>
<?doc::back("Список должностей", "?act=users_groups")?>
<?
include(FOOT);
case 'group_accesses':
if ($db -> res("SELECT COUNT(*) FROM `users_groups` WHERE `id` = ?", array(intval($_GET['group_id'])))) {
	$group = $db -> farr("SELECT * FROM `users_groups` WHERE `id` = ?", array(intval($_GET['group_id'])));
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$db -> q("DELETE FROM `users_groups_accesses_is` WHERE `id_group` = ?", array($group -> id));
		$select_users_groups_accesses_all = $db -> q("SELECT * FROM `users_groups_accesses_all`");
		while ($users_groups_access_post = $select_users_groups_accesses_all -> fetch()) {
			if (@$_POST[$users_groups_access_post -> access]) {
				$db -> q("INSERT INTO `users_groups_accesses_is` (`id_group`, `id_access`) VALUES (?, ?)", array($group -> id, $users_groups_access_post -> id));
			}
		}
		header("Location: ?act=users_groups");
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
			<label for="<? echo $users_groups_access_post -> id?>"><input type="checkbox" name="<? echo TextUtils::escape($users_groups_access_post -> access)?>" id="<? echo $users_groups_access_post -> id?>"<? echo ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_is` WHERE `id_group` = ? AND `id_access` = ?", array($group -> id, $users_groups_access_post -> id))?" CHECKED":null)?>> <? echo TextUtils::escape($users_groups_access_post -> name)?></label>
			<?
		}
		?>
		<? echo ussec::input()?>
		<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Сохранить">
	</form>
	<?doc::back("Назад", "?act=users_groups")?>
	<?
	include(FOOT);
}
endswitch;
$select_users_groups = $db -> q("SELECT * FROM `users_groups` ORDER BY `level` DESC");
if (!$select_users_groups -> rowCount())doc::listEmpty("Нет должностей");
$items = array();
while ($group_post = $select_users_groups -> fetch()) {
	$items[] = array(
		'link' => "?act=users_groups&mod=group_accesses&group_id={$group_post -> id}", 
		'name' => TextUtils::escape($group_post -> name), 
		'counter' => $db -> res("SELECT COUNT(*) FROM `users_groups_accesses_is` WHERE `id_group` = ?", array($group_post -> id)), 
		'content' => "Уровень: {$group_post -> level}", 
		'actions' => array(
			array(
				'link' => "?act=users_groups&mod=group_edit&group_id={$group_post -> id}", 
				'name' => "Редактировать"
			), 
			array(
				'link' => "?act=users_groups&mod=group_delete&group_id={$group_post -> id}", 
				'name' => "Удалить"
			)
		)
	);
}
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$sets = array('hr' => true);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
?>
<hr>
<div class="mod">
	<? echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="?act=users_groups&mod=group_add">Добавить должность</a>
</div>
<?doc::back("В админку", "?act=index")?>
<?
include(FOOT);
?>