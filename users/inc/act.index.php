<?
$title = 'Пользователи сайта';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `users_infos`");
if (!$cr)doc::listEmpty("Еще никто не регистрирувался");
$navi = new navi($cr, '?');
$q =$db -> q("SELECT * FROM `users_infos` ORDER BY `id` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page);
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
$navi -> show();
doc::back("На главную", "/");
include(FOOT);
?>