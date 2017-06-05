<? # by Killer
adminka::accessCheck('wiki_stat_edit');
$stat = $db -> farr("SELECT * FROM `wiki_stats` WHERE `id` = ?", array(intval($_GET['stat_id'])));
if (!@$stat -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Стаття не найдена');
	doc::back('Назад', '/wiki');
	include(FOOT);
}
$title .= ' - Редактирование статти';
include(HEAD);
$stat_title = $stat -> title;
$stat_text = $stat -> text;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$stat_title = $_POST['title'];
	$stat_text = $_POST['text'];
	if (TextUtils::length(trim($stat_title)) < 1)$error = 'Введите заголовок статти';
	elseif (TextUtils::length($stat_title) > 100)$error = 'Заголовок слишком длинный';
	elseif (TextUtils::length(trim($stat_text)) < 1)$error = 'Введите текст статти';
	else {
		$db -> q("UPDATE `wiki_stats` SET `title` = ?, `text` = ? WHERE `id` = ?", array($stat_title, $stat_text, $stat -> id));
		alerts::msg_sess("Стаття успешно отредактирована");
		header("Location: /wiki/".$stat -> id."/read");
		exit();
	}
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Заголовок:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'title', 'value' => TextUtils::DBFilter($stat_title), 'alert' => 'Не меньше 1-го и не больше 100-а символов');
$el[] = array('type' => 'title', 'value' => 'Текст:', 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'text', 'value' => TextUtils::DBFilter($stat_text), 'alert' => 'Не должно быть пустым');
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Сохранить', 'br' => true);
$sm = new SMX();
$sm -> assign('el', $el);
$sm -> display('form.tpl');
doc::back('Назад', '/wiki/'.$stat -> id.'/read');
include(FOOT);