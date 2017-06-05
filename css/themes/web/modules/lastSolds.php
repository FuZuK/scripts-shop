<?
echo "<div class='title'>Хиты продаж</div>\n";
echo "<div class='verge'>\n";
echo "<div class='titleCirLeft'></div>\n";
echo "<div class='titleCirRight'></div>\n";
echo "</div>\n";
$q = $db -> q("SELECT * FROM `users_shop_goods_solds` INNER JOIN `users_shop_goods` ON `users_shop_goods_solds`.`id_good` = `users_shop_goods`.`id` WHERE `users_shop_goods`.`in_block` = '0' AND `users_shop_goods`.`deleted` = '0' AND `users_shop_goods`.`shop_id_category` != '0' ORDER BY `users_shop_goods_solds`.`time` DESC LIMIT 6", array(0, 0));
if (!$q -> rowCount())echo alerts::list_empty('Нет товаров');
else {
	$num_good = 0;
	echo "<table>\n";
	echo "<tr>\n";
	while ($good = $q -> fetch()) {
		$good = new UsersShop\Good($good -> id);
		$seller = $good -> getSeller();
		if ($num_good == 3) {
			$num_good = 0;
			echo "</tr>\n";
			echo "<tr>\n";
		}
		$num_good++;
		echo "<td style='width: 33.333333333%;'>\n";
		echo "<div class='pl_photo_item rad_tlr rad_blr content_mess'>\n";
		echo "<div class='pl_photo_image_wrap'>\n";
		echo "<div class='main_mage'>\n";
		echo "<div class='new_good'></div>\n";
		echo Doc::showLink('/shop/good/'.$good -> id, Doc::showImage($good -> getMainPreview() -> preview_page, array('class' =>'main main_screen')));
		echo "</div>\n";
		echo "</div>\n";
		echo "<hr class='custom'>\n";
		echo "<div class='fresp' style='text-align: center; height: 50px;'>\n";
		echo Doc::showLink('/shop/good/'.$good -> id, TextUtils::DBFilter($good -> name))."<br />\n";
		$category_one = $good -> getShopCategory();
		$category_two = $category_one -> getCategory();
		if ($category_two -> exists() || $category_one -> exists()) {
			echo Doc::showImage('/images/folder_blue_copy.png', array('style' => 'vertical-align: 1px;'))." ";
			if ($category_two -> exists())
				echo Doc::showLink('/shop/category/'.$category_two -> id, TextUtils::DBFilter($category_two -> name))." &raquo; ";
			if ($category_one -> exists())
				echo Doc::showLink('/shop/category/'.$category_one -> id, TextUtils::DBFilter($category_one -> name))."<br />\n";
		}
		echo "</div>\n";
		echo "</div>\n";
		echo "</td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
}
?>