<?
$title = 'Настройки';
include_once(HEAD);
$items = array(
	array(
		'img' => imgs::show("edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/edit_profile", 
		'name' => "Редактировать профиль"
	), 
	array(
		'img' => imgs::show("blist.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/cab/blist", 
		'name' => "Черный список", 
		'counter' => $db -> res("SELECT COUNT(*) FROM `blacklist` WHERE `id_user` = ?", array($u -> id))
	), 
	array(
		'img' => imgs::show("view_set.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/settings/view", 
		'name' => "Настройки отображения"
	), 
	array(
		'img' => imgs::show("e_mail_set.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/settings/email", 
		'name' => "E-mail"
	), 
	array(
		'img' => imgs::show("phone.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/settings/phone", 
		'name' => "Телефон"
	), 
	array(
		'img' => imgs::show("notifications.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/settings/notifications", 
		'name' => "Оповещения"
	), 
	array(
		'img' => imgs::show("lock_yellow.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/settings/pass", 
		'name' => "Сменить пароль"
	), 
	array(
		'img' => imgs::show("key_blue.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/settings/keyword", 
		'name' => "Ключевое слово"
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
?>