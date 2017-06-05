<? # by Killer
class mailing {
	public function send_mess($user_id, $ank_id, $msg) {
		global $db;
		@$user = new Users\User($user_id);
		@$ank = new Users\User($ank_id);
		if (!$db -> res("SELECT COUNT(*) FROM `mail_conts` WHERE `id_user` = ? AND `id_ank` = ?", array($ank -> id, $user -> id))) {
			$db -> q("INSERT INTO `mail_conts` (`id_user`, `id_ank`, `date`, `date_last`, `count`) VALUES (?, ?, ?, ?, ?)", array($ank -> id, $user -> id, time(), time(), 1));
			$hid2 = $db -> lastInsertId();
			$db -> q("INSERT INTO `mail` SET `id_user` = ?, `id_cont` = ?, `msg` = ?, `date` = ?, `read` = ?, `type` = ?", array($ank -> id, $hid2, $msg, time(), 0, 'to'));
		} else {
			$acont= $db -> q("SELECT * FROM `mail_conts` WHERE `id_user` = ? AND `id_ank` = ?", array($ank -> id, $user -> id));
			$acont = $acont -> fetch();
			$db -> q("INSERT INTO `mail` SET `id_user` = ?, `id_cont` = ?, `msg` = ?, `date` = ?, `read` = ?, `type` = ?", array($ank -> id, $acont -> id, $msg, time(), 0, 'to'));
			$count=$db -> res("SELECT COUNT(*) FROM `mail` WHERE `id_cont` = ? AND `read` = ?", array($acont -> id, 0));
			$db -> q("UPDATE `mail_conts` SET `date_last` = ?, `count` = ? WHERE `id` = ?", array(time(), $count, $acont -> id));
			$hid2 = $acont -> id;
		}
		if (!$db -> res("SELECT COUNT(*) FROM `mail_conts` WHERE `id_user` = ? AND `id_ank` = ?", array($user -> id, $ank -> id))) {
			$db -> q("INSERT INTO `mail_conts` (`id_user`, `id_ank`, `date`, `date_last`) VALUES (?, ?, ?, ?)", array($user -> id, $ank -> id, time(), time()));
			$hid= $db -> lastInsertId();
			$db -> q("INSERT INTO `mail` SET `id_user` = ?, `id_cont` = ?, `msg` = ?, `date` = ?, `read` = ?, `type` = ?", array($user -> id, $hid, $msg, time(), 0, 'at'));
		} else {
			$ucont=$db -> q("SELECT * FROM `mail_conts` WHERE `id_user` = ? AND `id_ank` = ?", array($user -> id, $ank -> id));
			$ucont = $ucont -> fetch();
			$db -> q("UPDATE `mail_conts` SET `date_last` = ? WHERE `id` = ?", array(time(), $ucont -> id));
			$db -> q("INSERT INTO `mail` SET `id_user` = ?, `id_cont` = ?, `msg` = ?, `date` = ?, `read` = ?, `type` = ?",array($user -> id, $ucont -> id, $msg, time(), 0, 'at'));
			$hid = $ucont -> id;
		}
		if ($ank -> info -> send_system_email == 1 && $user_id == 0 && $ank -> info -> email) {
			$msg_email = TextUtils::show($msg, 0)."<br/><a href='http://$_SERVER[HTTP_HOST]/mail/?act=cont&id=$hid2'>Показать переписку &raquo;</a>";
			$subject = "Новое письмо от: ".$set -> sys_login;
			$adds="From: \"system@$_SERVER[HTTP_HOST]\" <system@$_SERVER[HTTP_HOST]>\n";
			$adds .= "Content-Type: text/html; charset=utf-8\n";
			mail($ank -> info -> email,'=?utf-8?B?'.base64_encode($subject).'?=', $msg_email, $adds);
		}
		return $hid;
	}}
?>