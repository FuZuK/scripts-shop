<?
adminka::accessCheck('shop_view_blocked_tovs');
$title .= ' - Заблокированные товары';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `shop_tovs` WHERE `in_block` = ?", array(1));
$navi = new navi($cr, '?act=deleted_tovs&');
$q = $db -> q("SELECT * FROM `shop_tovs` WHERE `in_block` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array(1));
$tovs = array();
while ($tov = $q -> fetch()) {
	$tov = shop::getTovInfo($tov -> id);
	$screen = shop::getScreenInfo($tov -> screen_info -> id);
	$seller = new Users\User($tov -> id_user);
	$block_us = new Users\User($tov -> block_id_user);
	$actions = array();
	if (adminka::access('shop_unblock_tov'))$actions[] = array(
		'link' => "/shop/block/{$tov -> id}", 
		'name' => "Разблокировать"
	);
	$shows = array(
		'name' => true,  
		'seller' => true, 
		'screen' => true, 
		'block_us' => true, 
		'block_msg' => true, 
		'lines' => true
	);
	$tovs[] = array(
		'data' => $tov, 
		'screnn' => $screen, 
		'seller' => $seller, 
		'block_us' => $block_us, 
		'screen_form' => imgs::show($screen -> prev_in_list, array('class' => 'main', 'height' => ($set -> wb?90:50), 'width' => ($set -> wb?90:50)), "/images/tov_screens/"), 
		'shows' => $shows, 
		'actions' => $actions
	);
}
$smarty = new SMX();
$smarty -> assign("tovs", $tovs);
$smarty -> display("list.tovs.tpl");
echo $navi -> show;

doc::back("В админку", "/adminka");

include(FOOT);
?>