<?
$title = 'Настройки отображения';
include_once(HEAD);
if (isset($_POST['save']) && ussec::check_p()) {
	$rop = intval($_POST['rop']);
	if (!($rop >= 3 && $rop <= 30))
		$error = 'Неверное количество результатов на странице';
	else {
		$db -> q("UPDATE `users_infos` SET `rop` = ? WHERE `id` = ?", array($rop, $u -> id));
		alerts::msg_sess("Настройки успешно сохранены");
		header("Location: /settings");
		exit();
	}
}
echo alerts::error();
new SMX(
	array('el' => array(
		array('type' => 'title', 'value' => 'Результатов на странице:', 'br' => true), 
		array('type' => 'text', 'name' => 'rop', 'value' => TextUtils::DBFilter($u -> info -> rop), 'br' => true, 'alert' => 'Не меньше 3-х и не больше 30-ти'), 
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
	)), 'form.tpl'
);
doc::back("Настройки", "/settings");
?>