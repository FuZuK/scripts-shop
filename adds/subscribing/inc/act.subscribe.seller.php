<?
$us = new Users\User(intval(@$_GET['user_id']));
if (!@$us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Пользователь не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
include(HEAD);
if (isset($_POST['sfsk'])) {
	$email = $_POST['email'];
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))$error = 'Неверный формат e-mail';
	elseif ($db -> res("SELECT COUNT(*) FROM `subscribers` WHERE `email` = ? AND `object` = ? AND `object_type` = ?", array($email, $us -> id, 1)))$error = 'Это e-mail уже подписан на обновления этого пользователя';
	else {
		$db -> q("INSERT INTO `subscribers` (`email`, `object`, `object_type`, `new_tovs`, `time`, `pass`) VALUES (?, ?, ?, ?, ?, ?)", array($email, $us -> id, 1, 1, time(), $passgen));
		$ssid = $db -> lastInsertId();
		$message = file_get_contents(dr."core/templates/mail/reg.tpl");
		$message = str_replace(array("{SITE_LINK}", "{LOGO_SRC}", "{MSG}"), array("http://".$_SERVER['HTTP_HOST'], "http://".$_SERVER['HTTP_HOST']."/images/logo.png", "Вы подписались на обновления пользователя ".$us -> login." на сайте <b>".SITE_NAME."</b>!<br />В дальнейшем Вы будете узнавать о всех новых товарах этого продавца.<br />Для того, что бы отказаться от получения оповещений, перейдите по этой ссылке <a href='http://$_SERVER[HTTP_HOST]/adds/subscribing/?act=unsubscribe&ssid=$ssid&pass=".md5($passgen)."'>http://$_SERVER[HTTP_HOST]/adds/subscribing/?act=unsubscribe&ssid=$ssid&pass=".md5($passgen)."</a> и подтвердите свою отписку."), $message);
		$subject = "Подписка на обновления";
		$adds="From: \"system@$_SERVER[HTTP_HOST]\" <system@$_SERVER[HTTP_HOST]>\n";
		$adds .= "Content-Type: text/html; charset=utf-8\n";
		mail($email,'=?utf-8?B?'.base64_encode($subject).'?=', $message, $adds);
		alerts::msg_sess("Вы успешно подписались на обновления пользователя ".$us -> login.".");
		header("Location: ".$set -> profile_page.$us -> id);
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST">
	<div class="content_mess content_redi">
		После подписки на обновления пользователя <? echo $us -> login(1, 0)?> на Вашую електронную почту будут приходить оповещения о новых товарах этого продавца.<br />
		В любой момент Вы сможете отключить эти оповещения, воспользовався паролем, который будет выслан на указанный Вами електронный адрес.<br />
	</div>
	<div class="content_list">
		<span class="form_q">Ваш e-mail:</span><br />
		<input type="text" class="rad_tlr rad_blr main_inp" name="email" value=""><br />
		<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Подписаться!">
	</div>
</form>
<?
doc::back("Назад", $set -> profile_page.$us -> id);
include(FOOT);
?>