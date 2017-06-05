<? # by Killer
class adminka {
	public static function enter() {
		global $set, $db, $u, $title, $theme;
		Users\User::if_user('is_reg');
		adminka::accessCheck('adminka_enter');
		if (!isset($_SESSION['adminka_enter'])) {
			include(HEAD);
			if (isset($_POST['sfsk']) && ussec::check_p() && Captcha::validate()) {
				$_SESSION['adminka_enter'] = true;
				header("Location: ?".$_SERVER['QUERY_STRING']);
				exit();
			}
			new SMX(array('el' => array(array('type' => 'captcha', 'br' => true), array('type' => 'ussec'), array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Продолжить', 'br' => true)), 'method' => 'POST'), 'form.tpl');
			include(FOOT);
		}
	}
	public static function adminsLog($mod_name, $act_name, $msg) {
		global $u, $db, $set, $ip, $browser;
		if (isset($u)) {
			if (!$db -> res("SELECT COUNT(*) FROM `admins_log_mod` WHERE `name` = ?", array($mod_name))) {
				$db -> q("INSERT INTO `admins_log_mod` (`name`) VALUES (?)", array($mod_name));
				$mod = $db -> farr("SELECT * FROM `admins_log_mod` WHERE `id` = ?", array($db -> lastInsertId()));
			} else {
				$mod = $db -> farr("SELECT * FROM `admins_log_mod` WHERE `name` = ?", array($mod_name));
			}
			if (!$db -> res("SELECT COUNT(*) FROM `admins_log_act` WHERE `name` = ? AND `id_mod` = ?", array($act_name, $mod -> id))) {
				$db -> q("INSERT INTO `admins_log_act` (`name`, `id_mod`) VALUES (?, ?)", array($act_name, $mod -> id));
				$act = $db -> farr("SELECT * FROM `admins_log_act` WHERE `id` = ?", array($db -> lastInsertId()));
			} else {
				$act = $db -> farr("SELECT * FROM `admins_log_act` WHERE `name` = ? AND `id_mod` = ?", array($act_name, $mod -> id));
			}
			$db -> q("INSERT INTO `admins_log` (`id_user`, `time`, `id_mod`, `id_act`, `msg`, `ip`, `browser`, `browser_full`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array($u -> id, time(), $mod -> id, $act -> id, $msg, $ip, $browser, $_SERVER['HTTP_USER_AGENT']));
			return true;
		} else return false;
	}
	public static function access ($access_name, $user_id = null) {
		global $set, $db, $u;
		if ($user_id)$u = new Users\User($user_id);
		if (!isset($u))return false;
		else {
			if ($u -> spec_access)$spec_access = " AND `id_user` = '".$u -> id."'";
			else $spec_access = " AND `id_group` = '".$u -> getGroup() -> id."'";
			if ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `access` = ?", array($access_name))) {
				$access = $db -> farr("SELECT * FROM `users_groups_accesses_all` WHERE `access` = ?", array($access_name));
				if ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_is` WHERE `id_access` = ?$spec_access", array($access -> id))) {
					return true;
				} else return false;
			} else return false;
		}
	}
	public static function accessCheck($access_name, $user_id = null) {
		global $set, $db, $u;
		if ($user_id)$u = new Users\User($user_id);
		if (!isset($u)) {
			alerts::error_sess('Доступ запрещен!');
			header("Location: /?");
			exit();
		} else {
			if ($u -> spec_access)$spec_access = " AND `id_user` = '".$u -> id."'";
			else $spec_access = " AND `id_group` = '".$u -> getGroup() -> id."'";
			if ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_all` WHERE `access` = ?", array($access_name))) {
				$access = $db -> farr("SELECT * FROM `users_groups_accesses_all` WHERE `access` = ?", array($access_name));
				if ($db -> res("SELECT COUNT(*) FROM `users_groups_accesses_is` WHERE `id_access` = ?$spec_access", array($access -> id))) {
					return true;
				} else {
					alerts::error_sess('Доступ запрещен!');
					header("Location: /?");
					exit();
				}
			} else {
				alerts::error_sess('Доступ запрещен!');
				header("Location: /?");
				exit();
			}
		}
	}
	public static function levelIs ($level) {
		global $set, $db, $u;
		if ($us -> getGroup() -> level >= $level)return true;
		else return false;
	}
}
?>