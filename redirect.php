<?
include('core/st.php');
if (!isset($_GET['url'])) {
	header("Location: /");
	exit();
}
header("Location: ".$_GET['url']);
$title = 'Перенаправление';
include(HEAD);
echo "<div class='content'>\n";
echo "Выполняется переход по ссылке <b>".TextUtils::escape($_GET['url'])."</b><br />\n";
if (isset($_SERVER['HTTP_REFERER']))
	echo Doc::showLink($_SERVER['HTTP_REFERER'], 'Отмена');
echo "</div>\n";
include(FOOT);
?>