<?
$title = 'Журнал';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `journal` WHERE `read` = ? AND `id_user` = ?", array(0, $u -> id));
$navi = new navi($cr, '?');
if (!$cr)doc::listEmpty("Нет новых оповещений");
$q = $db -> q("SELECT * FROM `journal` WHERE `read` = ? AND `id_user` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array(0, $u -> id));
while ($post = $q -> fetch()) {
	$author = new Users\User($post -> id_author);
	?>
	<div class="content_mess">
		<?
		if ($post -> cat == 'forum') {
			// Форум
			// Новый комм. в моем топике
			if ($post -> object_type == 'object') {
				$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array($post -> id_object));
				if ($topic -> id) {
					$new_comments = $db -> res("SELECT COUNT(*) FROM `forum_comms` WHERE `id_topic` = ? AND `time` > ? AND `id_user` != ?", array($topic -> id, $post -> last_time, $u -> id));
					?>
					Топик: <a href='/forum/t/<? echo $topic -> id?>'><? echo TextUtils::escape($topic -> them)?></a><br />
					<span class="red">+<? echo TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
					<?
				} else {
					?>
					<span class="red">Топик удален</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			} elseif ($post -> object_type == 'comment') {
				// Новый комм. в топике, где я оставлял комм.
				$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array($post -> id_object));
				if ($topic -> id) {
					$comment = $db -> farr("SELECT * FROM `forum_comms` WHERE `id_topic` = ? AND `id` = ?", array($post -> id_object, $post -> id_object_moth));
					if ($comment -> id) {
						$new_comments = $db -> res("SELECT COUNT(*) FROM `forum_comms` WHERE `id_topic` = ? AND `time` > ? AND `id_user` != ?", array($topic -> id, $post -> last_time, $u -> id));
						?>
						Топик: <a href='/forum/t/<? echo $topic -> id?>'><? echo TextUtils::escape($topic -> them)?></a><br />
						<span class="red">+<? echo TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
						<?
					} else {
						?>
						<span class="red">Комментарий удален!</span>
						<?
						$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
					}
				} else {
					?>
					<span class="red">Топик удален</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			}
		} elseif ($post -> cat == 'shop') {
			// Магазин
			// Новый комм. к моему товару
			if ($post -> object_type == 'object') {
				$good = new UsersShop\Good($post -> id_object);
				if ($good -> exists()) {
					$new_comments = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_comments` WHERE `id_good` = ? AND `time` > ? AND `id_user` != ?", array($good -> id, $post -> last_time, $u -> id));
					?>
					Товар: <a href='/user/shop/?act=good&good_id=<?=$good -> id?>'><?=TextUtils::DBFilter($good -> name)?></a><br />
					<span class="red">+<?=TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
					<?
				} else {
					?>
					<span class="red">Товар удален</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			} elseif ($post -> object_type == 'comment') {
				// Новый комм. к товару, к тоторому я оставил комм.
				$good = new UsersShop\Good($post -> id_object);
				if ($good -> exists()) {
					$comment = $db -> farr("SELECT * FROM `users_shop_goods_comments` WHERE `id_good` = ? AND `id` = ?", array($good -> id, $post -> id_object_moth));
					if ($comment -> id) {
						$new_comments = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_comments` WHERE `id_good` = ? AND `time` > ? AND `id_user` != ?", array($good -> id, $post -> last_time, $u -> id));
						?>
						Товар: <a href='/user/shop/?act=good&good_id=<?=$good -> id?>'><?=TextUtils::DBFilter($good -> name)?></a><br />
						<span class="red">+<?=TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
						<?
					} else {
						?>
						<span class="red">Комментарий удален</span>
						<?
						$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
					}
				} else {
					?>
					<span class="red">Товар удален</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			}
		} elseif ($post -> cat == 'news') {
			// Новости
			// Новый комм. к моей новости
			if ($post -> object_type == 'object') {
				$new = $db -> farr("SELECT * FROM `news` WHERE `id` = ?", array($post -> id_object));
				if ($new -> id) {
					$new_comments = $db -> res("SELECT COUNT(*) FROM `news_comms` WHERE `id_new` = ? AND `time` > ? AND `id_user` != ?", array($new -> id, $post -> last_time, $u -> id));
					?>
					Новость: <a href='/news/read/<? echo $new -> id?>'><? echo TextUtils::escape($new -> title)?></a><br />
					<span class="red">+<? echo TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
					<?
				} else {
					?>
					<span class="red">Новость удалена</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			} elseif ($post -> object_type == 'comment') {
				// Новый комм. к новости, в котороя о оставлял комм.
				$new = $db -> farr("SELECT * FROM `news` WHERE `id` = ?", array($post -> id_object));
				if ($new -> id) {
					$comment = $db -> farr("SELECT * FROM `news_comms` WHERE `id_new` = ? AND `id` = ?", array($post -> id_object, $post -> id_object_moth));
					if ($comment -> id) {
						$new_comments = $db -> res("SELECT COUNT(*) FROM `news_comms` WHERE `id_new` = ? AND `time` > ? AND `id_user` != ?", array($new -> id, $post -> last_time, $u -> id));
						?>
						Новость: <a href='/news/read/<? echo $new -> id?>'><? echo TextUtils::escape($new -> title)?></a><br />
						<span class="red">+<? echo TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
						<?
					} else {
						?>
						<span class="red">Комментарий удален</span>
						<?
						$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
					}
				} else {
					?>
					<span class="red">Новость удалена</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			}
		} elseif ($post -> cat == 'chatik') {
			// Мини-чат
			if ($post -> object_type == 'comment') {
				$comment = $db -> farr("SELECT * FROM `chatik_comms` WHERE `id` = ?", array($post -> id_object_moth));
				if ($comment -> id) {
					$new_comments = $db -> res("SELECT COUNT(*) FROM `chatik_comms` WHERE `time` > ? AND `id_user` != ?", array($post -> last_time, $u -> id));
					?>
					<a href='/chatik/'>Мини-чат</a><br />
					<span class="red">+<? echo TextUtils::declension($new_comments, array('сообщение', 'сообщения', 'сообщений'))?></span>
					<?
				} else {
					?>
					<span class="red">Сообщение удалено</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			}
		} elseif ($post -> cat == 'tickets') {
			// Новости
			// Новый комм. к моей новости
			if ($post -> object_type == 'object') {
				$ticket = $db -> farr("SELECT * FROM `tickets` WHERE `id` = ?", array($post -> id_object));
				if ($ticket -> id) {
					$new_comments = $db -> res("SELECT COUNT(*) FROM `tickets_comms` WHERE `id_ticket` = ? AND `time` > ? AND `id_user` != ?", array($ticket ->id, $post -> last_time, $u -> id));
					?>
					Тикет: <a href='/support/ticket/<? echo $ticket ->id?>'><? echo TextUtils::escape($ticket ->title)?></a><br />
					<span class="red">+<? echo TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
					<?
				} else {
					?>
					<span class="red">Тикет удален</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			} elseif ($post -> object_type == 'comment') {
				// Новый комм. к новости, в котороя о оставлял комм.
				$ticket= $db -> farr("SELECT * FROM `tickets` WHERE `id` = ?", array($post -> id_object));
				if ($ticket ->id) {
					$comment = $db -> farr("SELECT * FROM `tickets_comms` WHERE `id_ticket` = ? AND `id` = ?", array($post -> id_object, $post -> id_object_moth));
					if ($comment -> id) {
						$new_comments = $db -> res("SELECT COUNT(*) FROM `tickets_comms` WHERE `id_ticket` = ? AND `time` > ? AND `id_user` != ?", array($ticket ->id, $post -> last_time, $u -> id));
						?>
						Тикет: <a href='/support/ticket/<? echo $ticket ->id?>'><? echo TextUtils::escape($ticket ->title)?></a><br />
						<span class="red">+<? echo TextUtils::declension($new_comments, array('комментарий', 'комментария', 'комментариев'))?></span>
						<?
					} else {
						?>
						<span class="red">Комментарий удален</span>
						<?
						$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
					}
				} else {
					?>
					<span class="red">Тикет удален</span>
					<?
					$db -> q("DELETE FROM `journal` WHERE `id` = ?", array($post -> id));
				}
			}
		}
		?>
	</div>
	<?
}
echo $navi -> show;
echo "<hr>\n";
echo "<div class='mod'>\n";
echo imgs::show("book.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." <a href='/journal/readed'>Список прочитанных</a><br />\n";
echo imgs::show("delete.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))." <a href='/journal/read_all/".ussec::get()."'>Отметить все прочитанным</a><br />\n";
echo "</div>\n";
doc::back("В кабинет", "/cab");
include(FOOT);
?>