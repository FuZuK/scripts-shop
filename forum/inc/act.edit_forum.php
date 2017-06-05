<?
adminka::accessCheck('forum_edit_forum');
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array(intval($_GET['forum_id'])));
if (!$forum -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Подфорум не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$title .= ' - Редактирование подфорума';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
	elseif (TextUtils::length($name) > 50)$error = 'Название слишком длинное';
	elseif (TextUtils::length($desc) > 500)$error = 'Описание слишком длинное';
	else {
		if ($name != $forum -> name) {
			adminka::adminsLog("Форум", "Подфорумы", "Переименован подфорум \"".$forum -> name."\" в \"[url=http://$_SERVER[HTTP_HOST]/forum/f/".$forum -> id."]".$name."[/url]\"");
		}
		if ($desc != $forum -> desc) {
			adminka::adminsLog("Форум", "Подфорумы", "Изменено описание подфорума \"[url=http://$_SERVER[HTTP_HOST]/forum/f/".$forum -> id."]".$forum -> name."[/url]\"");
		}
		$db -> q("UPDATE `forum_forums` SET `name` = ?, `desc` = ? WHERE `id` = ?", array($name, $desc, $forum -> id));
		alerts::msg_sess("Подфорум успешно отредактирован");
		header("Location: /forum/f/".$forum -> id);
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Название:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="name" value="<? echo TextUtils::DBFilter($forum -> name);?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Описание:</span><br />
	<textarea name="desc" class="main_inp rad_tlr rad_blr"><? echo TextUtils::DBFilter($forum -> desc);?></textarea>
	<span class="alert">Не больше 500-ти символов<br /></span>
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Сохранить"> <a href="/smiles" class="hp_bb">Смайлы</a> <a href="/tags" class="hp_bb">Теги</a>
</form>
<?
if (adminka::access('forum_delete_forum')) {
	?>
	<hr>
	<div class="mod">
		<? echo imgs::show("delete.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/delete/forum/<? echo $forum -> id?>">Удалить подфорум</a><br />
	</div>
	<?
}
doc::back("Назад", "/forum/f/{$forum -> id}");
include(FOOT);
?>