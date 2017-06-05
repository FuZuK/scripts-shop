<? # by Killer
echo "<div class='title'>".Doc::showLink('/shop/new', 'Новинки')."</div>\n";
$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE `in_block` = '0' AND `deleted` = '0' AND `shop_id_category` != '0' ORDER BY `shop_time_add` DESC LIMIT 6");
while ($good = $q -> fetch()) {
	$good = new UsersShop\Good($good -> id);
	include(INCLUDS_DIR.'list_goods.php');
}
?>