<?
include(HEAD);
$us = $u;
$sort = 1;
$sort_q = " AND `opened` = '1'";
if (@$_GET['sort'] == 2) {
	$sort = 2;
	$sort_q = " AND `opened` = '0'";
} elseif (@$_GET['sort'] == 3) {
	$sort = 3;
	$sort_q = null;
}
?>
<div class="content s_sort">
	<span class="bord_s1"><?=($sort != 1?"<a href='?sort=1'>":"<span class='no_sel'>")?>Открытые<?=($sort != 1?"</a>":"</span>")?></span>
	<span class="bord_s1"><?=($sort != 2?"<a href='?sort=2'>":"<span class='no_sel'>")?>Закрытые<?=($sort != 2?"</a>":"</span>")?></span>
	<span class="bord_s2"><?=($sort != 3?"<a href='?sort=3'>":"<span class='no_sel'>")?>Все<?=($sort != 3?"</a>":"</span>")?></span>
</div>
<?
$cr = $db -> res("SELECT COUNT(*) FROM `tickets` WHERE `id_user` = ?$sort_q", array($us -> id));
if (!$cr)doc::listEmpty("Список тикетов пуст");
$navi = new navi($cr, "?sort=$sort&");
$stickets = $db -> q("SELECT * FROM `tickets` WHERE `id_user` = ?$sort_q ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($us -> id));
$tickets = array();
while ($ticket = $stickets -> fetch()) {
	$tickets[] = array(
		'data' => $ticket
	);
}
$smarty = new SMX();
$smarty -> assign("tickets", $tickets);
$smarty -> display("list.tickets.tpl");
echo $navi -> show;
?>
<hr>
<div class="mod">
	<? echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))?> <a href="/support/add_ticket">Новый тикет</a><br />
</div>
<?doc::back("В кабинет", "/cab")?>
<?
include(FOOT);
?>