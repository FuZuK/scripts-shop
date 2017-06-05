<?
$title = 'Кто на сайте?';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `users_infos` WHERE `date_last` > ?", array(time() - 600));
if (!$cr)doc::listEmpty("Никого нет най сайте");
$navi = new navi($cr, '?');
$q =$db -> q("SELECT * FROM `users_infos` WHERE `date_last` > ? ORDER BY `date_last` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array(time() - 600));
$users = array();
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$infos = "Рейтинг: <span class='".($us -> rating > 0?"green":"red")."'>{$us -> rating}</span><br />\n";
	$infos .= "Посл. визит: ".TimeUtils::show($post -> date_last)."<br />\n";
	$users[] = array(
		'us' => $us, 
		'info' => $infos
	);
}
$sets = array('rating' => true, 'hr' => true, 'rating' => true);
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("users", $users);
$smarty -> display("list.users.tpl");
echo $navi -> show;

doc::back("На главную", "/");

include(FOOT);
?>