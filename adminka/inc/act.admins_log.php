<?
adminka::accessCheck('adminka_log_read');
$title .= ' - Действия администрации';
include(HEAD);


if (isset($_GET['user_id'])) {
	$ank = new Users\User(intval($_GET['user_id']));
}
if (@!$ank -> id || $ank -> id != $u -> id && $ank -> getGroup() -> level >= $u -> getGroup() -> level)unset($ank);
$adm_log_c_all = $db -> res("SELECT COUNT(*) FROM `admins_log`".(isset($ank)?" WHERE `id_user` = '".$ank -> id."'":null));
$mes = mktime(0, 0, 0, date('m')-1); // время месяц назад
$adm_log_c_mes = $db -> res("SELECT COUNT(*) FROM `admins_log` WHERE `time` > ?".(isset($ank)?" AND `id_user` = '".$ank -> id."'":null), array($mes));
?>
<div class="content">
	<span class="ank_q">Вся активность:</span> <span class="ank_a"><? echo $adm_log_c_all?></span><br />
	<span class="ank_q">Активность за месяц:</span> <span class="ank_a"><? echo $adm_log_c_mes?></span><br />
</div>
<hr>
<?
if (isset($_GET['mod_id']) && isset($_GET['act_id'])) {
	$mod =$db -> farr("SELECT * FROM `admins_log_mod` WHERE `id` = ? LIMIT 1", array(intval($_GET['mod_id'])));
	$act = $db -> farr("SELECT * FROM `admins_log_act` WHERE `id` = ? LIMIT 1", array(intval($_GET['act_id'])));
	$count_results = $db -> res("SELECT COUNT(*) FROM `admins_log` WHERE `id_mod` = ? AND `id_act` = ?".(isset($ank)?" AND `id_user` = '".$ank -> id."'":null), array($mod -> id, $act -> id));
	if (!$count_results)doc::listEmpty("Нет действий");
	$navi = new navi($count_results, "?act=admins_log&mod_id=".$mod -> id."&act_id=".$act -> id.(isset($ank)?"&user_id=".$ank -> id:null) . "&");
	$select_admins_log = $db -> q("SELECT * FROM `admins_log` WHERE `id_mod` = ? AND `id_act` = ?".(isset($ank)?" AND `id_user` = '".$ank -> id."'":null)." ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($mod -> id, $act -> id));
	$items = array();
	while ($admins_log_post = $select_admins_log -> fetch()) {
		$us = new Users\User($admins_log_post -> id_user);
		$items[] = array(
			'img' => $us -> ava_list(), 
			'link' => $set -> profile_page.$us -> id, 
			'name' => $us -> login(0), 
			'counter' => TimeUtils::show($admins_log_post -> time), 
			'content' => TextUtils::show($admins_log_post -> msg)
		);
	}
	$smarty = new SMX();
	$smarty -> assign("list_items", $items);
	$sets = array('div' => 'content_mess', 'img_left' => true);
	$smarty -> assign("sets", $sets);
	$smarty -> display("list.items.tpl");
	echo $navi -> show;
	?>
	<?doc::back("Список действий", "?act=admins_log&mod_id={$mod -> id}".(isset($ank)?"&user_id=".$ank -> id:null))?>
	<?doc::back("Список модулей", "?act=admins_log".(isset($ank)?"&user_id=".$ank -> id:null))?>
	<?
	include(FOOT);
}
if (isset($_GET['mod_id'])) {
	$mod =$db -> farr("SELECT * FROM `admins_log_mod` WHERE `id` = ? LIMIT 1", array(intval($_GET['mod_id'])));
	$q = $db -> q("SELECT * FROM `admins_log_act` WHERE `id_mod` = ?", array($mod -> id));
	if (!$q -> rowCount())doc::listEmpty("Нет действий");
	while ($act = $q -> fetch()) {
		$act -> count = $db -> res("SELECT COUNT(*) FROM `admins_log` WHERE `id_act` = ?".(isset($ank)?" AND `id_user` = '".$ank -> id."'":null), array($act -> id));
		$items[] = array(
			'link' => "?act=admins_log&mod_id={$mod -> id}&act_id={$act -> id}".(isset($ank)?"&user_id=".$ank -> id:null), 
			'name' => TextUtils::escape($act -> name), 
			'counter' => $act -> count
		);
	}
	$smarty = new SMX();
	$smarty -> assign("list_items", $items);
	$sets = array('hr' => true, 'img' => imgs::show("str.gif"));
	$smarty -> assign("sets", $sets);
	$smarty -> display("list.items.tpl");
	
doc::back("Список модулей", "?act=admins_log".(isset($ank)?"&user_id=".$ank -> id:null)."");

	include(FOOT);
}
$q = $db -> q("SELECT * FROM `admins_log_mod`");
if (!$q -> rowCount())doc::listEmpty("Нет действий в модулях");
$items = array();
while ($mod = $q -> fetch()) {
	$mod -> count = $db -> res("SELECT COUNT(*) FROM `admins_log` WHERE `id_mod` = ?".(isset($ank)?" AND `id_user` = '".$ank -> id."'":null), array($mod -> id));
	$items[] = array(
		'link' => "?act=admins_log&mod_id={$mod -> id}".(isset($ank)?"&user_id=".$ank -> id:null), 
		'name' => TextUtils::escape($mod -> name), 
		'counter' => $mod -> count
	);
}
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$sets = array('hr' => true, 'img' => imgs::show("str.gif"));
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
doc::back("В админку", "?act=index");
include(FOOT);
?>