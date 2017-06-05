<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_add_cat');
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array(intval($_GET['forum_id'])));
if (!$forum -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Подфорум не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$title .= ' - Создание раздела';
include(HEAD);
$name = null;
$desc = null;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
	elseif (TextUtils::length($name) > 50)$error = 'Название слишком длинное';
	elseif (TextUtils::length($desc) > 500)$error = 'Описание слишком длинное';
	else {
		$db -> q("INSERT INTO `forum_cats` (`name`, `desc`, `id_forum`) VALUES (?, ?, ?)", array($name, $desc, $forum -> id));
		$cat_id = $db -> lastInsertId();
		adminka::adminsLog("Форум", "Разделы", "Создан раздел \"[url=http://$_SERVER[HTTP_HOST]/forum/c/".$cat_id."]".$name."[/url]\"");
		alerts::msg_sess("Раздел успешно создан");
		header("Location: /forum/f/".$forum -> id);
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Название:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="name" value="<? echo TextUtils::DBFilter($name);?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Описание:</span><br />
	<textarea name="desc" class="main_inp rad_tlr rad_blr"><? echo TextUtils::DBFilter($desc);?></textarea>
	<span class="alert">Не больше 500-ти символов<br /></span>
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Создать"> <a href="/smiles" class="hp_bb">Смайлы</a> <a href="/tags" class="hp_bb">Теги</a>
</form>
<?
doc::back("Назад", "/forum/f/{$forum -> id}");
include(FOOT);
?>