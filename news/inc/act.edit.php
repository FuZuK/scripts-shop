<?
Users\User::if_user('is_reg');
adminka::accessCheck('news_edit_new');
$new = $db -> farr("SELECT * FROM `news` WHERE `id` = ?", array(intval(@$_GET['new_id'])));
if (!$new -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Новость не найдена");
	doc::back("Назад", "/news");
	include(FOOT);
}
$author = new Users\User($new -> id_user);
$title .= ' - Редактировать';
include(HEAD);
$title = null;
$msg = null;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$title = $_POST['title'];
	$msg = $_POST['msg'];
	if (TextUtils::length(trim($title)) < 1)$error = 'Введите заголовок';
	elseif (TextUtils::length($title) > 50)$error = 'Заголовок слишком длинный';
	elseif (TextUtils::length(trim($msg)) < 1)$error = 'Введите текст';
	elseif (TextUtils::length($msg) > 5000)$title = 'Текст слишком длинный';
	else {
		if ($title != $new -> title) {
			adminka::adminsLog("Новости", "Записи", "Изменен заголовок новости \"".$new -> title."\" на \"[url=http://$_SERVER[HTTP_HOST]/news/read/".$new -> id."]".$title."[/url]\"");
		}
		if ($msg != $new -> msg) {
			adminka::adminsLog("Новости", "Записи", "Изменен текст новости \"[url=http://$_SERVER[HTTP_HOST]/news/read/".$new -> id."]".$new -> title."[/url]\"");
		}
		$db -> q("UPDATE `news` SET `title` = ?, `msg` = ? WHERE `id` = ?", array($title, $msg, $new -> id));
		alerts::msg_sess("Новость успешно отредактировна");
		header("Location: /news/read/".$new -> id);
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Заголовок:</span><br />
	<input type="text" name="title" class="main_inp rad_tlr rad_blr" value="<? echo TextUtils::DBFilter($new -> title);?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Текст:</span><br />
	<textarea name="msg" cols="30" rows="10" class="main_inp rad_tlr rad_blr"><? echo TextUtils::DBFilter($new -> msg);?></textarea>
	<span class="alert">Не больше 5000 символов<br /></span>
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Сохранить"><br />
</form>
<?
if (adminka::access('news_delete_new')) {
	?>
	<hr>
	<div class="mod">
		<? echo imgs::show("delete.png", array('width' => ICON_WH, 'height' => ICON_WH, 'class' => ICON_CLASS));?> <a href="/news/delete/<? echo $new -> id;?>">Удалить новость</a><br />
	</div>
	<?
}
doc::back("Назад", "/news/read/{$new -> id}");
include(FOOT);
?>