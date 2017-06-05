<?
$category = new Shop\Category(intval(@$_GET['category_id']));
if ($category -> exists())$title .= ' - '.$category -> name;
include(HEAD);
if ($category -> id_category == -1) {
	echo "<div class='content search_div'>\n";
	echo Doc::showImage("/images/search.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." <a href='/shop/search'>Поиск товаров</a>\n";
	echo "</div>\n";
}
$q = $db -> q("SELECT * FROM `shop_categories` WHERE `id_category` = ? ORDER BY `pos` ASC", array($category -> id));
while ($shop_category = $q -> fetch()) {
	$count_goods = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `shop_categories` LIKE ? AND `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0'", array("%/".$shop_category -> id."/%"));
	echo "<div class='content_mess'>\n";
	echo "<div class='list_us_info'>\n";
	echo Doc::showImage('/images/folder_blue.png', array('class' => 'ic_big'))." ".Doc::showLink('/shop/category/'.$shop_category -> id, TextUtils::DBFilter($shop_category -> name))." <span>($count_goods)</span>";
	echo "</div>\n";
	echo Doc::addClear();
	echo "</div>\n";
}
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `shop_id_category` = ? AND `deleted` = '0' AND `in_block` = '0'", array($category -> id));
$navi = new navi($count_results, "?");
$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE `shop_id_category` = ? AND `deleted` = '0' AND `in_block` = '0' ORDER BY `time_add` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($category -> id));
$goods_show_configs['for'] = 'shop_category';
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id);
	include(INCLUDS_DIR.'list_goods.php');
}
CActions::setSeparator('<br />');
CActions::setShowType(CActions::SHOW_ALL);
if (isset($u) && adminka::access('shop_add_category'))
	CActions::addAction('/shop/add_category/'.$category -> id, 'Добавить категорию', '/images/add1.png');
if (isset($u) && adminka::access('shop_edit_category') && $category -> id_category != -1)
	CActions::addAction('/shop/edit_category/'.$category -> id, 'Редактировать', '/images/edit.png');
if (isset($u) && adminka::access('shop_delete_category') && $category -> id_category != -1)
	CActions::addAction('/shop/delete_category/'.$category -> id, 'Удалить', '/images/delete.png');
if (CActions::getCount())
	echo "<hr>\n";
echo CActions::showActions();
if ($category -> id_category != -1)
	doc::back("Назад", "/shop/category/{$category -> id_category}");
include(FOOT);
?>