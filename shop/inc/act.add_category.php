<?
adminka::accessCheck('shop_add_category');
$title .= ' - Добавление категории';
$category = new Shop\Category(intval(@$_GET['category_id']));
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$upload = intval(@$_POST['upload']);
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
	elseif (TextUtils::length($name) > 50)$error = 'Название слишком длинное';
	elseif (TextUtils::length($desc) > 500)$error = 'Описание слишком длинное';
	else {
		$names_array = explode('/', $name);
		if (count($names_array) > 0) {
			$prevent_category = $category;
			foreach ($names_array as $number => $new_category_name) {
				$new_category_upload = ($number + 1 == count($names_array) ? $upload : 0);
				if (!$db -> res('SELECT COUNT(*) FROM `shop_categories` WHERE `id_category` = ? AND `name` = ?', array($prevent_category -> id, $new_category_name))) {
					$pos = $db -> res("SELECT MAX(`pos`) FROM `shop_categories` WHERE `id_category` = ?", array($prevent_category -> id)) + 1;
					$db -> q("INSERT INTO `shop_categories` (`name`, `desc`, `id_category`, `categories`, `pos`, `upload`) VALUES (?, ?, ?, ?, ?, ?)", array($new_category_name, '', $prevent_category -> id, $prevent_category -> categories.$prevent_category -> id.'/', $pos, $new_category_upload));
					$prevent_category = new Shop\Category($db -> lastInsertId());
					adminka::adminsLog("Магазин", "Категории", "Создание категории \"[url=http://$_SERVER[HTTP_HOST]/shop/category/{$prevent_category -> id}]".$prevent_category -> name."[/url]\"");
				} else {
					$prevent_category = Shop\Shop::findCategoryByName($new_category_name, $prevent_category -> id);
					$db -> q('UPDATE `shop_categories` SET `upload` = ? WHERE `id` = ?', array($new_category_upload, $prevent_category -> id));
				}
			}
		} else {
			if (!$db -> res('SELECT COUNT(*) FROM `shop_categories` WHERE `id_category` = ? AND `name` = ?', array($category -> id, $name))) {
				$pos = $db -> res("SELECT MAX(`pos`) FROM `shop_categories` WHERE `id_category` = ?", array($category -> id)) + 1;
				$db -> q("INSERT INTO `shop_categories` (`name`, `desc`, `id_category`, `categories`, `pos`, `upload`) VALUES (?, ?, ?, ?, ?, ?)", array($name, $desc, $category -> id, $category -> categories.$category -> id.'/', $pos, $upload));
				$category_id = $db -> lastInsertId();
				adminka::adminsLog("Магазин", "Категории", "Создание категории \"[url=http://$_SERVER[HTTP_HOST]/shop/category/$category_id]".$name."[/url]\"");
			}
			alerts::msg_sess("Категория успешно добавлена");
		}
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
$el[] = array('type' => 'text', 'name' => 'name', 'br' => true);
$el[] = array('type' => 'title', 'value' => 'Описание:', 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'desc', 'br' => true);
$el[] = array('type' => 'checkbox', 'id' => 'upload', 'name' => 'upload', 'value' => '1', 'text' => 'Выгрузка товаров', 'labels' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Добавить', 'br' => true);
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
Doc::back("Назад", "/shop/category/{$category -> id}");
include(FOOT);
?>