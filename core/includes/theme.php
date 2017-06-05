<?

if (isset($_GET['select_theme']) && in_array(TextUtils::escape($_GET['select_theme']), array('wap', 'web', 'pda'))) {
	$new_theme = TextUtils::escape($_GET['select_theme']);
	if (isset($u))$db -> q("UPDATE `users` SET `theme` = ? WHERE `id` = ?", array($new_theme, $u -> id));
	else $_SESSION['theme'] = $new_theme;
	$backLink = "/";
	if (isset($_SERVER['HTTP_REFERER'])) $backLink = $_SERVER['HTTP_REFERER'];
	header("Location: " . $backLink);
	exit();
}
$theme = new themes();
$theme -> setTheme('wap');
if ($set -> wb)$theme -> setTheme('web');
if (isset($_SESSION['theme']) && in_array($_SESSION['theme'], array('wap', 'web', 'pda')))$theme -> setTheme($_SESSION['theme']);
if (isset($u) && in_array($u -> theme, array('wap', 'web', 'pda'))) {
	$theme -> setTheme($u -> theme);
}
?>