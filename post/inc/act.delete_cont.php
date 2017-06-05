<?
$cont = $db -> farr("SELECT * FROM `mail_conts` WHERE `id_user` = ? AND `id` = ?", array($u -> id, intval($_GET['cont_id'])));
if (@!$cont -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Контакт не найден.");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($cont -> id_ank);
$title .= ' - Удаление контакта';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$select_messages = $db -> q("SELECT * FROM `mail` WHERE `id_cont` = ?", array($cont -> id));
	while ($message_post = $select_messages -> fetch()) {
		$db -> q("DELETE FROM `mail` WHERE `id` = ?", array($message_post -> id));
	}
	$db -> q("DELETE FROM `mail_conts` WHERE `id` = ?", array($cont -> id));
	alerts::msg_sess("Контакт успешно удален");
	header("Location: /post");
	exit();
}
?>
<form action="" method="POST" class="content">
	<span class="form_q">Вы действительно хотите удалить выбранный контакт и всю с ним переписку?</span><br />
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Да, хочу">
</form>
<?
doc::back("Отмена", "/post/cont/<? echo $cont -> id?>");
include(FOOT);
?>