<?
include('../core/st.php');
$img = imageCreateTrueColor(110, 30);
$img_x = imagesx($img);
$img_y = imagesy($img);
$white = imageColorAllocate($img, 255, 255, 255);
imageFill($img, 1, 1, $white);

$fonts_dir = $_SERVER['DOCUMENT_ROOT'].'/core/fonts';
$captchas_dir = $_SERVER['DOCUMENT_ROOT'].'/core/captchas';
// находим шрифты
$dir_fonts = opendir($fonts_dir);
$fonts = array();
while ($file = readdir($dir_fonts)) {
	if ($file != '.' && $file != '..' && preg_match("|^.*\.ttf$|", $file, $file2)) {
		$fonts[] = $file;
	}
}

// находим капчи
$dir_captchas = opendir($captchas_dir);
$captchas = array();
while ($file = readdir($dir_captchas)) {
	if ($file != '.' && $file != '..' && preg_match("|^.*\.php$|", $file, $file2)) {
		$captchas[] = $file;
	}
}

// выбираем случайный шрифт
$font = $fonts_dir.'/'.$fonts[mt_rand(0, count($fonts) - 1)];

// выбираем случайную капчу
$captcha = $captchas_dir.'/'.$captchas[mt_rand(0, count($captchas) - 1)];

$use_symbols = "123456790abcdefghijhkmntwpuvxyz";
$string = null;
for ($i = 1; $i <= 5; $i++) {
	$string .= $use_symbols{mt_rand(0, strlen($use_symbols) - 1)};
}
$_SESSION['captcha'] = $string;
$_SESSION['font'] = $font;

include_once($captcha);

// to browser
header("Content-type: image/png");
imagepng($img);
?>