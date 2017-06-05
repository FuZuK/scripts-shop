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
$category = $good -> getCategory();
$seller = $good -> getSeller();
if (!(isset($u) && $seller -> id == $u -> id || adminka::access('shop_set_previews_good'))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш товар");
	echo Doc::back('Назад', '/user/shop/?act=good&good_id='.$good -> id);
	include(FOOT);
}
$title = 'Список скриншотов';
include(HEAD);
if (isset($_GET['delete']) && $db -> res("SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id_good` = ? AND `id` = ?", array($good -> id, intval($_GET['delete']))) && ussec::check_g()) {
	if ($u -> id != $seller -> id) {
		adminka::adminsLog("Магазин", "Скриншоты", "Удален скриншот к товару \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id={$good -> id}]{$good -> name}[/url]\"");
	}
	$db -> q("DELETE FROM `users_shop_goods_previews` WHERE `id_good` = ? AND `id` = ?", array($good -> id, intval($_GET['delete'])));
	unlink(DR.$set -> goods_previews_dir.$good -> id."_".intval($_GET['delete']).".jpg");
	foreach (array(50, 90, 130, 250, 500) as $key => $value) {
		unlink(DR.$set -> goods_previews_dir.$good -> id."_".intval($_GET['delete'])."_prev_".$value.".jpg");
	}
	header("Location: ?act=previews&good_id=".$good -> id);
		alerts::msg_sess("Скриншот успешно удален");
	exit();
}
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$preview = $_FILES['preview'];
	if (!strstr($preview['type'], "image/"))$error = 'Неверный формат скриншота';
	else {
		if ($u -> id != $seller -> id) {
			adminka::adminsLog("Магазин", "Скриншоты", "Добавлен скриншот к товару \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id={$good -> id}]{$good -> name}[/url]\"");
		}
		$db -> q("INSERT INTO `users_shop_goods_previews` SET `id_good` = ?", array($good -> id));
		$sid = $db -> lastInsertId();
		foreach (array(50, 90, 130, 250, 800) as $key => $value) {
			files::imagePreview($preview['tmp_name'], DR.$set -> goods_previews_dir.$good -> id.'_'.$sid.'_'.$value.'x'.$value.'.'.UsersShop\Shop::PREVIEW_EXTENSION, $value, $value);
		}
		copy($preview['tmp_name'], DR.$set -> goods_previews_dir.$good -> id.'_'.$sid.'_original.'.UsersShop\Shop::PREVIEW_EXTENSION);
		header("Location: ?act=previews&good_id=".$good -> id);
		alerts::msg_sess("Скриншот успешно добавлен");
		exit();
	}
}
echo alerts::error();
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id_good` = ?", array($good -> id));
$navi = new navi($count_results, '?');
if (!$count_results)echo alerts::error("Нет скриншотов");
else {
	echo "<div class='content'>\n";
	$q = $db -> q("SELECT * FROM `users_shop_goods_previews` WHERE `id_good` = ? ORDER BY `id` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($good -> id));
	while ($preview = $q -> fetch()) {
		$preview = new UsersShop\Preview($preview -> id);
		echo "<div class='pl_photo_item rad_tlr rad_blr content_mess' style='width: 30%;'>\n";
		echo "<div class='pl_photo_image_wrap'>\n";
		echo "<div class='main_mage'>\n";
		echo Doc::showImage($preview -> preview_page, array('class' => 'main', 'width' => ($set -> wb?180:90), 'height' => ($set -> wb?180:90)));
		echo "</div>\n";
		echo "</div>\n";
		echo "<div class='pl_photo_image_info overfl_hid'>\n";
		echo Doc::showImage("/images/delete.png", array('width' => ICON_WH, 'height' => ICON_WH, 'class' => ICON_CLASS)).' '.Doc::showLink('?act=previews&good_id='.$good -> id.'&delete='.$preview -> id.'&'.ussec::link(),'Удалить');
		echo "</div>\n";
		echo "</div>\n";
	}
	echo "</div>\n";
	echo $navi -> show;
}
echo "<hr>\n";
echo "<form method='POST' action='' class='content' enctype='multipart/form-data'>\n";
echo "Новый скриншот:<br />\n";
echo "<input type='file' name='preview' class='main_inp rad_tlr rad_blr' /><br />\n";
echo ussec::input();
echo "<input type='submit' name='sfsk' value='Добавить' class='main_sub rad_tlr rad_blr' /><br />\n";
echo "</form>\n";
Doc::back("Назад", "/user/shop/?act=good&good_id=".$good -> id);
include_once(FOOT);
?>