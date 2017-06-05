<?
$title = 'Редактирование профиля';
include(HEAD);
$items = array(
	array(
		'img' => imgs::show("anketa.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/edit_profile/info", 
		'name' => "Личные данные"
	), 
	array(
		'img' => imgs::show("change_picture.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/edit_profile/change_avatar", 
		'name' => "Сменить аватар"
	), 
	array(
		'img' => imgs::show("contacts.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/edit_profile/contacts", 
		'name' => "Контакты"
	), 
	array(
		'img' => imgs::show("business_people.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/edit_profile/business", 
		'name' => "Занятость и знания"
	), 
	array(
		'img' => imgs::show("webmoney_icon.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/edit_profile/webmoney", 
		'name' => "WMID и WMR"
	)
);
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$sets = array(
	'hr' => true
);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
doc::back("Кабинет", "/cab");
include(FOOT);