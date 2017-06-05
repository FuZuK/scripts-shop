<?
Users\User::if_user('is_reg');
adminka::accessCheck('news_add_new');
$title .= ' - Добавить';
include(HEAD);;
$title = null;
$msg = null;
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$title = $_POST['title'];
	$msg = $_POST['msg'];
	if (TextUtils::length(trim($title)) < 1)$error = 'Введите заголовок';
	elseif (TextUtils::length($title) > 50)$error = 'Заголовок слишком длинный';
	elseif (TextUtils::length(trim($msg)) < 1)$error = 'Введите текст';
	elseif (TextUtils::length($msg) > 5000)$title = 'Текст слишком длинный';
	else {
		$db -> q("INSERT INTO `news` (`title`, `msg`, `id_user`, `time`) VALUES (?, ?, ?, ?)", array($title, $msg, $u -> id, time()));
		$new_id = $db -> lastInsertId();
		$sel_site_subsc = $db -> q("SELECT * FROM `subscribers` WHERE `object` = ? AND `object_type` = ? AND `new_news` = ?", array(0, 2, 1));
		while ($subsc = $sel_site_subsc -> fetch()) {
			$subject = TextUtils::escape($title);
			$message = file_get_contents(DR."core/templates/mail/subscribers.news.tpl");
			$message = str_replace(array("{ADDER_ID}", "{ADDER_LOGIN}", "{SITE_NAME}", "{ID}", "{TITLE}", "{MSG}", "{TIME}", "{PROFILE_PAGE}", "{SITE_LINK}", "{LOGO_SRC}", "{UNSS_LINK}"), array($u -> id, $u -> login, $_SERVER['HTTP_HOST'], $new_id, TextUtils::escape($title), TextUtils::show($msg), TimeUtils::show(time()), $set -> profile_page, "http://".$_SERVER['HTTP_HOST'], "http://".$_SERVER['HTTP_HOST']."/images/logo.png", "http://$_SERVER[HTTP_HOST]/adds/subscribing/?act=unsubscribe&ssid={$subsc -> id}&pass=".md5($subsc -> pass)), $message);
			$headers = "From: \"system@$_SERVER[HTTP_HOST]\" <system@$_SERVER[HTTP_HOST]>\n";
			$headers .= "Content-Type: text/html; charset=utf-8\n";
			mail($subsc -> email, '=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers);
		}
		adminka::adminsLog("Новости", "Записи", "Добавлена новость \"[url=http://$_SERVER[HTTP_HOST]/news/read/".$new_id."]".$title."[/url]\"");
		alerts::msg_sess("Новость успешно добавлена");
		header("Location: /news");
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Заголовок:</span><br />
	<input type="text" name="title" class="main_inp rad_tlr rad_blr" value="<? echo TextUtils::DBFilter($title);?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Текст:</span><br />
	<textarea name="msg" cols="30" rows="10" class="main_inp rad_tlr rad_blr"><? echo TextUtils::DBFilter($msg);?></textarea>
	<span class="alert">Не больше 5000 символов<br /></span>
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Добавить"><br />
</form>
<?
doc::back("Назад", "/news");
include(FOOT);
?>