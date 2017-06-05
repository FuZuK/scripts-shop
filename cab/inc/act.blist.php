<?
$title = 'Черный список';
switch (TextUtils::escape(@$_GET['mod'])):
case 'add':
$object_id = floatval($_GET['object_id']);
$object_type = intval($_GET['object_type']);
$array_types = array(
	1 => 'пользователя',
	2 => 'WMID'
);
$array_blinks = array(
	1 => $set -> profile_page.intval($_GET['object_id']),
	2 => "/cab/my_buss"
);
if (!$array_types[$object_type]) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Неверный тип обьекта.");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($object_type == 1 && !$db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ?", array($object_id))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Обьект не найден.");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($object_type == 1 && $object_id == $u -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Нельзя добавлять себя в свой Черный список.");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($db -> res("SELECT COUNT(*) FROM `blacklist` WHERE `id_user` = ? AND `object` = ? AND `object_type` = ?", array($u -> id, $object_id, $object_type))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Обьект уже был добавлен в Черный список ранее.");
	doc::back("Назад", "/");
	include(FOOT);
}
$title .= ' - Добавить '.$array_types[$object_type];
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$db -> q("INSERT INTO `blacklist` (`id_user`, `object_type`, `object`, `time`) VALUES (?, ?, ?, ?)", array($u -> id, $object_type, $object_id, time()));
	alerts::msg_sess("Обьект успешно добавлен в Черный список");
	header("Location: ".$array_blinks[$object_type]);
	exit();
}
?>
<form action="" method="POST" class="content">
	<?
	if ($object_type == 1) {
		$ank = new Users\User ($object_id);
		?>Пользователь: <? echo $ank -> login(1);
	} elseif ($object_type == 2) {
		?>WMID: <a href="https://passport.webmoney.ru/asp/CertView.asp?wmid=<? echo $object_id?>"><? echo $object_id?></a><?
	}
	?>
	<br />
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Добавить"><br />
</form>
<?doc::back("Назад", $array_blinks[$object_type])?>
<?
break;
case 'delete':
$post = $db -> farr("SELECT * FROM `blacklist` WHERE `id` = ? AND `id_user` = ?", array(intval(@$_GET['pid']), $u -> id));
if (!@$post -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Обьект не найден.");
	doc::back("Назад", "/cab/blist");
	include(FOOT);
	
}
if (ussec::check_g()) {
	$db -> q("DELETE FROM `blacklist` WHERE `id` = ? AND `id_user` = ?", array(intval(@$_GET['pid']), $u -> id));
	header("Location: /cab/blist");
	alerts::msg_sess("Обьект удален из Черного списка");
	exit();
}
break;
default:
	include(HEAD);
	$cr = $db -> res("SELECT COUNT(*) FROM `blacklist` WHERE `id_user` = ?", array($u -> id));
	if (!$cr)doc::listEmpty("Список пуст");
	$navi = new navi($cr, '?');
	$q = $db -> q("SELECT * FROM `blacklist` WHERE `id_user` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($u -> id));
	$items = array();
	while ($post = $q -> fetch()) {
		$actions = array(
			array(
				'link' => "?mod=delete&pid=<{$post -> id}&".ussec::link(), 
				'name' => "Удалить"
			)
		);
		if ($post -> object_type == 1) {
			$ank = new Users\User($post -> object);
			$content = "Пользователь: {$ank -> login(1)}<br />";
		} elseif ($post -> object_type == 2) {
			$content = "WMID: <a href='https://passport.webmoney.ru/asp/CertView.asp?wmid={$post -> object}'>{$post -> object}</a><br />";
		}
		$content .= "Добавлен: <span class='time_show'>".TimeUtils::show($post -> time)."</span>";
		$items[] = array(
			'content' => $content, 
			'actions' => $actions
		);
	}
	new SMX(array('list_items' => $items, 'sets' => array('hr' => true)), 'list.items.tpl');
	echo $navi -> show;
	doc::back("В кабинет", "/cab");
	include(FOOT);
	break;
endswitch;
?>