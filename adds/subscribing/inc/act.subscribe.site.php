<?
include(HEAD);
if (isset($_POST['sfsk'])) {
	$email = $_POST['email'];
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))$error = 'Неверный формат e-mail';
	elseif ($db -> res("SELECT COUNT(*) FROM `subscribers` WHERE `email` = ? AND `object` = ? AND `object_type` = ?", array($email, 0, 2)))$error = 'Это e-mail уже подписан на обновления сайта';
	else {
		$new_tovs = 1;
		$new_news = 1;
		$db -> q("INSERT INTO `subscribers` (`email`, `object`, `object_type`, `new_tovs`, `new_news`, `time`, `pass`) VALUES (?, ?, ?, ?, ?, ?, ?)", array($email, 0, 2, $new_tovs, $new_news, time(), $passgen));
		$ssid = $db -> lastInsertId();
		$message = file_get_contents(dr."core/templates/mail/reg.tpl");
		$message = str_replace(array("{SITE_LINK}", "{LOGO_SRC}", "{MSG}"), array("http://".$_SERVER['HTTP_HOST'], "http://".$_SERVER['HTTP_HOST']."/images/logo.png", "Вы подписались на обновления сайта ".SITE_NAME."!<br />В дальнейшем Вы будете узнавать о всех новых товарах продавцов и о свежих новостях сайта.<br />Для того, что бы отказаться от получения оповещений, перейдите по этой ссылке <a href='http://$_SERVER[HTTP_HOST]/adds/subscribing/?act=unsubscribe&ssid=$ssid&pass=".md5($passgen)."'>http://$_SERVER[HTTP_HOST]/adds/subscribing/?act=unsubscribe&ssid=$ssid&pass=".md5($passgen)."</a> и подтвердите свою отписку."), $message);
		$subject = "Подписка на обновления";
		$adds="From: \"system@$_SERVER[HTTP_HOST]\" <system@$_SERVER[HTTP_HOST]>\n";
		$adds .= "Content-Type: text/html; charset=utf-8\n";
		mail($email,'=?utf-8?B?'.base64_encode($subject).'?=', $message, $adds);
		alerts::msg_sess("Вы успешно подписались на обновления сайта.");
		header("Location: /");
		exit();
	}
}
echo alerts::error();
?>
<form action="" method="POST">
	<div class="content_mess content_redi">
		После подписки на обновления сайта на Вашую електронную почту будут приходить оповещения о всех новых товарах продавцов и о свежих новостях сайта.<br />
		В любой момент Вы сможете отключить эти оповещения, воспользовався паролем, который будет выслан на указанный Вами електронный адрес.<br />
	</div>
	<div class="content_list">
		<span class="form_q">Ваш e-mail:</span><br />
		<input type="text" class="rad_tlr rad_blr main_inp" name="email" value=""><br />
		<input type="submit" name="sfsk" class="rad_tlr rad_blr main_sub" value="Подписаться!">
	</div>
</form>
<?
doc::back("На главную", "/");
include(FOOT);
?>