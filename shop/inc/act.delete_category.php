<?
adminka::accessCheck('shop_delete_category');
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
	echo alerts::error('Нельзя удалять корневую категорию');
	Doc::back('Назад', '/shop/');
	include(FOOT);
}
$title .= ' - Удаление категории';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$category -> delete();
	header("Location: /shop/category/".$category -> id_category);
	exit();
}
echo "<div class='content'>\n";
echo "<span class='form_q'>Категория:</span> <a href='/shop/category/{$category -> id}'><span class='form_a'>".TextUtils::DBFilter($category -> name)."</span></a>\n";
echo "</div>\n";
echo "<hr>\n";
$el = array();
$el[] = array('type' => 'title', 'value' => 'Подтвердите удаление категории:', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Удалить', 'br' => true);
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
Doc::back("Назад", "/shop/category/{$category -> id}");
include(FOOT);
?>