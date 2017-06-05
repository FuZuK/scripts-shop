<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_create_poll');
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array(intval($_GET['cat_id'])));
if (!$cat -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Раздел не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
$array_polltime = array('infin' => 'Бессрочное', 'day' => '1 День', '3days' => '3 Дня', 'week' => '1 Неделю', 'month' => '1 Месяц', '3months' => '3 Месяца');
$array_polltime_num = array('infin' => 3600*24*31*12*3, 'day' => 3600*24, '3days' => 3600*24*3, 'week' => 3600*24*7, 'month' => 3600*24*31, '3months' => 3600*24*31*3);
$p = "new_poll_".$cat -> id;
if (!isset($_SESSION[$p])) {
	$_SESSION[$p] = array(
		'text' => null, 
		'vars' => array(null, null), 
		'polltime' => 'infin', 
		'multi' => 0
	);
}
if (isset($_SESSION[$p]['pinned'])) {
	header("Location: /forum/create/topic/".$cat -> id);
	if (isset($_GET['delete']) && ussec::check_g()) {
		unset($_SESSION[$p]);
		alerts::msg_sess("Опрос успешно удален");
	} else {
		alerts::msg_sess("Опрос уже прикреплен");
	}
	exit();
}
$_SESSION[$p]['count_vars'] = count($_SESSION[$p]['vars']);
$poll_new = $_SESSION[$p];
$title .= ' - Прикрипление опроса';
include(HEAD);
if (isset($_POST['add_variant']) && ussec::check_p()) {
	$_SESSION[$p]['text'] = $_POST['text'];
	if ($array_polltime[@$_POST['polltime']])$_SESSION[$p]['polltime'] = $_POST['polltime'];
	$_SESSION[$p]['multi'] = intval(@$_POST['multi']);
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['vars'][$num] = $value;
		}
	}
	$count_vars = 0;
	$count_not_empty_vars = 0;
	foreach ($_SESSION[$p]['vars'] as $key => $value) {
		$num = $key;
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	$_SESSION[$p]['count_vars'] = count($_SESSION[$p]['vars']);
	if ($poll_new['count_vars'] <= 10) {
		array_push($_SESSION[$p]['vars'], null);
	}
	header("Location: /forum/poll/new/".$cat -> id);
	exit();
}
if (isset($_POST['delete_variant']) && ussec::check_p()) {
	$_SESSION[$p]['text'] = $_POST['text'];
	if ($array_polltime[@$_POST['polltime']])$_SESSION[$p]['polltime'] = $_POST['polltime'];
	$_SESSION[$p]['multi'] = intval(@$_POST['multi']);
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['vars'][$num] = $value;
		}
	}
	$count_vars = 0;
	$count_not_empty_vars = 0;
	foreach ($_SESSION[$p]['vars'] as $key => $value) {
		$num = $key;
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	$_SESSION[$p]['count_vars'] = count($_SESSION[$p]['vars']);
	if ($poll_new['count_vars'] > 2) {
		array_pop($_SESSION[$p]['vars']);
	}
	header("Location: /forum/poll/new/".$cat -> id);
	exit();
}
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$_SESSION[$p]['text'] = $_POST['text'];
	if ($array_polltime[@$_POST['polltime']])$_SESSION[$p]['polltime'] = $_POST['polltime'];
	$_SESSION[$p]['multi'] = intval(@$_POST['multi']);
	foreach ($_POST as $key => $value) {
		if (preg_match("|^variant_([0-9]*)$|isU", $key, $num)) {
			$num = $num[1];
			$_SESSION[$p]['vars'][$num] = $value;
		}
	}
	$count_vars = 0;
	$count_not_empty_vars = 0;
	foreach ($_SESSION[$p]['vars'] as $key => $value) {
		$num = $key;
		$count_vars++;
		if (TextUtils::length(trim($value)) > 0)$count_not_empty_vars++;
	}
	$_SESSION[$p]['count_vars'] = count($_SESSION[$p]['vars']);
	if (TextUtils::length(trim($_SESSION[$p]['text'])) < 1)$error = 'Введите текст опроса';
	elseif (TextUtils::length($_SESSION[$p]['text']) > 500)$error = 'Текст опроса слишком длинный';
	elseif ($count_not_empty_vars < 2)$error = 'Нужно заполнить минимум два вариантов ответов';
	elseif ($count_not_empty_vars > 10)$error = 'Превышен лимит вариантов ответов';
	else {
		$_SESSION[$p]['pinned'] = true;
		alerts::msg_sess("Опрос успешно прикреплен");
		//unset($_SESSION[$p]);
		header("Location: /forum/create/topic/".$cat -> id);
		exit();
	}
}
echo alerts::error();
$poll_new = $_SESSION[$p];
?>
<form action="" method="POST" class="content">
	<span class="form_q">Опрос:</span><br />
	<textarea name="text" class="main_inp rad_tlr rad_blr" cols="30" rows="10"><? echo TextUtils::DBFilter($poll_new['text'])?></textarea>
	<span class="alert">Не более 500-ти символов<br /></span>
	<?
	$lp = 0;
	foreach ($poll_new['vars'] as $key => $value) {
		$lp++;
		?>
		<input type='text' style='width: 95%' name='variant_<? echo $key?>' class='main_inp rad_tlr rad_blr' value='<? echo TextUtils::DBFilter($value)?>'><br />
		<?
	}
	echo ($lp <= 9?"<input style='width: ".($lp>2?49:98)."%' type='submit' name='add_variant' class='main_sub rad_tlr rad_blr' value='Добавить' />":null).($lp > 2?"<input style='width: ".($lp<=9?49:98)."%' type='submit' name='delete_variant' class='main_sub rad_tlr rad_blr' value='Убрать' />":null);
	echo ussec::input();
	?>
	<span class="form_q">Окончание через: </span>
	<select name="polltime" class="main_inp rad_tlr rad_blr">
		<?
		foreach ($array_polltime as $key => $value) {
			?>
			<option value="<? echo $key?>"<? echo ($poll_new['polltime'] == $key?" SELECTED":null)?>><? echo $value?></option>
			<?
		}
		?>
	</select>
	<label for="multi_1"><input type="checkbox" name="multi" id="multi_1" value="1"<? echo ($poll_new['multi']?" CHECKED":null)?>> Выбор нескольких вариантов</label>
	<input style='width: 98%' type='submit' name='sfsk' class='main_sub rad_tlr rad_blr' value='Сохранить' />
</form>
<?
doc::back("Назад", "/forum/create/topic/{$cat -> id}");
include(FOOT);
?>