<?
$title .= ' - Новый тикет';
include(HEAD);
$title2 = null;
$msg = null;
$type = 0;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$title2 = $_POST['title'];
	$type = intval($_POST['type']);
	$msg = $_POST['msg'];
	if (TextUtils::length(trim($title2)) < 1)$error = 'Короткий заголовок';
	elseif (TextUtils::length($title2) > 50)$error = 'Заголовок слишком длинный';
	elseif (TextUtils::length(trim($msg)) < 20)$error = 'Короткое сообщение';
	elseif (TextUtils::length($msg) > 1024)$error = 'Сообщение слишком длинное';
	elseif (!in_array($type, array(0, 1)))$error = 'Неверный тип тикета';
	else {
		$db -> q("INSERT INTO `tickets` (`title`, `msg`, `time`, `id_user`, `opened`, `type`) VALUES (?, ?, ?, ?, ?, ?)", array($title2, $msg, time(), $u -> id, 1, $type));
		alerts::msg_sess("Тикет успешно создан. Скоро Вы получите ответ на свой вопрос.");
		header("Location: /support");
		exit();
	}
}
echo alerts::error();
?>
<form action="" class="content" method="POST">
	<span class="form_q">Тема:</span><br />
	<input type="text" class="rad_tlr rad_blr main_inp" name="title" value="<? echo TextUtils::DBFilter($title2)?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Кому:</span><br />
	<select name="type" class="rad_tlr rad_blr main_inp">
		<option value="0"<? echo ($type == 0?" SELECTED":null)?>>Администратору</option>
		<option value="1"<? echo ($type == 1?" SELECTED":null)?>>Консультанту</option>
	</select><br />
	<span class="form_q">Сообщение:</span><br />
	<textarea name="msg" class="rad_tlr rad_blr main_inp" cols="30" rows="10"><? echo TextUtils::DBFilter($msg)?></textarea>
	<span class="alert">Не меньше 20-ти и не больше 1000 символов<br /></span>
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Добавить"><br />
</form>
<?doc::back("Назад", "/support")?>
<?
include(FOOT);
?>