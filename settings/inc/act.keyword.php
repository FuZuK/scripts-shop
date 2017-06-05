<?
$title = 'Ключевое слово';
include_once(HEAD);
if (TextUtils::is_empty($u -> info -> keyword)) {
if (isset($_POST['save']) && ussec::check_p()) {
	$keyword = $_POST['keyword'];
	if (TextUtils::length(trim($keyword)) < 1)$error = 'Введите ключевое слово';
	elseif (TextUtils::length($keyword) > 50)$error = 'Ключевое слово слишком длинное';
	else {
		$db -> q("UPDATE `users_infos` SET `keyword` = ? WHERE `id` = ?", array($keyword, $u -> info -> id));
		if (!isset($_SESSION['i_wont_change_pass_but_empty_keyword'])) {
			alerts::msg_sess("Ключево слово успешно изменено");
			header("Location: /settings");
		} else {
			unset($_SESSION['i_wont_change_pass_but_empty_keyword']);
			alerts::msg_sess("Теперь Вы можете изменить свой пароль");
			header("Location: /settings/pass");
		}
		
		exit();
	}
}
echo alerts::error();
$el = array(
	array('type' => 'title', 'value' => 'Введите ключевое слово:', 'br' => true), 
	array('type' => 'text', 'name' => 'keyword', 'br' => true, 'alert' => 'Не больше 50-ти символов.<br />Внимание! Ключевое слово изменить нельзя. Запишите его куда-нибуть, чтобы не забыть!'), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
);
new SMX(array('el' => $el), 'form.tpl');
} else {
	echo "<div class='content'>\n";
	echo "Вы уже указали свое ключевое слово и изменить его не сможете!<br />\n";
	echo "Если Вы забили свое ключевое слово, обратитесь к администрации сайта.\n";
	echo "</div>\n";
}
Doc::back("Настройки", "/settings");
?>