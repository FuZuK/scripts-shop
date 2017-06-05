<?
adminka::accessCheck('shop_edit_category');
$category = new Shop\Category(intval(@$_GET['category_id']));
$category = new Shop\Category(intval(@$_GET['category_id']));
if (!$category -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Категория не найдена');
	Doc::back('Назад', '/shop/');
	include(FOOT);
}
if ($category -> isRoot()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Нельзя редактировать корневую категорию');
	Doc::back('Назад', '/shop/');
	include(FOOT);
}
$title .= ' - Редактирование категории';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$upload = intval(@$_POST['upload']);
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
	elseif (TextUtils::length($name) > 50)$error = 'Название слишком длинное';
	elseif (TextUtils::length($desc) > 500)$error = 'Описание слишком длинное';
	else {
		if ($category -> name != $name) {
			adminka::adminsLog("Магазин", "Категории", "Переименована категория \"".$category -> name."\" в \"[url=http://$_SERVER[HTTP_HOST]/shop/category/".$category -> id."]".$name."[/url]\"");
		}
		if ($category -> desc != $desc) {
			adminka::adminsLog("Магазин", "Категории", "Изменено описание категории [url=http://$_SERVER[HTTP_HOST]/shop/category/".$category -> id."]".$category -> name."[/url]\"");
		}
		if ($category -> upload != $upload) {
			adminka::adminsLog("Магазин", "Категории", ($upload?"Разрешено":"Запрещено")." выгружать товары в категорию [url=http://$_SERVER[HTTP_HOST]/shop/category/".$category -> id."]".$category -> name."[/url]\"");
		}
		$db ->q("UPDATE `shop_categories` SET `name` = ?, `desc` = ?, `upload` = ? WHERE `id` = ?", array($name, $desc, $upload, $category -> id));
		alerts::msg_sess("Категория успешно отредактирована");
		header("Location: /shop/category/".$category -> id);
		exit();
	}
}
echo alerts::error();
echo "<div class='content'>\n";
echo "<span class='form_q'>Категория:</span> <a href='/shop/category/{$category -> id}'><span class='form_a'>".TextUtils::DBFilter($category -> name)."</span></a>\n";
echo "</div>\n";
echo "<hr>\n";
$el = array();
$el[] = array('type' => 'title', 'value' => 'Название:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'name', 'br' => true, 'value' => TextUtils::DBFilter($category -> name));
$el[] = array('type' => 'title', 'value' => 'Описание:', 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'desc', 'br' => true, 'value' => TextUtils::DBFilter($category -> desc));
$el[] = array('type' => 'checkbox', 'id' => 'upload', 'name' => 'upload', 'value' => '1', 'text' => 'Выгрузка товаров', 'labels' => true, 'checked' => $category -> upload == 1 ? true : false);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Сохранить', 'br' => true);
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
Doc::back("Назад", "/shop/category/{$category -> id}");
include(FOOT);
?>