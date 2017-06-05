<?
$good = new UsersShop\Good(intval(@$_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$seller = $good -> getSeller();
$el = array();
if (!$good -> isDeleted() && (adminka::access('shop_delete_good') || $u -> id == $seller -> id)) {
	$title = 'Удалить товар';
	include(HEAD);
	if (isset($_POST['delete']) && ussec::check_p()) {
		$good -> delete();
		$category = $good -> getCategory();
		if (!$category -> exists())
			$category = UsersShop\Shop::getRootCategory($seller -> id);
		header("Location: /user/shop/?act=category&category_id={$good -> id_category}");
		exit();
	}
	$el[] = array('type' => 'title', 'value' => 'Подтвердите удаление товара:', 'br' => true);
	$el[] = array('type' => 'ussec');
	$el[] = array('type' => 'submit', 'name' => 'delete', 'value' => 'Удалить', 'br' => true);
} elseif ($good -> isDeleted() && adminka::access('shop_restore_good')) {
	$title = 'Восстановить товар';
	include(HEAD);
	if (isset($_POST['restore']) && ussec::check_p()) {
		$good -> restore();
		header("Location: /user/shop/?act=good&good_id={$good -> id}");
		exit();
	}
	$el[] = array('type' => 'title', 'value' => 'Подтвердите восстановление товара:', 'br' => true);
	$el[] = array('type' => 'ussec');
	$el[] = array('type' => 'submit', 'name' => 'restore', 'value' => 'Восстановить', 'br' => true);
} else {
	header("Location: /");
	exit();
}
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
Doc::back("Назад", "/user/shop/?act=good&good_id={$good -> id}");
include(FOOT);
?>