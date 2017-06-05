<?
$title = 'Контакты';
include_once(HEAD);
if (isset($_POST['save']) && ussec::check_p()) {
	$icq = $_POST['icq'];
	if (!(is_numeric($icq) && TextUtils::length($icq) >= 5 && TextUtils::length($icq) <= 9))
		$error = 'Неверный формат ICQ';
	else {
		$u -> getInfo() -> setData('icq', $icq);
		alerts::msg_sess("Изменения успешно приняты");
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}
}
echo alerts::error();
$el = array(
	array('type' => 'title', 'value' => 'ICQ:', 'br' => true), 
	array('type' => 'text', 'name' => 'icq', 'value' => TextUtils::DBFilter($us -> info -> icq), 'br' => true), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
);
new SMX(array('el' => $el), 'form.tpl');
Doc::back("Назад", $link_back);
?>