<?
switch (@$show_good_info_configs['for']) {
	case 'shop':
	$title = TextUtils::DBFilter($good -> name);
	journal::update('shop', $good -> id, "/shop/good/".$good -> id);
	break;
	default:
	$title = TextUtils::DBFilter($good -> name);
	journal::update('shop', $good -> id, "/user/shop/?act=good&good_id=".$good -> id);
	break;
}
include(HEAD);
echo "<div class='content lh2'>\n";
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
echo $seller -> icon()." Продавец: ".$seller -> login()." (<a href='/shop/seller/{$seller -> id}'>все товары</a>)<br />\n";
echo Doc::showImage("/images/alarm.png", array('class' => ICON_CLASS, 'height' => $set -> wb ? 24 : 16, 'width' => $set -> wb ? 24 : 16))." Добавлен: ".TimeUtils::show($good -> time_add)."<br />\n";
if ($good -> time_update_archive)
	echo Doc::showImage("/images/application-zip.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." Обновлен: ".TimeUtils::show($good -> time_update_archive)."<br />\n";
echo Doc::showImage("/images/my_tov.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." Размер: ".files::fileSizeWord(filesize($good -> getFilePath()))."<br />\n";
echo Doc::showImage("/images/kwallet.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." Цена: {$good -> price} WMR<br />\n";
if (isset($u) && $u -> id == $seller -> id)
	echo "&raquo; <a href='/user/shop/?act=advt_good&good_id={$good -> id}'>Купить рекламу</a><br />\n";
echo "</div>\n";
if ($good -> getCountPreviews() > 0) {
	$main_preview = $good -> getMainPreview();
	echo "<hr>\n";
	if ($set -> wb) {
		echo "<div class='hidden' id='preview'>\n";
		echo "<div class='contentTitle' style='text-align: center'>\n";
		echo TextUtils::DBFilter($good -> name);
		echo "</div>\n";
		echo "<div class='content' style='text-align: center;'>\n";
		echo Doc::showLink($main_preview -> preview_original, Doc::showImage($main_preview -> preview_zoom, array('class' => 'main', 'width' => PREVIEW_ZOOM_WH, 'height' => PREVIEW_ZOOM_WH)), array('target' => '_blank'));
		echo "</div>\n";
		echo "</div>\n";
	}
	echo "<div class='content'>\n";
	echo "<div style='text-align: center;'>\n";
	echo Doc::showLink($set -> wb ? 'preview' : $main_preview -> preview_original, Doc::showImage($main_preview -> preview_page, array('class' => 'main', 'width' => PREVIEW_PAGE_WH, 'height' => PREVIEW_PAGE_WH)), $set -> wb ? array('data-toggle' => 'modal') : '');
	$count_previews_last = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id` < ? AND `id_good` = ?", array($main_preview -> id, $good -> id)) + 1;
	echo "<div>\n";
	if ($good -> getCountPreviews() > 1) {
		echo ($main_preview -> hasPreventPreview() ? "<a href='?act=good&good_id={$good -> id}&preview_id=".$main_preview -> getPreventPreview() -> id."'>&laquo; пред</a>":"&laquo; пред");
		echo " <span class='grey'>($count_previews_last из ".$good -> getCountPreviews().")</span> ";
		echo ($main_preview -> hasNextPreview() ? "<a href='?act=good&good_id={$good -> id}&preview_id=".$main_preview -> getNextPreview() -> id."'>след &raquo;</a>":"след &raquo;");
	}
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
}
echo "<hr>\n";
echo "<div class='content'>\n";
echo "<div>\n";
echo TextUtils::show($good -> desc, $seller -> id);
echo "</div>\n";
echo "</div>\n";
$q = $db -> q('SELECT * FROM `users_shop_goods_glues` WHERE `id_good_one` = ? OR `id_good_two` = ?', array($good -> id, $good -> id));
if ($q -> rowCount() > 0) {
	$glues = array();
	while ($post = $q -> fetch()) {
		$var = 'id_good_'.($post -> id_good_two == $good -> id ? 'one' : 'two');
		$second_good = new UsersShop\Good($post -> $var);
		if ($second_good -> isAddedToShop())
			$glues[] = $second_good;
	}
	if (count($glues) > 0) {
		echo "<div class='content'>\n";
		echo "<b>Этот товар для других версий:</b><br />\n";
		foreach ($glues as $second_good) {
			echo "<div>\n";
			echo Doc::showLink('/shop/good/'.$second_good -> id, $second_good -> getShopCategory() -> getFullPathString().' &raquo; '.$second_good -> name);
			echo "</div>\n";
		}
		echo "</div>\n";
	}
}
if ($good -> discount >= 3 && $good -> discount <= 50 && $good -> discount_price >= 100) {
	echo "<hr>\n";
	echo "<div class='mod'>\n";
	echo "Предоставляется скидка на товар в размере <span class='green'>{$good -> discount}%</span> покупателям, которые покупали товары у этого продавца на сумму <span class='wmr_blue'>{$good -> discount_price} WMR</span> и больше.<br />\n";
	echo "Не пропустите этот момент!\n";
	echo "</div>\n";
}
echo "<hr>\n";
echo "<div class='mod_up'>\n";
echo "Всего копий: {$good -> copies}<br />\n";
if ($good -> getCountCopiesEnd())
	echo "Осталось: ".$good -> getCountCopiesEnd();
else
	echo "<span class='red'>Все копии проданы</span>\n";
echo "<br />\n";
echo "</div>\n";
if ((isset($u) && ($seller -> id == $u -> id || UsersShop\Shop::userBoughtGood($good -> id) || adminka::access('shop_download_goods'))) || $good -> isAddedToShop()) {
	echo "<hr>\n";
	echo "<div class='content lh2'>\n";
	if (isset($u) && ($seller -> id == $u -> id || UsersShop\Shop::userBoughtGood($good -> id) || adminka::access('shop_download_goods'))) {
		echo Doc::showImage("/images/download.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink($good -> getDownloadLink(), 'Скачать');
	} elseif ($good -> isAddedToShop()) {
		echo Doc::showImage("/images/shop.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/shop/buy/'.$good -> id, 'Купить')." за <span class='green'>{$good -> price} WMR</span><br />\n";
		if (isset($u) && !Shop\Basket::hasGood($good -> id))
			echo Doc::showImage("/images/shopcart.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/cab/basket?add='.$good -> id, 'В корзину')."<br />\n";
	}
	echo "</div>\n";
}
echo "<hr>\n";
echo "<div class='mod'>\n";
echo Doc::showImage("/images/rating.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." Рейтинг товара: {$good -> rating}<br />\n";
echo Doc::showImage("/images/review.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/user/shop/?act=reviews&good_id='.$good -> id, 'Отзывы').' ('.Doc::showLink('/user/shop/?act=reviews&good_id='.$good -> id.'&only=good', "<span class='green'>".$good -> getCountGoodReviews()."</span>").' | '.Doc::showLink('/user/shop/?act=reviews&good_id='.$good -> id.'&only=bad', "<span class='red'>".$good -> getCountBadReviews()."</span>").")<br />\n";
switch (@$show_good_info_configs['for']) {
	case 'shop':
	if (isset($u) && $seller -> id == $u -> id)
		echo Doc::showImage("/images/edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/user/shop/?act=good&good_id='.$good -> id, 'Работа с товаром')."<br />\n";
	break;
	default:
	if (isset($u) && ($seller -> id == $u -> id || adminka::access('shop_edit_good')))
		echo Doc::showImage("/images/edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/user/shop/?act=edit_good&good_id='.$good -> id, 'Редактировать')."<br />\n";
	if (isset($u) && $seller -> id == $u -> id)
		echo Doc::showImage("/images/glue.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/user/shop/?act=glue_goods&good_id='.$good -> id, 'Склеить товары')."<br />\n";
	if (isset($u) && $seller -> id == $u -> id || adminka::access('shop_set_previews_good'))
		echo Doc::showImage("/images/previews.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/user/shop/?act=previews&good_id='.$good -> id, 'Скриншоты')."<br />\n";
	if ($good -> isAddedToShop()) {
		$current_category = $good -> getShopCategory();
		echo Doc::showImage("/images/move_to.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." Товар в магазине: <a href='/shop/good/{$good -> id}'>{$current_category -> getFullPathString()}</a><br />\n";
		echo "<div style='margin-top: 4px;'>\n";
		echo "</div>\n";
		if (isset($u) && $u -> id == $seller -> id)
			echo Doc::showLink('/user/shop/?act=add_to_shop&good_id='.$good -> id, '&raquo; Переместить в магазине')."<br />\n";
	} else
		if (isset($u) && $u -> id == $seller -> id)
			echo Doc::showImage("/images/move_to.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/user/shop/?act=add_to_shop&good_id='.$good -> id, 'Добавить в магазин')."<br />\n";
	if (isset($u) && $u -> id == $seller -> id)
		echo Doc::showImage("/images/move.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)).' '.Doc::showLink('/user/shop/?act=replace_good&good_id='.$good -> id, 'Переместить у себя')."<br />\n";
	break;
}
echo "</div>\n";
$count_result = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_comments` WHERE `id_good` = ?", array($good -> id));
$navi = new navi($count_result, '?');
journal::update('shop', $good -> id, "/user/shop/?act=good&good_id=".$good -> id."&page=".$navi -> page);
echo "<div class='panel_ud'>\n";
echo Doc::showImage("/images/chat.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." Комментарии ($count_result)<br />\n";
echo "</div>\n";
echo "<hr>\n";
$q = $db -> q("SELECT * FROM `users_shop_goods_comments` WHERE `id_good` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($good -> id));
$posts = array();
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$reply_us = new Users\User($post -> reply_id_user);
	$actions = array();
	$actions[] = array(
		'link' => "/user/shop/?act=reply_comment&comment_id=".$post -> id, 
		'name' => 'Ответить'
	);
	if (adminka::access('shop_delete_comment'))$actions[] = array(
		'link' => "/user/shop/?act=delete_comment&comment_id={$post -> id}&ussec=".ussec::get(), 
		'name' => 'Удалить'
	);
	$posts[] = array(
		'data' => $post, 
		'us' => $us, 
		'reply_us' => $reply_us, 
		'time_form' => TimeUtils::show($post -> time), 
		'msg_form' => TextUtils::show($post -> msg, $us -> id), 
		'actions' => $actions
	);
}
$smarty = new SMX();
$smarty -> assign("posts", $posts);
$smarty -> display("list.comments.tpl");
echo $navi -> show;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	Users\User::if_user('is_reg');
	$msg = $_POST['msg'];
	if (TextUtils::length(trim($msg)) < 1)$error = 'Введите комментарий';
	elseif (TextUtils::length($msg) > 5000)$error = 'Комментарий слишком длинный';
	else {
		$db -> q("INSERT INTO `users_shop_goods_comments` (`id_good`, `id_user`, `time`, `msg`) VALUES (?, ?, ?, ?)", array($good -> id, $u -> id, time(), $msg));
		$cid = $db -> lastInsertId();
		if (isset($_POST['reply_id_user']) && $db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ?", array(intval($_POST['reply_id_user']))) && isset($_POST['reply_id_comment']) && $db -> res("SELECT COUNT(*) FROM `users_shop_goods_comments` WHERE `id` = ? AND `id_good` = ?", array(intval($_POST['reply_id_comment']), $good -> id))) {
			$rus = new Users\User(intval($_POST['reply_id_user']));
			$reply_comment = $db -> farr("SELECT * FROM `users_shop_goods_comments` WHERE `id` = ? AND `id_good` = ?", array(intval($_POST['reply_id_comment']), $good -> id));
			$db -> q("UPDATE `users_shop_goods_comments` SET `reply_id_user` = ?, `reply_id_comment` = ? WHERE `id` = ?", array($rus -> id, $reply_comment -> id, $cid));

			if ($u -> id != $rus -> id)
				if (!$good -> isDeleted() || $good -> isDeleted() && adminka::access('shop_view_deleted_goods', $rus -> id))
					if (!$good -> isBlocked() || $good -> isBlocked() && adminka::access('shop_view_blocked_goods', $rus -> id))
						Users\Notifications::send('shop_good_comment', $rus -> id, 'Вам ответили на комментарий к товару "'.$good -> name.'"');
		}
		$all_users_commed = array();
		$q = $db -> q("SELECT * FROM `users_shop_goods_comments` WHERE `id_good` = ? ORDER BY `time` DESC", array($good -> id));
		while ($comment = $q -> fetch()) {
			if (!in_array($comment -> id_user, $all_users_commed) && $comment -> id_user != $seller -> id && $comment -> id_user != $u -> id) {
				$all_users_commed[] = $comment -> id_user;
				if (!$good -> isDeleted() || $good -> isDeleted() && adminka::access('shop_view_deleted_goods', $comment -> id_user))
					if (!$good -> isBlocked() || $good -> isBlocked() && adminka::access('shop_view_blocked_goods', $comment -> id_user))
						journal::send($u -> id, $comment -> id_user, 'shop', 'comment', $good -> id, $comment -> id, $comment -> time);
			}
		}
		if ($u -> id != $seller -> id)
			if (!$good -> isDeleted() || $good -> isDeleted() && adminka::access('shop_view_deleted_goods', $seller -> id))
				if (!$good -> isBlocked() || $good -> isBlocked() && adminka::access('shop_view_blocked_goods', $seller -> id))
					journal::send($u -> id, $seller -> id, 'shop', 'object', $good -> id, 0, $good -> time);
		header("Location: ?act=good&good_id=".$good -> getID());
		exit();
	}
}
echo alerts::error();
if (isset($u)) {
	echo "<hr>\n";
	new SMX(
		array('el' => array(
			array('type' => 'textarea', 'name' => 'msg', 'id' => 'textarea', 'br' => true), 
			array('type' => 'ussec'), 
			array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить'), 
			array('type' => 'hp_smiles'), 
			array('type' => 'hp_tags')
		), 'fastSend' => true), 'form.tpl'
	);
}
switch (@$show_good_info_configs['for']) {
	case 'shop':
	echo "<hr>\n";
	CActions::setSeparator('<br />');
	CActions::setShowType(CActions::SHOW_ALL);
	CActions::addAction('/user/shop/?act=buyers&good_id='.$good -> getID(), 'Покупатели', '/images/buyers.png');
	if (adminka::access('shop_delete_good')) {
		CActions::addAction('/shop/delete_good/'.$good -> getID(), "Удалить", '/images/delete.png');
	}
	echo CActions::showActions();
	Doc::back(TextUtils::DBFilter($category -> name), "/shop/category/{$category -> id}");
	break;
	default:
	echo "<hr>\n";
	CActions::setSeparator('<br />');
	CActions::setShowType(CActions::SHOW_ALL);
	CActions::addAction('/user/shop/?act=buyers&good_id='.$good -> getID(), 'Покупатели', '/images/buyers.png');
	if ($good -> isBlocked() && adminka::access('shop_unblock_good') || !$good -> isBlocked() && adminka::access('shop_block_good')) {
		CActions::addAction('/user/shop/?act=block&good_id='.$good -> getID(), $good -> isBlocked() ? "Разблокировать" : "Заблокировать", '/images/'.($good -> isBlocked() ? 'lock_yellow.png' : 'unlock_yellow.png'));
	}
	if (!$good -> isDeleted() && adminka::access('shop_delete_good'))
		CActions::addAction('/user/shop/?act=delete_good&good_id='.$good -> getID(), "Удалить", '/images/delete.png');
	echo CActions::showActions();
	if ($good -> isDeleted() && !$category -> exists())
	Doc::back('Список удаленных товаров', '?act=deleted_goods&user_id='.$seller -> id);
	else
	Doc::back(TextUtils::DBFilter($category -> getName()), "/user/shop/?act=category&category_id={$category -> id}");
	break;
}
/*
switch (@$show_good_info_configs['for']) {
	case 'shop':
	break;
	default:
	break;
}
*/
?>