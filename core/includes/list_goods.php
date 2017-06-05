<?
CActions::setSeparator(' - ');
CActions::setShowType(CActions::SHOW_ONLY_LINKS);
CActions::setClass('mess_mod');
$seller = $good -> getSeller();
echo "<div class='content_mess'>\n";
echo "<div class='left'>\n";
echo Doc::showImage($good -> getMainPreview() -> preview_list, array('class' => 'main', 'height' => PREVIEW_LIST_WH, 'width' => PREVIEW_LIST_WH));
echo "</div>\n";
echo "<div class='lst_h'>\n";
echo "<div class='list_us_info'>\n";
if (isset($goods_show_configs) && isset($goods_show_configs['for']))
	switch ($goods_show_configs['for']) {
		case 'basket':
		case 'shop_category':
		echo Doc::showLink('/shop/good/'.$good -> id, TextUtils::DBFilter($good -> name));
		break;
		default:
		echo Doc::showLink('/user/shop/?act=good&good_id='.$good -> id, TextUtils::DBFilter($good -> name));
		break;
	} else {
		echo Doc::showLink('/user/shop/?act=good&good_id='.$good -> id, TextUtils::DBFilter($good -> name));
	}
echo "</div>\n";
echo "<div class='mess_list'>\n";
echo "<span class='form_q'>Цена:</span> <span class='form_a wmr_blue'>{$good -> price} WMR</span><br />\n";
echo "<span class='form_q'>Продавец:</span> ".$seller -> login(1)."<br />\n";
if (isset($goods_show_configs) && isset($goods_show_configs['for']))
	switch ($goods_show_configs['for']) {
		case 'basket':
		CActions::addAction('/cab/basket?delete='.$good -> id, 'Удалить');
		if (!UsersShop\Shop::userBoughtGood($good -> id))
			CActions::addAction('/shop/buy/'.$good -> id, 'Купить');
		break;
	}
echo "</div>\n";
if ($good -> isDeleted()) {
	$deleter = new Users\User($good -> deleted_id_user);
	echo "<div class='content_redi' style='line-height: 120%;'>\n";
	echo "<span class='red'>Товар удален</span> (".$deleter -> login(1).", ".TimeUtils::show($good -> deleted_time).")<br />\n";
	if (adminka::access('shop_restore_good')) {
		echo "<div style='margin-top: 4px'>\n";
		echo Doc::showLink('/user/shop/?act=delete_good&good_id='.$good -> id, "&raquo; Восcтановить");
		echo "</div>\n";
	}
	echo "</div>\n";
}
if ($good -> isBlocked()) {
	$blocker = new Users\User($good -> block_id_user);
	echo "<div class='content_redi' style='line-height: 120%;'>\n";
	echo "<span class='red'>Товар заблокирован</span> (".$blocker -> login(1).", ".TimeUtils::show($good -> block_time).")<br />\n";
	echo TextUtils::show($good -> block_msg, $blocker -> id);
	if (adminka::access('shop_restore_good')) {
		echo "<div style='margin-top: 4px'>\n";
		echo Doc::showLink('/user/shop/?act=block&good_id='.$good -> id, "&raquo; Разблокировать");
		echo "</div>\n";
	}
	echo "</div>\n";
}
if (CActions::getCount() > 0)
echo "<hr class='custom'>\n";
echo CActions::showActions();
echo "</div>\n";
echo Doc::addClear();
echo "</div>\n";
?>