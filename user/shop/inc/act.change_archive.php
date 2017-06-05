<?
Users\User::if_user('is_reg');
$good = new UsersShop\Good(intval($_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	echo Doc::back('Назад', '/');
	include(FOOT);
}
if ($good -> isDeleted()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар удален");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($good -> isBlocked()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар заблокирован");
	doc::back("Назад", "/");
	include(FOOT);
}
$category = $good -> getCategory();
$seller = $good -> getSeller();
if (!(isset($u) && $seller -> id == $u -> id || adminka::access('shop_set_previews_good'))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш товар");
	echo Doc::back('Назад', '/user/shop/?act=good&good_id='.$good -> id);
	include(FOOT);
}
$title = 'Замена архива';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$good_file = $_FILES['good'];
	preg_match("|^.*\.(.*)|", $good_file['name'], $ext);
	$ext = @$ext[1];
	if (!in_array($ext, $set -> goodsExts))$error = 'Файл не является архивом';
	elseif ($good_file['size'] < 0)$error = 'Размер файла слишком маленький';
	elseif ($good_file['size'] > 5*1024*1024)$error = 'Размер файла слишком большой';
	else {
		$db -> q("UPDATE `users_shop_goods` SET `time_update_archive` = ?, `ext` = ? WHERE `id` = ?", array(time(), $ext, $good -> id));
		if ($u -> id != $seller -> id) {
			adminka::adminsLog("Магазин", "Товары", "Заменен архив товара \"[url=http://$_SERVER[HTTP_HOST]/use/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url]\"");
		}
		unlink($good -> getFilePath());
		copy($good_file['tmp_name'], $good -> getFilePath());
		alerts::msg_sess("Архив успешно обновлен");
		header("Location: /user/shop/?act=edit_good&good_id=".$good -> id);
		exit();
	}
}
echo alerts::error();
$el = array(
	array('type' => 'title', 'value' => 'Файл:', 'br' => true), 
	array('type' => 'file', 'name' => 'good', 'br' => 'true', 'alert' => 'Разрешенные форматы: '.implode(', ', $set -> goodsExts)), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Заменить')
);
new SMX(array('el' => $el, 'files' => true), 'form.tpl');
doc::back("Назад", "/shop/good/{$good -> id}");
include(FOOT);
?>