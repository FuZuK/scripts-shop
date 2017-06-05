<div class="boxWrapper">
	<div class="boxPanel">
		<div class="boxTitle">Магазин</div>
		<?
		$q = $db -> q("SELECT * FROM `shop_categories` WHERE `id_category` = ? ORDER BY `pos` ASC", array(Shop\Shop::getRootCategory() -> id));
		while ($shop_category = $q -> fetch()) {
			$count_goods = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `in_block` = '0' AND `deleted` = '0' AND `shop_categories` LIKE ?", array("%/".$shop_category -> id."/%"));
			echo Doc::showLink('/shop/category/'.$shop_category -> id, Doc::showImage('/images/folder_blue.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' '.TextUtils::DBFilter($shop_category -> name)." <span>($count_goods)</span>");
		}
		echo Doc::showLink('/shop/search', Doc::showImage('/images/search.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' Поиск товаров');
		echo Doc::showLink('/shop/sellers_rating', Doc::showImage('/images/rating.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' Рейтинг продавцов');
		?>
	</div>
</div>