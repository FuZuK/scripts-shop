<?
Users\User::if_user('is_reg');
$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array(intval($_GET['topic_id'])));
if (!$topic -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Топик не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$author = new Users\User($topic -> id_user);
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array($topic -> id_cat));
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
if (!($u -> id == $author -> id && $topic -> time + 600 > time() || adminka::access('forum_edit_topic'))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("У Вас нет прав для редактирования этого топика.");
	doc::back("Назад", "/forum/t/{$topic -> id}");
	include(FOOT);
}
$title .= ' - Редактирование топика';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$them = $_POST['them'];
	$text2 = $_POST['text'];
	$lock = intval(@$_POST['lock']);
	$pin = intval(@$_POST['pin']);
	if (!($pin >= 0 && $pin <= 9))$pin = 0;
	if (TextUtils::length(trim($them)) < 1)$error = 'Введите тему топика';
	elseif (TextUtils::length($them) > 50)$error = 'Тема топика слишком длинная';
	elseif (TextUtils::length($text2) < 1)$error = 'Введите текст топика';
	elseif (TextUtils::length($text2) > 5000)$error = 'Текст топика слишком длинный';
	else {
		if ($topic -> them != $them && $u -> id != $author -> id) {
			adminka::adminsLog("Форум", "Топики", "Изменена тема топика \"".$topic -> them."\" на \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$them."[/url]\"");
		}
		if ($topic -> text != $text2 && $u -> id != $author -> id) {
			adminka::adminsLog("Форум", "Топики", "Изменен текста топика \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$topic -> them."[/url]\"");
		}
		$db -> q("UPDATE `forum_topics` SET `them` = ?,`text` = ?, `last_id_user` = ?, `last_time` = ? WHERE `id` = ?", array($them, $text2, $u -> id, time(), $topic -> id));
		if (adminka::access('forum_lockunlock_topics') && $topic -> lock != $lock && $u -> id != $author -> id) {
			adminka::adminsLog("Форум", "Топики", ($lock?"Закрыт":"Открыт")." топик \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$topic -> them."[/url]\"");
			$db -> q("UPDATE `forum_topics` SET `lock` = ?, `lock_id_user` = ?, `lock_time` = ? WHERE `id` = ?", array($lock, $u -> id, time(), $topic -> id));
		}
		if (adminka::access('forum_updown_topics') && $topic -> pin != $pin && $u -> id != $author -> id) {
			adminka::adminsLog("Форум", "Топики", "Сменен уровень топика \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$topic -> them."[/url]\" с ".$topic -> pin." на ".$pin);
			$db -> q("UPDATE `forum_topics` SET `pin` = ? WHERE `id` = ?", array($pin, $topic -> id));
		}
		alerts::msg_sess("Топик успешно отредактирован");
		header("Location: /forum/t/".$topic -> id);
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<?
	
	if ($u -> id == $author -> id && !adminka::access('forum_edit_topic')) {
		$left_time = ($topic -> time + 600) - time();
		?>
		<div class="wety">
			Осталось <? echo TextUtils::declension($left_time, array('секунда', 'секунды', 'секунд'))?><br />
		</div>
		<?
	}
	?>
	<span class="form_q">Тема:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="them" value="<? echo TextUtils::DBFilter($topic -> them);?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Текст:</span><br />
	<textarea name="text" class="main_inp rad_tlr rad_blr"><? echo TextUtils::DBFilter($topic -> text);?></textarea>
	<span class="alert">Не больше 5000 символов<br /></span>
	<? if (adminka::access('forum_edit_topic')) { ?>
	<span class="form_q">Уровень:</span><br />
	<input type="text" name="pin" class="main_inp rad_tlr rad_blr" value="<? echo TextUtils::DBFilter($topic -> pin);?>" size="2"><br />
	<label for="lock"><input type="checkbox" name="lock" id="lock" value="1"<? echo ($topic -> lock?" CHECKED":null)?>> Закрыть<br /></label>
	<? } ?>
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Сохранить"> <a href="/smiles" class="hp_bb">Смайлы</a> <a href="/tags" class="hp_bb">Теги</a>
</form>
<?
if (adminka::access('forum_remove_topic') || adminka::access('forum_delete_topic')) {
	?>
	<hr>
	<div class="mod">
		<?
		if (adminka::access('forum_remove_topic')) {
			echo imgs::show("move.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/remove/topic/<? echo $topic -> id?>">Переместить топик</a><br />
			<?
		}
		if (adminka::access('forum_delete_topic')) {
			echo imgs::show("delete.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/delete/topic/<? echo $topic -> id?>">Удалить топик</a><br />
			<?
		}
		?>
	</div>
	<?
}
doc::back("Назад", "/forum/t/{$topic -> id}");
include(FOOT);
?>