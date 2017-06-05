<?
// выводим наш текст
$letters = str_split($string);
$x = 5;
foreach ($letters as $letter) {
	$font_size = mt_rand(15, 22);
	$font_color = imageColorAllocateAlpha($img, rand(0, 100), rand(0, 100), rand(0, 100), 1);
	$y = mt_rand(0, 2);
	$anglet = mt_rand(1, 2);
	if ($anglet == 1)$angle = mt_rand(350, 360);
	else $angle = mt_rand(0, 10);
	imageTtfText(
		$img, // изображение
		$font_size, // размер шрифта
		$angle, // угол поворота
		$x, // координаты !нижнего! левого угла по x
		$font_size + $y, // координаты !нижнего! левого угла по y
		$font_color, // цвет текста
		$font, // шрифт
		$letter // текст
	);
	$x += 22;
}

// lines
$pixels = 10;
for ($i = 4; $i <= $img_x; $i += $pixels) {
	imageSetThickness($img, rand(1, 5));
	$line_color = imageColorAllocateAlpha($img, rand(0, 100), rand(0, 100), rand(0, 100), rand(90, 100));
	imageLine(
		$img, 
		$i, 
		0, 
		$i, 
		$img_y, 
		$line_color
	);
}

// рисуем рамку
$border_thickness = 1;
imageSetThickness($img, $border_thickness);
$rectangle_color = imageColorAllocateAlpha($img, rand(0, 100), rand(0, 100), rand(0, 100), 100);
imageRectangle(
	$img, 
	0, 
	0, 
	$img_x - $border_thickness, 
	$img_y - $border_thickness, 
	$rectangle_color
);

// меняем внешний цвет
$rectangle_color = imageColorAllocateAlpha($img, rand(0, 200), rand(0, 200), rand(0, 200), 120);
imageFilledRectangle(
	$img, 
	0, 
	0, 
	$img_x, 
	$img_y, 
	$rectangle_color
);
?>