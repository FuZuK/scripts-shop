<? # by Killer
header("Content-type: text/plain");
if (isset($_SERVER['QUERY_STRING'])) {
	$QUERY_STRING = htmlspecialchars($_SERVER['QUERY_STRING']);
	$exp = explode(',', $QUERY_STRING);
	foreach ($exp as $js_file) {
		if (file_exists($js_file) && preg_match("|^.*\.js$|", $js_file))echo "\r\n\r\n\r\n\r\n/* ".htmlspecialchars($js_file)." */\r\n\r\n\r\n\r\n".file_get_contents($js_file);
	}
}
?>