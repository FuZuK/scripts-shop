<?
$title = 'Выбор миниатюры аватара';
include_once(HEAD);
if (!$u -> avaInstalled()) {
	alerts::error_sess("Установите аватар");
	header("Location: /edit_profile/change_avatar");
	exit();
}
if (!$set -> wb) {
	alerts::error_sess("Доступно только с компьютера");
	header("Location: /edit_profile/change_avatar");
	exit();
}
$ava = getimagesize(dr."/images/avatars/".md5($u -> id)."/big_prof.jpg");
?>
<link rel="stylesheet" href="/css/imgareaselect/imgareaselect-animated.css">
<script src="/js/imgareaselect/jquery.imgareaselect.dev.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('<div style="float: right;  border: 1px solid;"><img src="/images/avatars/<? echo md5($u -> id)?>/big_prof.jpg" style="position: relative;" /><div>') .css({
		overflow: 'hidden',
		width: '100px',
		height: '100px'
	}) .insertAfter($('#photo'));
	$('#photo').imgAreaSelect({
		aspectRatio: '1:1',
		handles: true,
		onSelectChange: preview,
		onSelectEnd: function ( image, selection ) {
			$('input[name=x1]').val(selection.x1);
			$('input[name=y1]').val(selection.y1);
			$('input[name=w]').val(selection.width);
			$('input[name=h]').val(selection.height);
		}
	});
});
function preview(img, selection) {
	var scaleX = 100 / (selection.width || 1);
	var scaleY = 100 / (selection.height || 1);
	$('#photo + div > img').css({
		width: Math.round(scaleX * <? echo $ava[0]; ?>) + 'px',
		height: Math.round(scaleY * <? echo $ava[1]; ?>) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
	});
}
</script>
<?
if (isset($_POST['submited']) && ussec::check_p() && isset($_POST['x1']) && isset($_POST['w']) && isset($_POST['h'])) {
	$filename = dr."images/avatars/".md5($u -> id)."/big_prof.jpg";
	$new_filename = dr."images/avatars/".md5($u -> id)."/big_list.jpg";
	list($current_width, $current_height) = getimagesize($filename);
	$x1 = floatval($_POST['x1']);
	if ($x1 > $current_width || $x1 < 0)
		$x1 = 0;
	$y1 = floatval($_POST['y1']);
	if ($y1 > $current_height || $y1 < 0)
		$y1 = 0;
	$w = floatval($_POST['w']);
	if ($w < 0 || $w > $current_width)
		$w = $current_width;
	$h = floatval($_POST['h']);
	if ($h < 0 || $h > $current_height)
		$h = $current_height;
	$crop_width = 80;
	$crop_height = 80;
	$new = imagecreatetruecolor($crop_width, $crop_height);
	$current_image = imagecreatefromstring(file_get_contents($filename));
	imagecopyresampled($new, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);
	imagejpeg($new, $new_filename, 100);
	alerts::msg_sess("Миниатюра успешно сохранена");
	header("Location: /edit_profile/change_avatar");
	exit();
}
echo "<div class='content'>\n";
echo "<img id='photo' src='/images/avatars/".md5($u -> id)."/big_prof.jpg' alt='' />\n";
echo "<div class='clear'></div>\n";
$el = array();
$el[] = array('type' => 'hidden', 'name' => 'x1', 'value' => '0');
$el[] = array('type' => 'hidden', 'name' => 'y1', 'value' => '0');
$el[] = array('type' => 'hidden', 'name' => 'w', 'value' => $ava[0]);
$el[] = array('type' => 'hidden', 'name' => 'h', 'value' => $ava[1]);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'submited', 'value' => 'Сохранить');
$sm = new SMX();
$sm -> assign('el', $el);
$sm -> display('form.tpl');
echo "</div>\n";
doc::back('Назад', '/edit_profile/change_avatar');
?>