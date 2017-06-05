<div class="title">
	Лидеры месяца
</div>
<?
$select_month_liders = $db -> q("SELECT * FROM `users` WHERE `month_solds` > 0 ORDER BY `month_solds` DESC LIMIT 3");
if (!$select_month_liders -> rowCount()) {
	?>
	<div class="content_mess">
		Нет лидеров
	</div>
	<?
}
while ($month_lider = $select_month_liders -> fetch()) {
	$us = new Users\User($month_lider -> id);
	?>
	<div class="content_mess">
		<? echo $us -> login(1).$us -> rating()?><br />
		<? echo TextUtils::declension($us -> month_solds, array('продажа', 'продажи', 'продаж'))?><br />
	</div>
	<?
}
?>