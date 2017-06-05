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
$db -> q("UPDATE `mail_conts` SET `count` = ? WHERE `id` = ?", array(0, $cont -> id));
if ($db -> res("SELECT COUNT(*) FROM `mail` WHERE `id_cont` = ? AND `id_user` = ? AND `read` = ?", array($cont -> id, $u -> id, 0))) {
	$db -> q("UPDATE `mail` SET `read` = ? WHERE `id_cont` = ? AND `id_user` = ? AND `read` = ?", array(1, $cont -> id, $u -> id, 0));
	header("Location: ?cont=".$cont -> id);
	exit();
}
if (isset($_GET['delete_mess_id']) && $db -> res("SELECT COUNT(*) FROM `mail` WHERE `id_cont` = ? AND `id` = ?", array($cont -> id, intval($_GET['delete_mess_id']))) && ussec::check_g()) {
	$db -> q("DELETE FROM `mail` WHERE `id_cont` = ? AND `id` = ?", array($cont -> id, intval($_GET['delete_mess_id'])));
	header("Location: ?cont=".$cont -> id);
	exit();
}
$title = 'Переписка с '.TextUtils::escape($us -> login);
include(HEAD);
if ($us -> id) {
	$el = array();
	// $el[] = array('type' => 'title', 'value' => 'Ваш ответ:', 'br' => true);
	$el[] = array('type' => 'textarea', 'name' => 'mess', 'value' => '', 'br' => true);
	$el[] = array('type' => 'ussec');
	$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить');
	$el[] = array('type' => 'hp_smiles');
	$el[] = array('type' => 'hp_tags');
	new SMX(array('el' => $el, 'fastSend' => true, 'action' => '/post/send/' . $us -> id), 'form.tpl');
}
$count_results = $db -> res("SELECT COUNT(*) FROM `mail` WHERE `id_cont` = ?", array($cont -> id));
$navi = new navi($count_results, '?');
$query = $db -> q("SELECT * FROM `mail` WHERE `id_cont` = ? ORDER BY `date` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($cont -> id));
$posts = array();
while ($post = $query -> fetch()) {
	$actions = array();
	$actions[] = array(
		'link' => "?delete_mess_id={$post -> id}&ussec=".ussec::get(), 
		'name' => 'Удалить'
	);
	if (!$db -> res("SELECT COUNT(*) FROM `blacklist` WHERE `id_user` = ? AND `object` = ? AND `object_type` = ?", array($u -> id, $us -> id, 1)) && $u -> id != $us -> id)$actions[] = array(
		'link' => "/cab/blist?mod=add&object_id={$us -> id}&object_type=1", 
		'name' => 'Бан'
	);
	$posts[] = array(
		'data' => $post, 
		'us' => $us, 
		'time_form' => TimeUtils::show($post -> date), 
		'msg_form' => TextUtils::show($post -> msg), 
		'actions' => $actions
	);
}
$smarty = new SMX();
$smarty -> assign("posts", $posts);
$smarty -> display("list.mail.messages.tpl");
echo $navi -> show;
?>
<hr>
<div class="mod">
	<? echo imgs::show("delete.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))?> <a href="/post/delete_cont/<? echo $cont -> id?>">Удалить контакт</a><br />
</div>
<?
doc::back("Список контактов", "/post");
include(FOOT);
?>