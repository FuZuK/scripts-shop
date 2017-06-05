<?
$title = 'Оповещения';
include_once(HEAD);
$options = array('Не получать', 'Получать');
$options_keys = array_keys($options);
$ns = $u -> getNotificationsSettings();
if (isset($_POST['save']) && ussec::check_p()) {
	$ns -> setData('sms_shop_good_buy', in_array(intval(@$_POST['sms_shop_good_buy']), $options_keys) ? intval(@$_POST['sms_shop_good_buy']) : 0);
	$ns -> setData('sms_shop_good_comment', in_array(intval(@$_POST['sms_shop_good_comment']), $options_keys) ? intval(@$_POST['sms_shop_good_comment']) : 0);
	$ns -> setData('sms_shop_good_bad_review', in_array(intval(@$_POST['sms_shop_good_bad_review']), $options_keys) ? intval(@$_POST['sms_shop_good_bad_review']) : 0);
	$ns -> setData('sms_new_mess', in_array(intval(@$_POST['sms_new_mess']), $options_keys) ? intval(@$_POST['sms_new_mess']) : 0);
	$ns -> setData('email_shop_good_buy', in_array(intval(@$_POST['sms_shop_good_buy']), $options_keys) ? intval(@$_POST['sms_shop_good_buy']) : 0);
	$ns -> setData('email_shop_good_comment', in_array(intval(@$_POST['sms_shop_good_buy']), $options_keys) ? intval(@$_POST['sms_shop_good_buy']) : 0);
	$ns -> setData('email_shop_good_bad_review', in_array(intval(@$_POST['sms_shop_good_buy']), $options_keys) ? intval(@$_POST['sms_shop_good_buy']) : 0);
	$ns -> setData('email_new_mess', in_array(intval(@$_POST['sms_shop_good_buy']), $options_keys) ? intval(@$_POST['sms_shop_good_buy']) : 0);
	alerts::msg_sess('Изменения успешно сохранены');
	header('Location: ?');
	exit();
}
echo "<div class='content_redi'>\n";
$smsc_configs = new configs('smsc.dat');
echo "Оповещения по СМС платные. За каждое СМС с личного счета снимается сумма в размере {$smsc_configs -> sms_price} WMR. Если на счету не хватает денег - оповещения не приходят. ";
echo "Оповещения приходят только если пользователя нету на сайте. ";
echo "СМС оповещения приходят только один раз для каждого раздела.\n";
echo "</div>\n";
$el = array(
	array('type' => 'title', 'value' => '<b>СМС оповещения</b>', 'br' => true), 
	array('type' => 'title', 'value' => 'Покупка товара:', 'br' => true), 
	array('type' => 'select', 'name' => 'sms_shop_good_buy', 'options' => $options, 'selected' => $ns -> sms_shop_good_buy, 'br' => true), 
	array('type' => 'title', 'value' => 'Ответ на комментарий к чужому товару:', 'br' => true), 
	array('type' => 'select', 'name' => 'sms_shop_good_comment', 'options' => $options, 'selected' => $ns -> sms_shop_good_comment, 'br' => true), 
	array('type' => 'title', 'value' => 'Притензия на товар:', 'br' => true), 
	array('type' => 'select', 'name' => 'sms_shop_good_bad_review', 'options' => $options, 'selected' => $ns -> sms_shop_good_bad_review, 'br' => true), 
	array('type' => 'title', 'value' => 'Личное сообщение:', 'br' => true), 
	array('type' => 'select', 'name' => 'sms_new_mess', 'options' => $options, 'selected' => $ns -> sms_new_mess, 'br' => true), 
	array('type' => 'title', 'value' => '<b>E-mail оповещения</b>', 'br' => true), 
	array('type' => 'title', 'value' => 'Покупка товара:', 'br' => true), 
	array('type' => 'select', 'name' => 'email_shop_good_buy', 'options' => $options, 'selected' => $ns -> email_shop_good_buy, 'br' => true), 
	array('type' => 'title', 'value' => 'Ответ на комментарий к чужому товару:', 'br' => true), 
	array('type' => 'select', 'name' => 'semail_shop_good_comment', 'options' => $options, 'selected' => $ns -> email_shop_good_comment, 'br' => true), 
	array('type' => 'title', 'value' => 'Притензия на товар:', 'br' => true), 
	array('type' => 'select', 'name' => 'email_shop_good_bad_review', 'options' => $options, 'selected' => $ns -> email_shop_good_bad_review, 'br' => true), 
	array('type' => 'title', 'value' => 'Личное сообщение:', 'br' => true), 
	array('type' => 'select', 'name' => 'email_new_mess', 'options' => $options, 'selected' => $ns -> email_new_mess, 'br' => true), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить', 'br' => true)
);
new SMX(array('el' => $el), 'form.tpl');
Doc::back('Настройки', '/settings');
include_once(FOOT);
?>