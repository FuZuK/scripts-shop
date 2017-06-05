<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_edit_cat');
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array(intval($_GET['cat_id'])));
if (!$cat -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Раздел не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
$title .= ' - Редактирование раздела';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
	elseif (TextUtils::length($name) > 50)$error = 'Название слишком длинное';
	elseif (TextUtils::length($desc) > 500)$error = 'Описание слишком длинное';
	else {
		if ($name != $cat -> name) {
			adminka::adminsLog("Форум", "Разделы", "Переименован раздел \"".$cat -> name."\" в \"[url=http://$_SERVER[HTTP_HOST]/forum/c/".$cat -> id."]".$name."[/url]\"");
		}
		if ($desc != $cat -> desc) {
			adminka::adminsLog("Форум", "Разделы", "Изменено описание раздела \"[url=http://$_SERVER[HTTP_HOST]/forum/c/".$cat -> id."]".$cat -> name."[/url]\"");
		}
		$db -> q("UPDATE `forum_cats` SET `name` = ?, `desc` = ? WHERE `id` = ?", array($name, $desc, $cat -> id));
		alerts::msg_sess("Раздел успешно отредактирован");
		header("Location: /forum/c/".$cat -> id);
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Название:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="name" value="<? echo TextUtils::DBFilter($cat -> name);?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Описание:</span><br />
	<textarea name="desc" class="main_inp rad_tlr rad_blr"><? echo TextUtils::DBFilter($cat -> desc);?></textarea>
	<span class="alert">Не больше 500-ти символов<br /></span>
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Сохранить"> <a href="/smiles" class="hp_bb">Смайлы</a> <a href="/tags" class="hp_bb">Теги</a>
</form>
<hr>
<div class="mod">
	<? echo imgs::show("delete.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/delete/cat/<? echo $cat -> id?>">Удалить раздел</a><br />
</div>
<?
doc::back("Назад", "/forum/c/{$cat -> id}");
include(FOOT);
?>