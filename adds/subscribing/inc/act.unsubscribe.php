<?
$subscribe = $db -> farr("SELECT * FROM `subscribers` WHERE `id` = ?", array(intval($_GET['ssid'])));
if (!@$subscribe -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Подписка не найдена.");
	doc::back("Назад", "/");
	include(FOOT);
}
if (md5($subscribe -> pass) != TextUtils::escape(@$_GET['pass'])) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Неверный пароль.");
	doc::back("Назад", "/");
	include(FOOT);
}
$title .= ' - Отписаться от оповещений';
include(HEAD);
if (isset($_POST['sfsk'])) {
	$db -> q("DELETE FROM `subscribers` WHERE `id` = ?", array($subscribe -> id));
	alerts::msg_sess("Вы успешно отменили подписку.");
	header("Location: /?");
	exit();
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Вы действительно хотите отменить подписку?</span><br />
	<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Да, хочу">
</form>
<?
doc::back("Отмена", "/");
include(FOOT);
?>