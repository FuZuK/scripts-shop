<?
$title = 'Гости на сайте';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ?", array(time() - 600));
if (!$cr)doc::listEmpty("Никого нет най сайте");
$navi = new navi($cr, '?');
$q =$db -> q("SELECT * FROM `guests` WHERE `date_last` > ? ORDER BY `date_last` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array(time() - 600));
$guests = array();
while ($post = $q -> fetch()) {
	$content = "<span class='form_q'>Посл. визит:</span> ".TimeUtils::show($post -> date_last)."<br />\n";
	$content .= "<span class='form_q'>IP:</span> ".TextUtils::escape($post -> ip)."<br />\n";
	$content .= "<span class='form_q'>Браузер:</span> ".TextUtils::escape($post -> browser)."<br />\n";
	$guests[] = array(
		'content' => $content
	);
}
$sets = array('img' => imgs::show("guest.png", array('height' => ($set -> wb?80:40), 'width' => ($set -> wb?80:40))), 'img_left' => true, 'hr' => true);
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("list_items", $guests);
$smarty -> display("list.items.tpl");
echo $navi -> show;

doc::back("На главную", "/");

include(FOOT);
?>