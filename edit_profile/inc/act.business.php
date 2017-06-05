<?
$title = 'Занятость и знания';
include_once(HEAD);
if (isset($_POST['save']) && ussec::check_p()) {
	$sost = intval($_POST['state']);
	$specialization = $_POST['specialization'];
	$specialization_add = $_POST['specialization_add'];
	if (!in_array($sost, array(0, 1)))$error = 'Неверное состояние';
	elseif (TextUtils::length($specialization) > 50)$error = 'Название специализации не должно быть таким длинным';
	elseif (TextUtils::length($specialization_add) > 500)$error = 'Опишите вкратце свою специализацию';
	else {
		$db -> q("UPDATE `users_infos` SET `state` = ?, `specialization` = ?, `specialization_add` = ? WHERE `id` = ?", array($sost, $specialization, $specialization_add, $us -> info -> id));
		alerts::msg_sess("Изменения успешно приняты");
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}
}
echo alerts::error();
$el = array(
	array('type' => 'title', 'value' => 'Состояние:', 'br' => true), 
	array('type' => 'select', 'name' => 'state', 'options' => array('Свободен', 'Занят'), 'selected' => $us -> info -> state, 'br' => true), 
	array('type' => 'title', 'value' => 'Специализация:', 'br' => true), 
	array('type' => 'text', 'name' => 'specialization', 'value' => TextUtils::DBFilter($us -> info -> specialization), 'br' => true, 'alert' => 'Не больше 50-ти символов'), 
	array('type' => 'title', 'value' => 'Дополнительно:', 'br' => true), 
	array('type' => 'text', 'name' => 'specialization_add', 'value' => TextUtils::DBFilter($us -> info -> specialization_add), 'br' => true, 'alert' => 'Не больше 500-ти символов'), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
);
new SMX(array('el' => $el), 'form.tpl');
Doc::back("Назад", $link_back);
?>