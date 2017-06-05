<?
$ticket = $db -> farr("SELECT * FROM `tickets` WHERE `id` = ?", array(intval($_GET['ticket_id'])));
if (!@$ticket -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Тикет не найден.");
	?>
	<?doc::back("Назад", "/support")?>
	<?
	include(FOOT);
}
$us = new Users\User($ticket -> id_user);
if ($us -> id != $u -> id && !adminka::access('tickets_open_ticket')) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш тикет!");
	?>
	<?doc::back("Назад", "/support")?>
	<?
	include(FOOT);
}
if ($ticket -> opened == 1) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Тикет уже открыт!");
	?>
	<?doc::back("Назад", "/support")?>
	<?
	include(FOOT);
}
$title .= ' - '.TextUtils::escape(TextUtils::cut($ticket -> title, 20));
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	if ($us -> id != $u -> id) {
		adminka::adminsLog("Тикеты", "Тикеты", "Открыт тикет \"[url=http://$_SERVER[HTTP_HOST]/support/ticket/".$ticket -> id."]".$ticket -> title."[/url]\" пользователя [user]".$us -> login."[/user]");
	}
	$db -> q("UPDATE `tickets` SET `opened` = ? WHERE `id` = ?", array(1, $ticket -> id));
	alerts::msg_sess("Тикет успешно открыт");
	header("Location: /support/ticket/".$ticket -> id);
	exit();
}
?>
<form action="" class="content" method="POST">
	<span class="form_q">Вы действительно хотите открыть выбранный тикет?</span><br />
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Да, хочу"><br />
</form>
<?doc::back("Назад", "/support/ticket/{$ticket -> id}")?>
<?
include(FOOT);
?>