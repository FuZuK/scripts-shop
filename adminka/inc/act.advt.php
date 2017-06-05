<? # by Killer
adminka::accessCheck('adminka_advt_sets');
$title .= ' - Настройки рекламы';
include(HEAD);
switch (@$_GET['mod']):
##### добавление рекламы
case 'add':
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$name = $_POST['name'];
		$link = $_POST['link'];
		$icon = $_POST['icon'];
		$day = intval($_POST['day']);
		$month = intval($_POST['month']);
		$year = intval($_POST['year']);
		if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
		elseif (TextUtils::length(trim($link)) < 1)$error = 'Введите ссылку';
		elseif (!checkdate($month, $day, $year))$error = 'Неверная дата';
		elseif (mktime(23, 59, 59, $month, $day, $year) < time())$error = 'Дата уже прошла';
		else {
			$time_to = mktime(23, 59, 59, $month, $day, $year);
			$db -> q("INSERT INTO `advt` (`name`, `link`, `icon`, `time`, `time_to`) VALUES (?, ?, ?, ?, ?)", array($name, $link, $icon, time(), $time_to));
			alerts::msg_sess("Ссылка успешно добавлена");
			header("Location: ?act=advt");
			exit();
		}
	}
	echo alerts::error();
	$el = array();
	$el[] = array('type' => 'title', 'value' => 'Название:', 'br' => true);
	$el[] = array('type' => 'text', 'name' => 'name', 'value' => '', 'alert' => 'Не меньше 1-го символа');
	$el[] = array('type' => 'title', 'value' => 'Ссылка:', 'br' => true);
	$el[] = array('type' => 'text', 'name' => 'link', 'value' => '', 'alert' => 'Не меньше 1-го символа');
	$el[] = array('type' => 'title', 'value' => 'Иконка:', 'br' => true);
	$el[] = array('type' => 'text', 'name' => 'icon', 'br' => true);
	$el[] = array('type' => 'title', 'value' => 'До:', 'br' => true);
	$el[] = array('type' => 'select_date', 'start_year' => date("Y"), 'end_year' => date("Y")+10, 'selected_day' => 1, 'selected_month' => 1, 'selected_year' => date("Y"), 'br' => 1);
	$el[] = array('type' => 'ussec');
	$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Добавить', 'br' => true);
	new SMX(array('el' => $el), 'form.tpl');
	doc::back('Назад', '?act=advt');
	include(FOOT);
	break;
####### удаление рекламы
case 'delete':
	if (isset($_GET['advt_id']) && $db -> res("SELECT COUNT(*) FROM `advt` WHERE `id` = ?", array(intval($_GET['advt_id'])))) {
		$advt = $db -> farr("SELECT * FROM `advt` WHERE `id` = ?", array(intval($_GET['advt_id'])));
		if (isset($_POST['sfsk']) && ussec::check_p()) {
			$db -> q("DELETE FROM `advt` WHERE `id` = ?", array($advt -> id));
			alerts::msg_sess("Ссылка успешно удалена");
			header("Location: ?act=advt");
			exit();
		}
		echo alerts::error();
		$el = array();
		$el[] = array('type' => 'title', 'value' => 'Вы действительно хотите удалить выбранную ссылку?', 'br' => true);
		$el[] = array('type' => 'ussec');
		$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Да, хочу', 'br' => true);
		new SMX(array('el' => $el), 'form.tpl');
		doc::back('Назад', '?act=advt');
		include(FOOT);
	}
	break;
