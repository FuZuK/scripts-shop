<?
$ticket = $db -> farr("SELECT * FROM `tickets` WHERE `id` = ?", array(intval($_GET['ticket_id'])));
if (!@$ticket -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Тикет не найден.");
	?>
	<?doc::back("Назад", "/support")?>
	<?
	include(FOOT);
}
$us = new Users\User($ticket -> id_user);
if (!($us -> id == $u -> id || $ticket -> type == 0 && adminka::access('tickets_read_ticket_to_adm') || $ticket -> type == 1 && adminka::access('tickets_read_ticket_to_kons'))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш тикет!");
	?>
	<?doc::back("Назад", "/support")?>
	<?
	include(FOOT);
}
$title .= ' - '.TextUtils::escape(TextUtils::cut($ticket -> title, 20));
include(HEAD);
?>
<div class="content">
	<div class="wety">
		<div class="left">
			<? echo $us -> ava_list()?>
		</div>
		<div class="lst_h">
			<div class="list_us_info">
				<? echo $us -> icon().$us -> login(1)?> <span class="time_show">(<? echo TimeUtils::show($ticket -> time)?>)</span><br />
			</div>
			<hr class="custom">
			<div class="mess_list">
				<b><? echo TextUtils::escape($ticket -> title)?></b><br />
				<span style="color: blue;"><? echo ($ticket -> type == 0?"Администратору":"Консультанту")?></span><br />
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div>
		<? echo TextUtils::show($ticket -> msg)?>
	</div>
	<?
	if ($us -> id == $u -> id || $ticket -> opened == 0 && adminka::access('tickets_open_ticket') || $ticket -> opened == 1 && adminka::access('tickets_close_ticket')) {
		?>
		<hr class="custom">
		<div class="mess_mod">
			<?
			if ($ticket -> opened == 1) {
				?>
				<a href="/support/close_ticket/<? echo $ticket -> id?>">Закрыть</a>
				<?
			} elseif ($ticket -> opened == 0) {
				?>
				<a href="/support/open_ticket/<? echo $ticket -> id?>">Открыть</a>
				<?
			}
			?>
		</div>
		<?
	}
	?>
</div>
<?
$count_results = $db -> res("SELECT COUNT(*) FROM `tickets_comms` WHERE `id_ticket` = ?", array($ticket -> id));
$navi = new navi($count_results, "?");
journal::update('tickets', $ticket -> id, $_SERVER['REQUEST_URI']);
?>
<div class="panel_ud">
	<? echo imgs::show("chat.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))?> Комментарии (<? echo $count_results;?>)<br />
</div>
<hr>
<?
$posts = array();
$q = $db -> q("SELECT * FROM `tickets_comms` WHERE `id_ticket` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($ticket -> id));
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$posts[] = array(
		'data' => $post, 
		'us' => $us, 
		'time_form' => TimeUtils::show($post -> time), 
		'msg_form' => TextUtils::show($post -> msg, $us -> id)
	);
}
$smarty = new SMX();
$smarty -> assign("posts", $posts);
$smarty -> display("list.comments.tpl");
echo $navi -> show;
if (isset($u) && $ticket -> opened) {
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$msg = $_POST['msg'];
		if (TextUtils::length(trim($msg)) < 1)$error = 'Введите комментарий';
		elseif (TextUtils::length($msg) > 1024)$error = 'Комментарий слишком длинный';
		else {
			$db -> q("INSERT INTO `tickets_comms` (`id_ticket`, `id_user`, `time`, `msg`) VALUES (?, ?, ?, ?)", array($ticket -> id, $u -> id, time(), $msg));
			$mid = $db -> lastInsertId();
			$all_users_commed = array();
			$q = $db -> q("SELECT * FROM `tickets_comms` WHERE `id_ticket` = ? ORDER BY `time` DESC", array($ticket -> id));
			while ($comment = $q -> fetch()) {
				if (!in_array($comment -> id_user, $all_users_commed) && $comment -> id_user != $us -> id && $comment -> id_user != $u -> id) {
					$all_users_commed[] = $comment -> id_user;
					if (adminka::access('tickets_read_ticket', $comment -> id_user)) {
						journal::send($u -> id, $comment -> id_user, 'tickets', 'comment', $ticket -> id, $comment -> id, $comment -> time);
					}
				}
			}
			echo $u -> id.' '.$us -> id.'<br>';
			if ($u -> id != $us -> id) {
				echo $u -> id.' '.$us -> id.'<br>';
				journal::send($u -> id, $us -> id, 'tickets', 'object', $ticket -> id, 0, $ticket -> time);
			}
			header("Location: ?");
			exit();
		}
	}
	echo alerts::error();
	?>
	<hr>
	<form action="" class="content rad_trl" method="POST">
		<textarea name="msg" id="msg_mess" cols="30" rows="10" class="main_inp rad_tlr rad_blr"></textarea><br>
		<? echo ussec::input();?>
		<input type="submit" class="main_sub rad_tlr rad_blr" name="sfsk" value="Отправить"> <a href="/smiles" class="hp_bb">Смайлы</a> <a href="/tags" class="hp_bb">Теги</a>
	</form>
<?
}
if (adminka::access('tickets_read_ticket_to_adm') || adminka::access('tickets_read_ticket_to_kons')) {
	?>
	<?doc::back("Нерешенные вопросы", "/adminka/?act=new_tickets")?>
	<?
}
?>
<?doc::back("Мои тикеты", "/support")?>
<?
include(FOOT);
?>