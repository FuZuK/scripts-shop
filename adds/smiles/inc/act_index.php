<?
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `smiles`");
if (!$count_results)doc::listEmpty("Нет смайлов");
$navi = new navi($count_results, '?');
$query = $db -> q("SELECT * FROM `smiles` ORDER BY `id` ASC LIMIT ".$navi -> start.", ".$set -> results_on_page);
$smiles = array();
while ($post = $query -> fetch()) {
	$smiles[] = array(
		'img' => imgs::show($post -> id.".png", array(), "/images/smiles/"), 
		'content' => $post -> name
	);
}
$sets = array('hr' => true, 'img_left' => true);
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("list_items", $smiles);
$smarty -> display("list.items.tpl");
echo $navi -> show;
include(FOOT);
?>