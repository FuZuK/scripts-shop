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
if ($good -> isDeleted() && !adminka::access('shop_view_deleted_goods')) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар удален");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($good -> isBlocked() && !adminka::access('shop_view_blocked_goods')) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар заблокирован");
	doc::back("Назад", "/");
	include(FOOT);
}
$category = $good -> getCategory();
$seller = $good -> getSeller();
if (!(isset($u) && $seller -> id == $u -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш товар");
	echo Doc::back('Назад', '/user/shop/?act=good&good_id='.$good -> id);
	include(FOOT);
}
$title = 'Редактирование товара';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$price = intval($_POST['price']);
	$copies = intval($_POST['copies']);
	$discount = intval($_POST['discount']);
	$discount_price = intval($_POST['discount_price']);
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
	elseif(TextUtils::length($name) > 50)$error = 'Название слишком длинное';
	elseif (TextUtils::length(trim($desc)) < 1)$error = 'Введите описание';
	elseif (TextUtils::length($desc) > 5000)$error = 'Описание слишком длинное';
	elseif ($price < 20)$error = 'Цена не должна быть меньше 20-ти WMR';
	elseif ($copies < 1)$error = 'Количество копий не должно быть меньше ноля';
	else {
		if ($u -> id != $seller -> id) {
			adminka::adminsLog("Магазин", "Товары", "Отредактирован товар \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url]\"");
		}
		$db -> q("UPDATE `users_shop_goods` SET `name` = ?, `desc` = ?, `price` = ?, `copies` = ?, `discount` = ?, `discount_price` = ? WHERE `id` = ?", array($name, $desc, $price, $copies, $discount, $discount_price, $good -> id));
		alerts::msg_sess("Товар успешно отредактирован");
		header("Location: /user/shop/?act=good&good_id=".$good -> id);
		exit();
	}
}
echo alerts::error();
?>
<form method="POST" action="" class="content">
	<span class="form_q">Название:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="name" value="<? echo TextUtils::DBFilter($good -> name);?>"><br />
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Описание:</span><br />
	<textarea class="main_inp rad_tlr rad_blr" name="desc"><? echo TextUtils::DBFilter($good -> desc);?></textarea><br />
	<span class="alert">Не больше 1024-х символов<br /></span>
	<span class="form_q">Цена:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="price" value="<? echo intval($good -> price);?>"><span class="form_q">WMR</span><br />
	<span class="alert">Не меньше 20-ти WMR<br /></span>
	<span class="form_q">Скидка (в %):</span><br />
	<input type="text" name="discount" class="main_inp rad_tlr rad_blr" value="<? echo TextUtils::DBFilter($good -> discount)?>"><br />
	<span class="alert">От 3-х до 50-ти процентов (0 - не действует)<br /></span>
	<span class="form_q">Сумма покупок:</span><br />
	<input type="text" name="discount_price" class="main_inp rad_tlr rad_blr" value="<? echo $good -> discount_price?>"><br />
	<span class="alert">От 100 WMR, целое число (скидка будет даваться покупателю, который покупал ранее Ваши товары на сумму не меньше этой)<br /></span>
	<span class="form_q">Количество копий:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="copies" value="<? echo intval($good -> copies);?>"><br />
	<span class="alert">Не меньше одной<br></span>
	<? echo ussec::input();?>
	<input type="submit" class="main_sub rad_tlr rad_blr" name="sfsk" value="Сохранить"><br />
</form>
<?
CActions::setSeparator("<br />\n");
CActions::setShowType(CActions::SHOW_ALL);
if ($seller -> id == $u -> id || adminka::access('shop_change_archive'))
	CActions::addAction('/user/shop/?act=change_archive&good_id='.$good -> id, 'Заменить архив', '/images/application-zip.png');
if ($seller -> id == $u -> id || adminka::access('shop_delete_good'))
	CActions::addAction('/user/shop/?act=delete_good&good_id='.$good -> id, 'Удалить товар', '/images/delete.png');
if (CActions::getCount() > 0) echo "<hr>\n";
echo CActions::showActions();
doc::back("Назад", "/user/shop/?act=good&good_id=".$good -> id);
include(FOOT);
?>