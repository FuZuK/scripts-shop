<?
$title = 'Мой кабинет';
include_once(HEAD);
$list_items = array(
	array(
		'img' => imgs::show("support.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => "/support", 
		'name' => "Поддержка"
	), 
	array(
		'img' => imgs::show("prof.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)), 
		'link' => $set -> profile_page.$u -> id, 
		'name' => "Профиль"
	), 
	array(
		'img' => imgs::show("basket_green.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/cab/purchases", 
		'name' => "Покупки"
	), 
	array(
		'img' => imgs::show("shop.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/user/shop/", 
		'name' => "Мой магазин", 
		'counter' => $db -> res('SELECT COUNT(*) FROM `users_shop_goods` WHERE `id_user` = ?', array($u -> id))
	), 
	array(
		'img' => imgs::show("shopcart.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/cab/basket", 
		'name' => "Корзина"
	), 
	array(
		'img' => imgs::show("emblem_money.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/cab/accounting", 
		'name' => "Бухгалтерия"
	), 
	array(
		'img' => imgs::show("money.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/cab/money", 
		'name' => "Мои деньги"
	), 
	array(
		'img' => imgs::show("mail_new.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/post", 
		'name' => "Контакты"
	), 
	array(
		'img' => imgs::show("journal.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/journal", 
		'name' => "Журнал оповещений"
	), 
	array(
		'img' => imgs::show("list.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/cab/loglist", 
		'name' => "Логи входов"
	), 
	array(
		'img' => imgs::show("settings.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/settings", 
		'name' => "Настройки"
	), 
	array(
		'img' => imgs::show("exit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)),
		'link' => "/cab/logout", 
		'name' => "Выход"
	)
);
new SMX(array('sets' => array('hr' => true), 'list_items' => $list_items), "list.items.tpl");
?>