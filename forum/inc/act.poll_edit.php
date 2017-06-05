<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_edit_poll');
$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array(intval($_GET['topic_id'])));
if (!$topic -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Топик не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$author = new Users\User($topic -> id_user);
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array($topic -> id_cat));
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
if (!$topic -> poll) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("К топику опрос не прикреплен.");
	doc::back("Назад", "/forum/t/<? echo $topic -> id?>");
	include(FOOT);
}
$p = "edit_poll_".$topic -> id;
$select_vars = $db -> q("SELECT * FROM `forum_poll_vars` WHERE `id_topic` = ? ORDER BY `num` ASC", array($topic -> id));
$array_old_vars = array();
while ($variant = $select_vars -> fetch()) {
	$array_old_vars[$variant -> id] = $variant -> variant;
}
if (!isset($_SESSION[$p])) {
	$_SESSION[$p] = array(
		'text' => $topic -> poll_text, 
		'vars' => array(), 
		'default_vars' => $array_old_vars, 
		'clear' => 0
	);
}
$_SESSION[$p]['count_vars'] = count($_SESSION[$p]['vars']) + count($_SESSION[$p]['default_vars']);
$poll_edit = $_SESSION[$p];
$title .= ' - Редактирование опроса';
include(HEAD);
if (isset($_POST['add_variant']) && ussec::check_p()) {
	$_SESSION[$p]['text'] = $_POST['text'];
	$_SESSION[$p]['clear'] = intval(@$_POST['clear']);
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['vars'][$num] = $value;
		}
	}
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_d_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['default_vars'][$num] = $value;
		}
	}
	$count_vars = 0;
	$count_not_empty_vars = 0;
	foreach ($_SESSION[$p]['vars'] as $key => $value) {
		$num = $key;
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	foreach ($_SESSION[$p]['default_vars'] as $key => $value) {
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	$_SESSION[$p]['count_vars'] = count($_SESSION[$p]['vars']) + count($_SESSION[$p]['default_vars']);
	if ($poll_edit['count_vars'] <= 10) {
		array_push($_SESSION[$p]['vars'], null);
	}
	header("Location: /forum/poll/edit/".$topic -> id);
	exit();
}
if (isset($_POST['delete_variant']) && ussec::check_p()) {
	$_SESSION[$p]['text'] = $_POST['text'];
	$_SESSION[$p]['clear'] = intval(@$_POST['clear']);
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['vars'][$num] = $value;
		}
	}
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_d_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['default_vars'][$num] = $value;
		}
	}
	$count_vars = 0;
	$count_not_empty_vars = 0;
	foreach ($_SESSION[$p]['vars'] as $key => $value) {
		$num = $key;
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	foreach ($_SESSION[$p]['default_vars'] as $key => $value) {
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	$_SESSION[$p]['count_vars'] = count($_SESSION[$p]['vars']) + count($_SESSION[$p]['default_vars']);
	if ($poll_edit['count_vars'] > 2) {
		array_pop($_SESSION[$p][(count($_SESSION[$p]['vars'])?'vars':'default_vars')]);
	}
	header("Location: /forum/poll/edit/".$topic -> id);
	exit();
}
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$_SESSION[$p]['text'] = $_POST['text'];
	$_SESSION[$p]['clear'] = intval(@$_POST['clear']);
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['vars'][$num] = $value;
		}
	}
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_d_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['default_vars'][$num] = $value;
		}
	}
	$count_vars = 0;
	$count_not_empty_vars = 0;
	foreach ($_SESSION[$p]['vars'] as $key => $value) {
		$num = $key;
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	foreach ($_SESSION[$p]['default_vars'] as $key => $value) {
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	if (TextUtils::length(trim($_SESSION[$p]['text'])) < 1)$error = 'Введите текст опроса';
	elseif (TextUtils::length($_SESSION[$p]['text']) > 500)$error = 'Текст опроса слишком длинный';
	elseif ($count_not_empty_vars < 2)$error = 'Нужно заполнить минимум два вариантов ответов';
	elseif ($count_not_empty_vars > 10)$error = 'Превышен лимит вариантов ответов';
	else {
		if ($_SESSION[$p]['clear'])$db -> q("DELETE FROM `forum_poll_votes` WHERE `id_topic` = ?", array($topic -> id));
		$db -> q("UPDATE `forum_topics` SET `poll_text` = ? WHERE `id` = ?", array($_SESSION[$p]['text'], $topic -> id));
		$num = 0;
		$select_vars = $db -> q("SELECT * FROM `forum_poll_vars` WHERE `id_topic` = ? ORDER BY `num` ASC", array($topic -> id));
		while ($variant = $select_vars -> fetch()) {
			if (isset($_SESSION[$p]['default_vars'][$variant -> id]) && TextUtils::length(trim($_SESSION[$p]['default_vars'][$variant -> id]))) {
				$num++;
				$db -> q("UPDATE `forum_poll_vars` SET `variant` = ?, `num` = ? WHERE `id` = ? AND `id_topic` = ?", array($_SESSION[$p]['default_vars'][$variant -> id], $num, $variant -> id, $topic -> id));
			} else {
				$db -> q("DELETE FROM `forum_poll_vars` WHERE `id` = ? AND `id_topic` = ?", array($variant -> id, $topic -> id));
				$db -> q("DELETE FROM `forum_poll_votes` WHERE `id_var` = ?", array($variant -> id));
			}
		}
		foreach ($_SESSION[$p]['vars'] as $key => $value) {
			if (TextUtils::length(trim($value))) {
				$num++;
				$db -> q("INSERT INTO `forum_poll_vars` (`id_topic`, `variant`, `num`) VALUES (?, ?, ?)", array($topic -> id, $value, $num));
			}
		}
		adminka::adminsLog("Форум", "Опросы", "Отредактирован опрос к топику \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$topic -> them."[/url]\"");
		alerts::msg_sess("Опрос успешно отредактирован");
		unset($_SESSION[$p]);
		header("Location: /forum/t/".$topic -> id);
		exit();
	}
}
if (isset($_POST['delete']) && ussec::check_p()) {
	adminka::accessCheck('forum_delete_poll');
	adminka::adminsLog("Форум", "Опросы", "Удален опрос к топику \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$topic -> them."[/url]\"");
	$db -> q("DELETE FROM `forum_poll_vars` WHERE `id_topic` = ?", array($topic -> id));
	$db -> q("DELETE FROM `forum_poll_votes` WHERE `id_topic` = ?", array($topic -> id));
	$db -> q("UPDATE `forum_topics` SET `poll` = ? WHERE `id` = ?", array(0, $topic -> id));
	alerts::msg_sess("Опрос успешно удален");
	unset($_SESSION[$p]);
	header("Location: /forum/t/".$topic -> id);
	exit();
}
echo alerts::error();
$poll_edit = $_SESSION[$p];
?>
<form action="" method="POST" class="content">
	<span class="form_q">Опрос:</span><br />
	<textarea name="text" class="main_inp rad_tlr rad_blr" cols="30" rows="10"><? echo TextUtils::DBFilter($poll_edit['text'])?></textarea>
	<span class="alert">Не более 500-ти символов<br /></span>
	<?
	$lp = 0;
	foreach ($poll_edit['default_vars'] as $key => $value) {
		$lp++;
		?>
		<input type='text' style='width: 95%' name='variant_d_<? echo $key?>' class='main_inp rad_tlr rad_blr' value='<? echo TextUtils::DBFilter($value)?>'><br />
		<?
	}
	foreach ($poll_edit['vars'] as $key => $value) {
		$lp++;
		?>
		<input type='text' style='width: 95%' name='variant_<? echo $key?>' class='main_inp rad_tlr rad_blr' value='<? echo TextUtils::DBFilter($value)?>'><br />
		<?
	}
	echo ($lp <= 9?"<input style='width: ".($lp>2?49:98)."%' type='submit' name='add_variant' class='main_sub rad_tlr rad_blr' value='Добавить' />":null).($lp > 2?"<input style='width: ".($lp<=9?49:98)."%' type='submit' name='delete_variant' class='main_sub rad_tlr rad_blr' value='Убрать' />":null);
	echo ussec::input();
	?>
	<label for="clear_1"><input type="checkbox" name="clear" id="clear_1" value="1"<? echo ($poll_edit['clear']?" CHECKED":null)?>> Очистить результаты</label>
	<input style='width: <? echo (adminka::access('forum_delete_poll')?49:98)?>%' type='submit' name='sfsk' class='main_sub rad_tlr rad_blr' value='Сохранить' />
	<?
	if (adminka::access('forum_delete_poll')) {
		?>
		<input style='width: 49%' type='submit' name='delete' class='main_sub rad_tlr rad_blr' value='Удалить' />
		<?
	}
	?>
</form>
<?
doc::back("Назад", "/forum/t/{$topic -> id}");
include(FOOT);
?>