<? # by Killer
echo "<div class='title'>Хиты продаж</div>\n";
$q = $db -> q("SELECT * FROM `users_shop_goods_solds` INNER JOIN `users_shop_goods` ON `users_shop_goods_solds`.`id_good` = `users_shop_goods`.`id` WHERE `users_shop_goods`.`in_block` = '0' AND `users_shop_goods`.`deleted` = '0' AND `users_shop_goods`.`shop_id_category` != '0' ORDER BY `users_shop_goods_solds`.`time` DESC LIMIT 6", array(0, 0));
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id_good);
	include(INCLUDS_DIR.'list_goods.php');
}
?>