<?
$title .= ' - Поиск товаров';
include(HEAD);
if (isset($_GET['search']))$search = $_GET['search'];
if (isset($_POST['search']))$search = $_POST['search'];
if (isset($search)) {
	$cr = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE (`name` LIKE ? OR `desc` LIKE ?) AND `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0'", array("%$search%", "%$search%"));
	$navi = new navi($cr, '?search='.TextUtils::escape($search).'&');
	$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE (`name` LIKE ? OR `desc` LIKE ?) AND `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0' ORDER BY `time_add` DESC LIMIT ".$navi ->start.", ".$set -> results_on_page, array("%$search%", "%$search%"));
	if (!$cr)doc::listEmpty("Ничего не найдено");
	$goods_show_configs['for'] = 'shop_category';
	while ($post = $q -> fetch()) {
		$good = new UsersShop\Good($post -> id);
		include(INCLUDS_DIR.'list_goods.php');
	}
	echo $navi -> show;
}
echo "<hr>\n";
$el = array(
	array('type' => 'text', 'name' => 'search'), 
	array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Поиск')
);
new SMX(array('el' => $el, 'action' => '?'), 'form.tpl');
doc::back("Назад", "/shop");
include(FOOT);
?>