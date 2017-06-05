<?
// константы
$array_defines = array(
	'DR' => $_SERVER['DOCUMENT_ROOT'].'/', 
	'CORE' => $_SERVER['DOCUMENT_ROOT'].'/core/', 
	'LIBS' => $_SERVER['DOCUMENT_ROOT'].'/core/libs/', 
	'HEAD' => $_SERVER['DOCUMENT_ROOT'].'/core/head.php', 
	'FOOT' => $_SERVER['DOCUMENT_ROOT'].'/core/foot.php', 
	'TMP' => $_SERVER['DOCUMENT_ROOT'].'/core/tmp/', 
	'GOODS' => $_SERVER['DOCUMENT_ROOT'].'/core/files/goods/', 
	'CLASSES' => $_SERVER['DOCUMENT_ROOT'].'/core/clss/', 
	'TPLS' => $_SERVER['DOCUMENT_ROOT'].'/core/templates/main', 
	'MODALFILES' => $_SERVER['DOCUMENT_ROOT'].'/core/includes/modal/', 
	'INCLUDS_DIR' => $_SERVER['DOCUMENT_ROOT'].'/core/includes/', 
	'DBK_HOST' => $_SERVER['SERVER_ADDR'], 
	'DBK_NAME' => 'shop', 
	'DBK_USER' => 'root', 
	'DBK_PASS' => '', 
	'URL' => $_SERVER['REQUEST_URI'], 
	'SITE_NAME' => $_SERVER['HTTP_HOST'], 
	'CAPTCHAS_DIR' => $_SERVER['DOCUMENT_ROOT'].'/core/captchas/', 
	'FONTS_DIR' => $_SERVER['DOCUMENT_ROOT'].'/core/fonts/'
);

foreach ($array_defines as $const => $value) {
	define($const, $value, true);
}
?>