<?
$title = 'WMID и WMR';
include(HEAD);
$wm_info = new WM_Info();
if (isset($_POST['save']) && ussec::check_p() && !$us -> info -> wmid) {
	$wmid = $_POST['wmid'];
	$wm_info -> getWMIDInfo($wmid);
	if (!(TextUtils::length($wmid) == 12 && is_numeric($wmid)))
		$error = 'Неверный формат WM-идентификатора';
	elseif (!$wm_info -> wmid_new)
		$error = 'Такого WM-идентификатора не существует в системе';
	elseif ($db -> res("SELECT COUNT(*) FROM `users_infos` WHERE `wmid` = ?", array($wmid)))
		$error = 'Этот WMID уже занят';
	else {
		$us -> info -> setData('wmid', $wmid);
		$us -> info -> setData('wm_bl', $wm_info -> bl);
		$us -> info -> setData('wm_attestat', $wm_info -> attestat_name);
		$us -> info -> setData('wm_posclaims', $wm_info -> posclaims);
		$us -> info -> setData('wm_negclaims', $wm_info -> negclaims);
		alerts::msg_sess("Изменения успешно приняты. Теперь укажите свой R кошелек.");
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}
}
if (isset($_POST['save']) && ussec::check_p() && $us -> info -> wmid && !$us -> info -> wmr) {
	$wmr = $_POST['wmr'];
	$wm_info -> getPurseInfo($wmr, 'R');
	if (!(TextUtils::length($wmr) == 12 && is_numeric($wmr)))
		$error = 'Неверный формат R кошелька';
	elseif (!$wm_info -> wmid)
		$error = 'Такого R кошелька не существует в системе';
	elseif ($wm_info -> wmid != $us -> info -> wmid)
		$error = 'Данный R кошелк не пренадлежит WM-идентификатору '.$us -> info -> wmid;
	elseif ($db -> res("SELECT COUNT(*) FROM `users_infos` WHERE `wmr` = ?", array($wmr)))
		$error = 'Этот R кошелек уже занят';
	else {
		$us -> info -> setData('wmr', $wmr);
		alerts::msg_sess("Изменения успешно приняты");
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Ваш WMID', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'wmid', 'value' => TextUtils::DBFilter($us -> info -> wmid), 'disabled' => !TextUtils::is_empty($us -> info -> wmid), 'br' => true, 'alert' => 'Из соображений безопасности WMID изменять нельзя');
if (!TextUtils::is_empty($us -> info -> wmid)) {
	$el_add = array(
		array('type' => 'title', 'value' => 'R кошелек:', 'br' => true), 
		array('type' => 'text', 'name' => 'wmr', 'value' =>  TextUtils::DBFilter($us -> info -> wmr), 'disabled' => !TextUtils::is_empty($us -> info -> wmr), 'br' => true, 'alert' => 'Из соображений безопасности R кошелек изменять нельзя')
	);
	$el = array_merge($el, $el_add);
}
if (TextUtils::is_empty($us -> info -> wmid) || TextUtils::is_empty($us -> info -> wmr))
	$el = array_merge($el, array(
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
	));
new SMX(array('el' => $el), 'form.tpl');
Doc::back("Назад", $link_back);
?>