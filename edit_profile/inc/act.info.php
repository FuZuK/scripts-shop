<?
$title = 'Личные данные';
include_once(HEAD);
$prefix = 'Birth_';
if (isset($_POST['save']) && ussec::check_p()) {
	$name = $_POST['name'];
	$city = $_POST['city'];
	$birth_day = $_POST[$prefix.'day'];
	$birth_month = $_POST[$prefix.'month'];
	$birth_year = $_POST[$prefix.'year'];
	$birth_date = mktime(0, 0, 0, $birth_month, $birth_day, $birth_year);
	if (TextUtils::length($name) > 32)alerts::reg_form_error(1);
	elseif (TextUtils::length($city) > 32)alerts::reg_form_error(2);
	elseif (!checkdate($birth_month, $birth_day, $birth_year))$error = 'Неверный формат даты рождения';
	else {
		$db -> q("UPDATE `users_infos` SET `name` = ?, `city` = ?, `birth_day` = ?, `birth_month` = ?, `birth_year` = ? WHERE `id` = ?", array($name, $city, intval($birth_day), intval($birth_month), intval($birth_year), $us -> info -> id));
		alerts::msg_sess("Изменения успешно приняты");
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Имя:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'name', 'value' => TextUtils::DBFilter($us -> info -> name), 'alert' => 'Не больше 32-х символов', 'warning' => alerts::use_form_error(1));
$el[] = array('type' => 'title', 'value' => 'Дата рождения:', 'br' => true);
$el[] = array('type' => 'select_date', 'start_year' => 1800, 'end_year' => -5, 'prefix' => $prefix, 'selected_day' => $us -> info -> birth_day, 'selected_month' => $us -> info -> birth_month, 'selected_year' => $us -> info -> birth_year, 'br' => true);
$el[] = array('type' => 'title', 'value' => 'Родной город:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'city', 'value' => TextUtils::DBFilter($us -> info -> city), 'alert' => 'Не больше 32-х символов', 'warning' => alerts::use_form_error(2));
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить');
new SMX(array('el' => $el), 'form.tpl');
Doc::back("Назад", $link_back);
?>