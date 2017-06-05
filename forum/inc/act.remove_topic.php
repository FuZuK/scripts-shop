<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_remove_topic');
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
$title .= ' - Перемещение топика';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$cat_replace = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array(intval($_POST['cat_id'])));
	if (!$cat_replace -> id)$error = 'Категория не найдена';
	elseif ($cat_replace -> id == $cat -> id)$error = 'Топик уже находится в этой категории';
	else {
		$db -> q("UPDATE `forum_topics` SET `id_cat` = ? WHERE `id` = ?", array($cat_replace -> id, $topic -> id));
		adminka::adminsLog("Форум", "Топики", "Топик \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$topic -> them."[/url]\" перемещен из раздела \"[url=http://$_SERVER[HTTP_HOST]/forum/c/".$cat -> id."]".$cat -> name."[/url]\" в \"[url=http://$_SERVER[HTTP_HOST]/forum/c/".$cat_replace -> id."]".$cat_replace -> name."[/url]\"");
		alerts::msg_sess("Топик успешно перемещен");
		header("Location: /forum/t/".$topic -> id);
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Выберите раздел:</span><br />
	<select name="cat_id" class="main_inp rad_tlr rad_blr">
	<?
	$select_forums_list = $db -> q("SELECT * FROM `forum_forums` ORDER BY `pos` ASC");
	while ($forum_list = $select_forums_list -> fetch()) {
		?>
		<optgroup label="<? echo TextUtils::escape($forum_list -> name)?>" style="font-weight: 10px;">
			<?
			$select_cats_list = $db -> q("SELECT * FROM `forum_cats` WHERE `id_forum` = ?", array($forum_list -> id));
			while ($cat_list = $select_cats_list -> fetch()) {
				?>
				<option value="<? echo $cat_list -> id?>"<?echo ($cat_list -> id == $cat -> id?" SELECTED":null)?>><? echo TextUtils::escape($cat_list -> name)?></option>
				<?
			}
			?>
		</optgroup>
		<?
	}
	?>
	</select><br />
	<? echo ussec::input()?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Переместить"><br />
</form>
<?
doc::back("Назад", "/forum/t/{$topic -> id}");
include(FOOT);
?>