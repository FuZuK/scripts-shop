<?
adminka::accessCheck('edit_users_groups_accesses');
$title .= ' - Редактирование привилегий';
include(HEAD);
$cats_array = array();
$q = $db -> q("SELECT * FROM `users_groups_accesses_all`");
while ($post = $q -> fetch()) {
	$new_cat = explode(' - ', $post -> name);
	$new_cat_name = $new_cat[0];
	if (!in_array($new_cat_name, $cats_array))$cats_array[] = $new_cat_name;
}
sort($cats_array, SORT_STRING);
switch (@$_GET['mod']):
case 'access_edit':
if ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `id` = ?", array(intval(@$_GET['access_id'])))) {
	$access = $db -> farr("SELECT * FROM `users_groups_accesses_all` WHERE `id` = ?", array(intval(@$_GET['access_id'])));
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$access_name = $_POST['access'];
		$name = $_POST['name'];
		if (TextUtils::length(trim($access_name)) < 1)$error = 'Введите привилегий';
		elseif ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `access` = ? AND `id` != ?", array($access_name, $access -> id)))$error = 'Такой привилегий уже есть';
		elseif (TextUtils::length(trim($name)) < 1)$error = 'Введите название привилегия';
		elseif ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `name` = ? AND `id` != ?", array($name, $access -> id)))$error = 'Привилегий с таким названием уже есть';
		else {
			$db -> q("UPDATE `users_groups_accesses_all` SET `access` = ?, `name` = ? WHERE `id` = ?", array($access_name, $name, $access -> id));
			header("Location: ?act=users_groups_accesses");
			exit();
		}
	}
	echo alerts::error();
	?>
	<form action="" method="POST" class="content">
		<span class="form_q">Привилегия:</span><br />
		<input type="text" name="access" class="rad_tlr rad_blr main_inp" value="<? echo TextUtils::DBFilter($access -> access)?>"><br />
		<span class="form_q">Название:</span><br />
		<input type="text" name="name" class="rad_tlr rad_blr main_inp" value="<? echo TextUtils::DBFilter($access -> name)?>"><br />
		<? echo ussec::input()?>
		<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Сохранить"><br />
	</form>
	<?doc::back("Список привилегий", "?act=users_groups_accesses")?>
	<?
	include(FOOT);
}
case 'access_delete':
if ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `id` = ?", array(intval(@$_GET['access_id'])))) {
	$access = $db -> farr("SELECT * FROM `users_groups_accesses_all` WHERE `id` = ?", array(intval(@$_GET['access_id'])));
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$db -> q("DELETE FROM `users_groups_accesses_all` WHERE `id` = ?", array($access -> id));
		$db -> q("DELETE FROM `users_groups_accesses_is` WHERE `id_access` = ?", array($access -> id));
		header("Location: ?act=users_groups_accesses");
		exit();
	}
	echo alerts::error();
	?>
	<form action="" method="POST" class="content">
		<span class="form_q">Вы действительно хотите удалить этот привилегий?</span><br />
		<? echo ussec::input()?>
		<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Да, хочу"><br />
	</form>
	<?doc::back("Отмена", "?act=users_groups_accesses")?>
	<?
	include(FOOT);
}
case 'access_add':
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$access_name = $_POST['access'];
	$name = $_POST['name'];
	if (TextUtils::length(trim($access_name)) < 1)$error = 'Введите привилегий';
	elseif ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `access` = ?", array($access_name)))$error = 'Такой привилегий уже есть';
	elseif (TextUtils::length(trim($name)) < 1)$error = 'Введите название привилегия';
	elseif ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `name` = ?", array($name)))$error = 'Привилегий с таким названием уже есть';
	else {
		$db -> q("INSERT INTO `users_groups_accesses_all` (`access`, `name`) VALUES (?, ?)", array($access_name, $name));
		header("Location: ?act=users_groups_accesses");
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Привилегия:</span><br />
	<input type="text" name="access" class="rad_tlr rad_blr main_inp" value=""><br />
	<span class="form_q">Название:</span><br />
	<input type="text" name="name" class="rad_tlr rad_blr main_inp" value=""><br />
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Сохранить"><br />
</form>
<?doc::back("Список привилегий", "?act=users_groups_accesses")?>
<?
include(FOOT);
endswitch;
if (isset($_GET['cat']) && in_array($_GET['cat'], $cats_array)) {
	$cat = TextUtils::DBFilter($_GET['cat']);
	$count_results = $db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `name` LIKE ?", array("%$cat - %"));
	if (!$count_results)doc::listEmpty("Список привилегий пуст");
	$navi = new navi($count_results, '?act=users_groups_accesses&cat='.$cat.'&');
	$select_users_groups_accesses = $db -> q("SELECT * FROM `users_groups_accesses_all` WHERE `name` LIKE ? ORDER BY `name` ASC LIMIT ".$navi -> start.", ".$set -> results_on_page, array("%$cat - %"));
	$items = array();
	while ($users_groups_access = $select_users_groups_accesses -> fetch()) {
		$actions = array(
			array(
				'link' => "?act=users_groups_accesses&mod=access_edit&access_id={$users_groups_access -> id}", 
				'name' => 'Редактировать'
			), 
			array(
				'link' => "?act=users_groups_accesses&mod=access_delete&access_id={$users_groups_access -> id}", 
				'name' => 'Удалить'
			)
		);
		$items[] = array(
			'name' =>  TextUtils::DBFilter($users_groups_access -> name).' ('.TextUtils::DBFilter($users_groups_access -> access).')', 
			'actions' => $actions
		);
	}
	$sets = array(
		'hr' => true
	);
	$smarty = new SMX();
	$smarty -> assign("sets", $sets);
	$smarty -> assign("list_items", $items);
	$smarty -> display("list.items.tpl");
	echo $navi -> show;
	?>
	<hr>
	<div class="mod">
		<? echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="?act=users_groups_accesses&mod=access_add">Добавить привилегий</a>
	</div>
	<?doc::back("Список категорий", "?act=users_groups_accesses")?>
	<?
	include(FOOT);
}
$items = array();
foreach ($cats_array as $value) {
	$items[] = array(
		'link' => "?act=users_groups_accesses&cat=".TextUtils::DBFilter($value), 
		'name' => TextUtils::DBFilter($value)
	);
}
$sets = array(
	'hr' => true
);
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
?>
<hr>
<div class="mod">
	<? echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="?act=users_groups_accesses&mod=access_add">Добавить привилегий</a>
</div>
<?doc::back("В админку", "?act=index")?>
<?
include(FOOT);
?>