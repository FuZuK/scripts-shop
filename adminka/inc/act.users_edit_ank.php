<?
adminka::accessCheck('users_edit_anketa');
$us = new Users\User(intval(@$_GET['user_id']));
if (!$us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Пользователь не найден.");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($us -> getGroup() -> level >= $u -> getGroup() -> level) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("У Вас недостаточно привилегий для редактирования профиля этого пользователя.");
	doc::back("Назад", $set -> profile_page.$us -> id);
	include(FOOT);
}
$title .= ' - Редактироание профиля '.$us -> login;
include(HEAD);
$link_back = "?act=users_edit_ank&user_id=".$us -> id;
if (isset($_GET['case']) && is_file("../edit_profile/inc/act.".TextUtils::escape($_GET['case']).".php")) {
	include_once("../edit_profile/inc/act.".TextUtils::escape($_GET['case']).".php");
	include_once(FOOT);
}
$items = array(
	array(
		'img' => imgs::show("anketa.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "?act=users_edit_ank&user_id={$us -> id}&case=info", 
		'name' => "Личные данные"
	), 
	array(
		'img' => imgs::show("change_picture.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "?act=users_edit_ank&user_id={$us -> id}&case=change_avatar", 
		'name' => "Сменить аватар"
	), 
	array(
		'img' => imgs::show("contacts.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "?act=users_edit_ank&user_id={$us -> id}&case=contacts", 
		'name' => "Контакты"
	), 
	array(
		'img' => imgs::show("business_people.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "?act=users_edit_ank&user_id={$us -> id}&case=business", 
		'name' => "Занятость и знания"
	), 
	array(
		'img' => imgs::show("webmoney_icon.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "?act=users_edit_ank&user_id={$us -> id}&case=webmoney", 
		'name' => "WMID и WMR"
	)
);
if (adminka::access('users_spec_access')) {
	$items[] = array(
		'img' => imgs::show("access.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "?act=user_spec_access&user_id={$us -> id}", 
		'name' => "Отдельные привилегии"
	);
}
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$sets = array(
	'hr' => true
);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
doc::back("Назад", $set -> profile_page.$us -> id);
include(FOOT);
?>