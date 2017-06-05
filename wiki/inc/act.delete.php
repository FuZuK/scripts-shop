<? # by Killer
adminka::accessCheck('wiki_stat_delete');
$stat = $db -> farr("SELECT * FROM `wiki_stats` WHERE `id` = ?", array(intval($_GET['stat_id'])));
if (!@$stat -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Стаття не найдена');
	doc::back('Назад', '/wiki');
	include(FOOT);
}
$title .= ' - Удаление статти';
include(HEAD);
$stat_title = $stat -> title;
$stat_text = $stat -> text;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$db -> q("DELETE FROM `wiki_stats` WHERE `id` = ?", array($stat -> id));
	alerts::msg_sess("Стаття успешно удалена");
	header("Location: /wiki");
	exit();
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Вы уверенны, что хотите удалить выбранную статтю?', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Да, уверен', 'br' => true);
$sm = new SMX();
$sm -> assign('el', $el);
$sm -> display('form.tpl');
doc::back('Назад', '/wiki/'.$stat -> id.'/read');
include(FOOT);