####### редактирование рекламы
case 'edit':
	if (isset($_GET['advt_id']) && $db -> res("SELECT COUNT(*) FROM `advt` WHERE `id` = ?", array(intval($_GET['advt_id'])))) {
		$advt = $db -> farr("SELECT * FROM `advt` WHERE `id` = ?", array(intval($_GET['advt_id'])));
		if (isset($_POST['sfsk']) && ussec::check_p()) {
			$name = $_POST['name'];
			$link = $_POST['link'];
			$icon = $_POST['icon'];
			$day = intval($_POST['day']);
			$month = intval($_POST['month']);
			$year = intval($_POST['year']);
			if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
			elseif (TextUtils::length(trim($link)) < 1)$error = 'Введите ссылку';
			elseif (!checkdate($month, $day, $year))$error = 'Неверная дата';
			elseif (mktime(23, 59, 59, $month, $day, $year) < time())$error = 'Дата уже прошла';
			else {
				$time_to = mktime(23, 59, 59, $month, $day, $year);
				$db -> q("UPDATE `advt` SET `name` = ?, `link` = ?, `icon` = ?, `time_to` = ? WHERE `id` = ?", array($name, $link, $icon, $time_to, $advt -> id));
				alerts::msg_sess("Ссылка успешно отредактирована");
				header("Location: ?act=advt");
				exit();
			}
		}
		echo alerts::error();
		$el = array();
		$day_selected = date("d", $advt -> time_to);
		$month_selected = date("m", $advt -> time_to);
		$year_selected = date("Y", $advt -> time_to);
		$el[] = array('type' => 'title', 'value' => 'Название:', 'br' => true);
		$el[] = array('type' => 'text', 'name' => 'name', 'value' => TextUtils::DBFilter($advt -> name), 'alert' => 'Не меньше 1-го символа');
		$el[] = array('type' => 'title', 'value' => 'Ссылка:', 'br' => true);
		$el[] = array('type' => 'text', 'name' => 'link', 'value' => TextUtils::DBFilter($advt -> link), 'alert' => 'Не меньше 1-го символа');
		$el[] = array('type' => 'title', 'value' => 'Иконка:', 'br' => true);
		$el[] = array('type' => 'text', 'name' => 'icon', 'value' => TextUtils::DBFilter($advt -> icon), 'br' => true);
		$el[] = array('type' => 'title', 'value' => 'До:', 'br' => true);
		$el[] = array('type' => 'select_date', 'start_year' => date("Y"), 'end_year' => date("Y")+10, 'selected_day' => $day_selected, 'selected_month' => $month_selected, 'selected_year' => $year_selected, 'br' => 1);
		$el[] = array('type' => 'ussec');
		$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Сохранить', 'br' => true);
		new SMX(array('el' => $el), 'form.tpl');
		doc::back('Назад', '?act=advt');
		include(FOOT);
	}
	break;
default:
	$cr = $db -> res("SELECT COUNT(*) FROM `advt`");
	$navi = new navi($cr, '?act=advt');
	$q = $db -> q("SELECT * FROM `advt` ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page);
	$items = array();
	while ($post  = $q -> fetch()) {
		$cont = "<b>".TextUtils::escape($post -> name)."</b><br />\n";
		$cont .= "Ссылка: ".TextUtils::escape($post -> link)."<br />\n";
		if ($post -> icon)
			$cont .= "Иконка: ".TextUtils::escape($post -> icon)."<br />\n";
		$cont .= "До: ".TimeUtils::show($post -> time_to)."<br />\n";
		$actions = array();
		$actions[] = array('name' => 'Редактировать', 'link' => '?act=advt&mod=edit&advt_id='.$post -> id);
		$actions[] = array('name' => 'Удалить', 'link' => '?act=advt&mod=delete&advt_id='.$post -> id);
		$items[] = array('content' => $cont, 'actions' => $actions);
	}
	$sm = new SMX();
	$sm -> assign('list_items', $items);
	$sm -> assign('sets', array('hr' => true));
	$sm -> display('list.items.tpl');
	CActions::setShowType(CActions::SHOW_ALL);
	CActions::setSeparator("<br />\n");
	CActions::addAction('?act=advt&mod=add', 'Добавить', '/images/add1.png');
	if (CActions::getCount() > 0) echo "<hr>\n";
	echo CActions::showActions();
	Doc::back('В админку', '?act=index');
	include(FOOT);
	break;
endswitch;
?>