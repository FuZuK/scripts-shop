<?
$title = 'Сменить аватар';
include_once(HEAD);
if (isset($_POST['save']) && ussec::check_p()) {
	$avatar = $_FILES['avatar'];
	if (!$image_src = @imagecreatefromstring(file_get_contents($avatar['tmp_name'])))$error = 'Это не картинка';
	elseif (imagesx($image_src) < $set -> avatar_min_x || imagesy($image_src) < $set -> avatar_min_y)$error = 'Минимальный размер аватара '.$set -> avatar_min_x.'x'.$set -> avatar_min_y;
	else {
		if (!is_dir(DR."images/avatars/".md5($us -> id)))mkdir(DR."images/avatars/".md5($us -> id));
		if (file_exists(DR."images/avatars/".md5($us -> id)."/original.jpg"))unlink(DR."images/avatars/".md5($us -> id)."/original.jpg");
		if (file_exists(DR."images/avatars/".md5($us -> id)."/small_prof.jpg"))unlink(DR."images/avatars/".md5($us -> id)."/small_prof.jpg");
		if (file_exists(DR."images/avatars/".md5($us -> id)."/big_prof.jpg"))unlink(DR."images/avatars/".md5($us -> id)."/big_prof.jpg");
		if (file_exists(DR."images/avatars/".md5($us -> id)."/small_list.jpg"))unlink(DR."images/avatars/".md5($us -> id)."/small_list.jpg");
		if (file_exists(DR."images/avatars/".md5($us -> id)."/big_list.jpg"))unlink(DR."images/avatars/".md5($us -> id)."/big_list.jpg");
		copy($avatar['tmp_name'], DR."images/avatars/".md5($us -> id)."/original.jpg");
		files::imagePreview($avatar['tmp_name'], DR."images/avatars/".md5($us -> id)."/small_prof.jpg", 90, 90, 0);
		files::imagePreview($avatar['tmp_name'], DR."images/avatars/".md5($us -> id)."/big_prof.jpg", 200, 200, 0);
		files::imagePreviewCenter($avatar['tmp_name'], DR."images/avatars/".md5($us -> id)."/small_list.jpg", 40, 40);
		files::imagePreviewCenter($avatar['tmp_name'], DR."images/avatars/".md5($us -> id)."/big_list.jpg", 80, 80);
		alerts::msg_sess("Аватар успешно заменен");
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}
}
echo alerts::error();
if ($u -> avaInstalled()) {
	echo "<div class='content hl2'>\n";
	echo "<span class='form_q'>Текущий аватар:</span><br />\n";
	echo $us -> ava_prof(1)."<br />\n";
	if ($set -> wb)
		echo Doc::showLink('/edit_profile/crop_avatar', '&raquo; Выбрать миниатюру');
	echo "</div>\n";
	echo "<hr>\n";
}
$el = array(
	array('type' => 'title', 'value' => 'Выберите аватар:', 'br' => true), 
	array('type' => 'file', 'name' => 'avatar', 'br' => true, 'alert' => 'Минимальный размер аватара '.$set -> avatar_min_x.'x'.$set -> avatar_min_y.'px'), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'save', 'value' => 'Сохранить')
);
new SMX(array('el' => $el, 'files' => true), 'form.tpl');
Doc::back("Назад", $link_back);
?>