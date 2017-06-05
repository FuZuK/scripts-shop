<?
$select_month_liders = $db -> q("SELECT * FROM `users` WHERE `month_solds` > 0 ORDER BY `month_solds` DESC LIMIT 3");
if ($select_month_liders -> rowCount()):
?>
<div class="boxWrapper">
	<div class="boxPanel">
		<div class="boxTitle">Лидеры</div>
		<?
		while ($month_lider = $select_month_liders -> fetch()) {
			$us = new Users\User($month_lider -> id);
			echo Doc::showLink('/shop/seller/'.$us -> id, 
			"<b>".$us -> login(0, 0)."</b><br />\n"
			.TextUtils::declension($us -> month_solds, array('продажа', 'продажи', 'продаж')));
		}
		?>
	</div>
</div>
<?endif?>