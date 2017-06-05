<?
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
$title .= ' - '.TextUtils::escape($topic -> them);
if (isset($_POST['do_vote']) && $topic -> poll && !$db -> res("SELECT COUNT(*) FROM `forum_poll_votes` WHERE `id_topic` = ? AND `id_user` = ?", array($topic -> id, $u -> id))) {
	Users\User::if_user('is_reg');
	$my_vote = $db -> res("SELECT COUNT(*) FROM `forum_poll_votes` WHERE `id_topic` = ? AND `id_user` = ?", array($topic -> id, $u -> id));
	$checked = false;
	if ($topic -> poll_time > time()) {
		foreach ($_POST as $key => $value) {
			if (preg_match("|^([0-9]*)$|isU", $value, $var_id)) {
				if ($var = $db -> farr("SELECT * FROM `forum_poll_vars` WHERE `id` = ? AND `id_topic` = ?", array($var_id[1], $topic -> id))) {
					$db -> q("INSERT INTO `forum_poll_votes` (`id_topic`, `id_user`, `id_var`, `time`) VALUES (?, ?, ?, ?)", array($topic -> id, $u -> id, $var -> id, time()));
					$checked = true;
					echo $value.' '.$key.'<br />';
					if (!$topic -> poll_check)break;
				}
			}
		}
		if (!$checked) {
			alerts::msg_sess("Выберите вариант ответа");
		} else {
			header("Location: /forum/t/".$topic -> id);
			exit();
		}
	} else alerts::msg_sess("Время вышло");
}
include(HEAD);
?>
<div class="content">
	<div class="wety">
		<? echo $author -> icon().$author -> login()?> (<? echo TimeUtils::show($topic -> time)?>)<br />
	</div>
	<? echo TextUtils::show($topic -> text, $author -> id)?>
	<?
	if ($topic -> poll) {
		@$my_vote = $db -> res("SELECT COUNT(*) FROM `forum_poll_votes` WHERE `id_topic` = ? AND `id_user` = ?", array($topic -> id, $u -> id));
		$all_votes = $db -> res("SELECT COUNT(*) FROM `forum_poll_votes` WHERE `id_topic` = ?", array($topic -> id));
		?>
		<div class="poll">
			<? echo TextUtils::show($topic -> poll_text, $author -> id)?><br />
			<?
			$select_vars = $db -> q("SELECT * FROM `forum_poll_vars` WHERE `id_topic` = ? ORDER BY `num` ASC", array($topic -> id));
			$get_leader = $db -> q("SELECT * FROM `forum_poll_vars` WHERE `id_topic` = ? ORDER BY `num` ASC", array($topic -> id));
			while ($g_leader =  $get_leader -> fetch()) {
				$this_votes = $db -> res("SELECT COUNT(*) FROM `forum_poll_votes` WHERE `id_topic` = ? AND `id_var` = ?", array($topic -> id, $g_leader -> id));
				if (isset($leader)) {
					$leader_votes = $db -> res("SELECT COUNT(*) FROM `forum_poll_votes` WHERE `id_topic` = ? AND `id_var` = ?", array($topic -> id, $leader -> id));
					if ($leader_votes < $this_votes)$leader = $g_leader;
				} else $leader = $g_leader;
			}
			if (!$my_vote) { ?><form action="" method="POST"><? } ?>
				<?
				while ($var = $select_vars -> fetch()) {
					$this_votes = $db -> res("SELECT COUNT(*) FROM `forum_poll_votes` WHERE `id_topic` = ? AND `id_var` = ?", array($topic -> id, $var -> id));
					$percent = round(($all_votes?($this_votes / $all_votes) * 100:0), 2);
					?>
					<div class="variant-w">
					<? if (!$my_vote) { ?><label for="variant_select_<? echo $var -> id?>_1"><input type="<? echo ($topic -> poll_check?"checkbox":"radio")?>" name="variant_select_<? echo ($topic -> poll_check?$var -> id:null)?>" id="variant_select_<? echo $var -> id?>_1" value="<? echo $var -> id?>"> <? } echo TextUtils::escape($var -> variant)?><? if (!$my_vote && isset($u)) { ?><br /></label><? } else {?><span class="right small"><? echo $percent?>% (<? echo $this_votes?>)</span><? } ?>
					<? if ($my_vote || !isset($u)) { ?>
					<div class="variant">
						<div class="varinat-progress" style="width: <? echo $percent?>%"></div>
					</div>
					<? } ?>
					</div>
					<?
				}
				if (!$my_vote)echo ussec::input();
				?>
				<? if (!$my_vote) { ?><input type="submit" name="do_vote" class="main_sub rad_tlr rad_blr" value="Проголосовать"><br />
			</form>
			<?
			}
			?>
			<br />
			<div class="alert">
			Опрос начался <? echo TimeUtils::show($topic -> poll_time_start)?>
			<? if ($topic -> poll_timee != 'infin') {
				if ($topic -> poll_time > time())echo " и закончится ".TimeUtils::show($topic -> poll_time);
				else echo " и закончился ".TimeUtils::show($topic -> poll_time);
			} ?>
			</div>
			<?
			if (adminka::access('forum_edit_poll')) {
				echo imgs::show("poll_blue.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))?> <a href="/forum/poll/edit/<? echo $topic -> id?>">Редактировать</a>
				<?
			}
			?>
		</div>
		<?
	}
	?>
</div>
<?
if ($topic -> last_id_user) {
	$last_us = new Users\User($topic -> last_id_user);
	?>
	<div class="panel_ud" style="background: #BBDBE7;">
		Последний раз редактировал<? echo $last_us -> pw(array('а', null))?> <? echo $last_us -> icon().$last_us -> login()?>
	</div>
	<?
}
if (isset($u) && $u -> id == $author -> id && $topic -> time + 600 > time() || adminka::access('forum_edit_topic') || adminka::access('forum_add_poll') && !$topic -> poll || adminka::access('forum_delete_topic')) {
	echo "<hr>\n";
	echo "<div class='mod'>\n";
	if ($u -> id == $author -> id && $topic -> time + 600 > time() || adminka::access('forum_edit_topic')) {
		echo imgs::show("edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)) . " <a href='/forum/edit/topic/{$topic -> id}'>Редактировать</a><br />\n";
	}
	if (adminka::access('forum_add_poll') && !$topic -> poll) {
		echo imgs::show("poll_blue.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)) . " <a href='/forum/poll/add/{$topic -> id}'>Добавить опрос</a><br />\n";
	}
	if (adminka::access('forum_delete_topic')) {
		echo imgs::show("delete.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)) . " <a href='/forum/delete/topic/{$topic -> id}'>Удалить</a><br />\n";
	}
	echo "</div>\n";
}
$count_results = $db -> res("SELECT COUNT(*) FROM `forum_comms` WHERE `id_topic` = ?", array($topic -> id));
$navi = new navi($count_results, '?');
journal::update('forum', $topic -> id, "/forum/t/".$topic -> id."?page=".$navi -> page);
echo "<div class='panel_ud'>\n";
echo imgs::show("chat.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)) . " Комментарии ({$count_results})<br />\n";
echo "</div>\n";
echo "<hr>\n";
$posts = array();
$q = $db -> q("SELECT * FROM `forum_comms` WHERE `id_topic` = ?".(adminka::access('forum_show_hiden_comments')?null:" AND `hidden` = '0'")." ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($topic -> id));
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$reply_us = new Users\User($post -> reply_id_user);
	$hus = new Users\User($post -> hidden_id_user);
	$actions = array();
	$actions[] = array(
		'link' => "/forum/reply/{$post -> id}", 
		'name' => 'Ответить'
	);
	if (isset($u) && $u -> id == $us -> id && $post -> time + 600 > time())$actions[] = array(
		'link' => "/forum/edit/comment/{$post -> id}", 
		'name' => 'Редактировать'
	);
	if (adminka::access('forum_hideshow_comments'))$actions[] = array(
		'link' => "/forum/sh/comment/{$post -> id}/".ussec::get(), 
		'name' => ($post -> hidden?"Показать":"Скрыть")
	);
	if (adminka::access('forum_delete_comment'))$actions[] = array(
		'link' => "/forum/delete/comment/{$post -> id}/".ussec::get(), 
		'name' => 'Удалить'
	);
	$posts[] = array(
		'data' => $post, 
		'us' => $us, 
		'reply_us' => $reply_us, 
		'hus' => $hus, 
		'time_form' => TimeUtils::show($post -> time), 
		'msg_form' => TextUtils::show($post -> msg, $us -> id), 
		'actions' => $actions
	);
}
$smarty = new SMX();
$smarty -> assign("posts", $posts);
$smarty -> display("list.comments.tpl");
echo $navi -> show;
if (isset($u)) {
	if (isset($_POST['sfsk']) && !$topic -> lock && ussec::check_p()) {
		$comment = $_POST['msg'];
		if (TextUtils::length(trim($comment)) < 1)$error = 'Введите комментарий';
		elseif (TextUtils::length($comment) > 5000)$error = 'Комментарий слишком длинный';
		else {
			$db -> q("INSERT INTO `forum_comms` (`id_topic`, `id_user`, `time`, `msg`) VALUES (?, ?, ?, ?)", array($topic -> id, $u -> id, time(), $comment));
			$cid = $db -> lastInsertId();
			if (isset($_POST['reply_id_user']) && $db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ?", array(intval($_POST['reply_id_user']))) && isset($_POST['reply_id_comment']) && $db -> res("SELECT COUNT(*) FROM `forum_comms` WHERE `id` = ? AND `id_topic` = ?", array(intval($_POST['reply_id_comment']), $topic -> id))) {
				$rus = new Users\User(intval($_POST['reply_id_user']));
				$reply_comment = $db -> farr("SELECT * FROM `forum_comms` WHERE `id` = ? AND `id_topic` = ?", array(intval($_POST['reply_id_comment']), $topic -> id));
				$db -> q("UPDATE `forum_comms` SET `reply_id_user` = ?, `reply_id_comment` = ? WHERE `id` = ?", array($rus -> id, $reply_comment -> id, $cid));
			}
			$all_users_commed = array();
			$q = $db -> q("SELECT * FROM `forum_comms` WHERE `id_topic` = ? ORDER BY `time` DESC", array($topic -> id));
			while ($comment = $q -> fetch()) {
				if (!in_array($comment -> id_user, $all_users_commed) && $comment -> id_user != $author -> id && $comment -> id_user != $u -> id) {
					$all_users_commed[] = $comment -> id_user;
					journal::send($u -> id, $comment -> id_user, 'forum', 'msg', $topic -> id, $comment -> id, $comment -> time);
				}
			}
			if ($u -> id != $author -> id) {
				journal::send($u -> id, $author -> id, 'forum', 'object', $topic -> id, 0, $topic -> time);
			}
			header("Location: ?");
			exit();
		}
	}
	if (!$topic -> lock) {
		echo alerts::error();
		$elms = array();
		$elms[] = array('type' => 'textarea', 'name' => 'msg', 'value' => '', 'cols' => '30', 'rows' => '10', 'br' => true);
		$elms[] = array('type' => 'ussec');
		$elms[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить');
		$elms[] = array('type' => 'hp_smiles');
		$elms[] = array('type' => 'hp_tags');
		new SMX(array('method' => 'POST', 'el' => $elms, 'fastSend' => true), 'form.tpl');
	} else {
		$us_lock = new Users\User($topic -> lock_id_user);
		echo "<div class='msg_inline' style='border-radius: 0;'>\n";
		echo "Топик закрыл " . $us_lock -> login(1, 0) . " (" . TimeUtils::show($topic -> lock_time) . ")<br />\n";
		echo "</div>\n";
	}
}
Doc::back("Назад", "/forum/c/{$cat -> id}");
include(FOOT);
?>