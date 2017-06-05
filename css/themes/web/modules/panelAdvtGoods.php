<?
$q = $db -> q("SELECT `users_shop_goods_advt`.`name`, `users_shop_goods_advt`.`id_good` FROM `users_shop_goods_advt` INNER JOIN `users_shop_goods` ON `users_shop_goods_advt`.`id_good` = `users_shop_goods`.`id` WHERE `users_shop_goods`.`in_block` = '0' AND `users_shop_goods`.`deleted` = '0' AND `users_shop_goods`.`shop_id_category` != '0' ORDER BY `users_shop_goods_advt`.`time` DESC LIMIT 5");
if ($q -> rowCount()):
?>
<div class="boxWrapper">
	<div class="boxPanel">
		<div class="boxTitle">Рекомендуем</div>
		<?
		while ($post = $q -> fetch()) {
			$good = new UsersShop\Good($post -> id_good);
			echo Doc::showLink('/shop/good/'.$good -> id, 
				"<div class='left'>\n"
				.Doc::showImage($good -> getMainPreview() -> preview_list, array('height' => 48, 'width' => 48))."\n"
				."</div>\n"
				."<div class='ov_hid'>\n"
				.TextUtils::DBFilter($post -> name)."<br />\n"
				."<span class='wmr_blue small'>{$good -> price} WMR</span>\n"
				."</div>"
				.Doc::addClear());
		}
		?>
	</div>
</div>
<?endif?>