<?
Users\User::if_user('is_reg');
adminka::accessCheck('news_delete_new');
$new = $db -> farr("SELECT * FROM `news` WHERE `id` = ?", array(intval(@$_GET['new_id'])));
if (!$new -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Новость не найдена");
	doc::back("Назад", "/news");
	include(FOOT);
}
$author = new Users\User($new -> id_user);
$title .= ' - Удалить';
include(HEAD);
$title = null;
$msg = null;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	adminka::adminsLog("Новости", "Записи", "Удалена новость \"[url=http://$_SERVER[HTTP_HOST]/news/read/".$new -> id."]".$new -> title."[/url]\"");
	$db -> q("DELETE FROM `news` WHERE `id` = ?", array($new -> id));
	$db -> q("DELETE FROM `news_comms` WHERE `id_new` = ?", array($new -> id));
	alerts::msg_sess("Новость успешно удалена");
	header("Location: /news");
	exit();
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Вы действительно хотите удалить эту новость?</span><br />
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Да, хочу"><br />
</form>
<?
doc::back("Назад", "/news/edit/{$new -> id}");
include(FOOT);
?>