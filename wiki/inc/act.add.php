<? # by Killer
adminka::accessCheck('wiki_stat_add');
$title .= ' - Добавление статти';
include(HEAD);
$stat_title = null;
$stat_text = null;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$stat_title = $_POST['title'];
	$stat_text = $_POST['text'];
	if (TextUtils::length(trim($stat_title)) < 1)$error = 'Введите заголовок статти';
	elseif (TextUtils::length($stat_title) > 100)$error = 'Заголовок слишком длинный';
	elseif (TextUtils::length(trim($stat_text)) < 1)$error = 'Введите текст статти';
	else {
		$db -> q("INSERT INTO `wiki_stats` (`id_user`, `title`, `text`, `time`) VALUES (?, ?, ?, ?)", array($u -> id, $stat_title, $stat_text, time()));
		$stat_id = $db -> lastInsertId();
		alerts::msg_sess("Стаття успешно добавлена");
		header("Location: /wiki/".$stat_id."/read");
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
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Добавить', 'br' => true);
$sm = new SMX();
$sm -> assign('el', $el);
$sm -> display('form.tpl');
doc::back('Назад', '/wiki');
include(FOOT);