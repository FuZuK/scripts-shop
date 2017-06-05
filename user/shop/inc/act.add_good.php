<?
Users\User::if_user('is_reg');
Users\User::if_user('activated');
if (!$db -> res('SELECT COUNT(*) FROM `users_shop_categories` WHERE `id` = ?', array(intval(@$_GET['category_id'])))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/");
	include(FOOT);
}
$category = $db -> farr('SELECT * FROM `users_shop_categories` WHERE `id` = ?', array(intval($_GET['category_id'])));
$us = new Users\User($category -> id_user);
if (!(isset($u) && $u -> id == $us -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/user/");
	include(FOOT);
}
$title = 'Добавление товара';
include(HEAD);
$exts = $set -> goodsExts;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$price = intval($_POST['price']);
	$copies = intval($_POST['copies']);
	$file = $_FILES['file'];
	preg_match("|^.*\.(.*)|", $file['name'], $ext);
	@$ext = $ext[1];
	$discount = intval($_POST['discount']);
	$discount_price = intval($_POST['discount_price']);
	if (TextUtils::length(trim($name)) < 1)alerts::reg_form_error(1);
	elseif(TextUtils::length($name) > 50)alerts::reg_form_error(1);
	elseif (TextUtils::length(trim($desc)) < 1)alerts::reg_form_error(2);
	elseif (TextUtils::length($desc) > 5000)alerts::reg_form_error(2);
	elseif ($price < 20)alerts::reg_form_error(3);
	elseif ($copies < 1)alerts::reg_form_error(4);
	elseif (!in_array($ext, $exts))$error = 'Файл не является архивом';
	elseif ($file['size'] < 0)$error = 'Размер файла слишком маленький';
	elseif ($file['size'] > 5 * 1024 * 1024)$error = 'Размер файла слишком большой';
	else {
		$db -> q("INSERT INTO `users_shop_goods` (`name`, `desc`, `price`, `copies`, `id_user`, `id_category`, `time_add`, `categories`, `discount`, `discount_price`, `ext`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($name, $desc, $price, $copies, $u -> id, $category -> id, time(), $category -> categories.$category -> id.'/', $discount, $discount_price, $ext));
		$good = new UsersShop\Good($db -> lastInsertId());
		copy($file['tmp_name'], $good -> getFilePath());
		alerts::msg_sess("Товар успешно добавлен");
		header("Location: /user/shop/?act=category&category_id=".$category -> id);
		exit();
	}
}
echo alerts::error();
echo "<div class='content'>\n";
echo "<span class='form_q'>Категория:</span> <a href='/user/shop/?act=category&category_id={$category -> id}'><span class='form_a'>".TextUtils::DBFilter($category -> name)."</span></a>\n";
echo "</div>\n";
echo "<hr>\n";
$el = array();
$el[] = array('type' => 'title', 'value' => 'Название:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'name', 'value' => '', 'alert' => 'Не меньше 1-го и не больше 50-ти символов', 'warning' => alerts::use_form_error(1));
$el[] = array('type' => 'title', 'value' => 'Описание:', 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'desc', 'value' => '', 'alert' => 'Не меньше 1-го и больше 1024-х символов', 'warning' => alerts::use_form_error(2));
$el[] = array('type' => 'title', 'value' => 'Цена:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'price', 'value' => '20', 'alert' => 'Не меньше 20-ти WMR', 'warning' => alerts::use_form_error(3));
$el[] = array('type' => 'title', 'value' => 'Количество копий:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'copies', 'value' => '1', 'alert' => 'Не меньше одной', 'warning' => alerts::use_form_error(4));
$el[] = array('type' => 'title', 'value' => 'Скидка (в %):', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'discount', 'value' => '', 'alert' => 'От 3-х до 50-ти процентов (0 - не действует)', 'warning' => alerts::use_form_error(5));
$el[] = array('type' => 'title', 'value' => 'Сумма покупок:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'discount_price', 'value' => '', 'alert' => 'От 100 WMR, целое число (скидка будет даваться покупателю, который покупал ранее Ваши товары на сумму не меньше этой)', 'warning' => alerts::use_form_error(6));
$el[] = array('type' => 'title', 'value' => 'Файл:', 'br' => true);
$el[] = array('type' => 'file', 'name' => 'file', 'alert' => 'Разрешенные форматы: '.implode(', ', $exts));
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Добавить', 'br' => true);
new SMX(array('el' => $el, 'files' => true), 'form.tpl');
doc::back("Назад", "/user/shop/?act=category&category_id={$category -> id}");
include(FOOT);
?>