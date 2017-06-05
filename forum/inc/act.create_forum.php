<?
adminka::accessCheck('forum_add_forum');
$title .= ' - Создание подфорума';
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
		$db -> q("INSERT INTO `forum_forums` (`name`, `desc`) VALUES (?, ?)", array($name, $desc));
		$forum_id = $db -> lastInsertId();
		adminka::adminsLog("Форум", "Подфорумы", "Создан подфорум \"[url=http://$_SERVER[HTTP_HOST]/forum/f/".$forum_id."]".$name."[/url]\"");
		alerts::msg_sess("Подфорум успешно создан");
		header("Location: /forum");
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
doc::back("Назад", "/forum");
include(FOOT);
?>