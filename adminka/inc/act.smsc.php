<?
adminka::accessCheck('adminka_smsc');
$title .= ' - Управление СМС Центром';
include(HEAD);
switch (@$_GET['sel']) {
	case 'sms_status':
	if (!$db -> res('SELECT COUNT(*) FROM `smsc_messages` WHERE `id` = ?', array(intval(@$_GET['sms_id'])))) {
		echo alerts::error('Сообщение не найдено');
		Doc::back('Назад', '?act=smsc');
		include_once(FOOT);
	}
	$sms = $db -> farr('SELECT * FROM `smsc_messages` WHERE `id` = ?', array(intval(@$_GET['sms_id'])));
	echo "<div class='content'>\n";
	$result = SMSC_Api::get_status($sms -> id_sms, $sms -> phone);
	if (!isset($result -> error_code)) {
		echo "<span class='form_q'>Статус:</span> <span class='form_a'>".SMSC_Api::$sms_statuses[$result -> status]."</span><br />\n";
	} else {
		echo "Произошла ошибка!<br />\n";
		echo "Код ошибки: {$result -> error_code} - {$result -> error}<br />\n";
	}
	echo "</div>\n";
	Doc::back('Назад', '?act=smsc&sel=messages');
	break;
	case 'messages':
	$cr = $db -> res('SELECT COUNT(*) FROM `smsc_messages`');
	$navi = new navi($cr, '?act=smsc&');
	if (!$cr)echo alerts::list_empty('Список СМС пуст');
	$q = $db -> q('SELECT * FROM `smsc_messages` ORDER BY `time_add` DESC LiMIT '.$navi -> start.', '.$set -> results_on_page);
	$items = array();
	while ($post = $q -> fetch()) {
		$content = Doc::showLink('?act=smsc&sel=sms_status&sms_id='.$post -> id, "#{$post -> id_sms}");
		$content .= "<div>\n";
		$content .= TextUtils::DBFilter($post -> message);
		$content .= "</div>\n";
		$content .= "На номер <b>".TextUtils::DBFilter($post -> phone)."</b> (".TImeUtils::show($post -> time_add).")\n";
		$items[] = array(
			'content' => $content
		);
	}
	new SMX(array('list_items' => $items, 'sets' => array('hr' => true)), 'list.items.tpl');
	echo $navi -> show;
	Doc::back('Назад', '?act=smsc');
	break;
	case 'balance':
	echo "<div class='content'>\n";
	$result = SMSC_Api::get_balance();
	if (!isset($result -> error_code)) {
		echo "<span class='form_q'>Баланс:</span> <span class='form_a'>{$result -> balance} WMR</span><br />\n";
	} else {
		echo "Произошла ошибка!<br />\n";
		echo "Код ошибки: {$result -> error_code} - {$result -> error}<br />\n";
	}
	echo "</div>\n";
	Doc::back('Назад', '?act=smsc');
	break;
	default:
	$smsc_configs = new configs('smsc.dat');
	if (isset($_POST['save']) && ussec::check_p()) {
		$smsc_configs -> login = $_POST['smsc_login'];
		$smsc_configs -> password = $_POST['smsc_password'];
		$smsc_configs -> post = $_POST['smsc_post'];
		$smsc_configs -> https = $_POST['smsc_https'];
		$smsc_configs -> charset = $_POST['smsc_charset'];
		$smsc_configs -> smtp_from = $_POST['smsc_smtp_from'];
		$smsc_configs -> sms_price = floor($_POST['sms_price']);
		$smsc_configs -> save();
		alerts::msg_sess('Изминения успешно сохранены');
		header('Location: ?act=smsc');
		exit();
	}
	$el = array(
		array('type' => 'title', 'value' => 'Логин:', 'br'=> true), 
		array('type' => 'text', 'name' => 'smsc_login', 'value' => TextUtils::DBFilter($smsc_configs -> login), 'br'=> true), 
		array('type' => 'title', 'value' => 'Пароль:', 'br'=> true), 
		array('type' => 'text', 'name' => 'smsc_password', 'value' => TextUtils::DBFilter($smsc_configs -> password), 'br'=> true), 
		array('type' => 'title', 'value' => 'Отправлять данные через POST:', 'br'=> true), 
		array('type' => 'text', 'name' => 'smsc_post', 'value' => TextUtils::DBFilter($smsc_configs -> post), 'br'=> true), 
		array('type' => 'title', 'value' => 'Использовать SSL:', 'br'=> true), 
		array('type' => 'text', 'name' => 'smsc_https', 'value' => TextUtils::DBFilter($smsc_configs -> https), 'br'=> true), 
		array('type' => 'title', 'value' => 'Кодировка:', 'br'=> true), 
		array('type' => 'text', 'name' => 'smsc_charset', 'value' => TextUtils::DBFilter($smsc_configs -> charset), 'br'=> true),
		array('type' => 'title', 'value' => 'E-mail SMTP отправщика:', 'br'=> true), 
		array('type' => 'text', 'name' => 'smsc_smtp_from', 'value' => TextUtils::DBFilter($smsc_configs -> smtp_from), 'br'=> true), 
		array('type' => 'title', 'value' => 'Стоимость СМС (WMR):', 'br' => true), 
		array('type' => 'text', 'name' => 'sms_price', 'value' => TextUtils::DBFilter($smsc_configs -> sms_price), 'br' => true), 
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
	);
	new SMX(array('el' => $el), 'form.tpl');
	echo "<hr>\n";
	CActions::setSeparator('<br>');
	CActions::setShowType(CActions::SHOW_ALL);
	CActions::addAction('?act=smsc&sel=balance', 'Баланс', '/images/money.png');
	CActions::addAction('?act=smsc&sel=messages', 'Список СМС', '/images/mail.png');
	echo CActions::showActions();
	Doc::back('В админку', '/adminka');
	break;
}
?>