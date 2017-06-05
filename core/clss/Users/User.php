<?
namespace Users;
class User {
	public $user_id = null, $exists = false;
	public static $_users = array();

	public function __construct($user_id) {
		global $db;
		$this -> user_id = $user_id;
		$this -> exists = $db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ?", array($this -> user_id)) == 0 ? false : true;
	}

	public function exists() {
		return $this -> exists;
	}

	public function getData($var) {
		return @$this -> getAllData() -> $var;
	}

	public function getAllData() {
		$this -> fillData();
		return self::$_users[$this -> user_id];
	}

	private function fillData() {
		global $db;
		if (!isset(self::$_users[$this -> user_id])) {
			self::$_users[$this -> user_id] = $db -> farr("SELECT * FROM `users` WHERE `id` = ?", array($this -> user_id));
		}
	}

	public function __get($var) {
		$_data = null;
		switch ($var) {
			case 'info':
			$_data = $this -> getInfo();
			break;
			case 'settings':
			$_data = $this -> getSettings();
			break;
			default:
			$_data = $this -> getData($var);
			break;
		}
		return $_data;
	}

	public function __set($var, $val) {
		$this -> $var = $val;
	}

	public function setData($var, $val) {
		global $db;
		$db -> q('UPDATE `users` SET `'.$var.'` = ? WHERE `id` = ?', array($val, $this -> id));
		$this -> $var = $val;
	}

	public function login($url = 1, $show_prev = 1) {
		global $set;
		$return = \TextUtils::escape($this -> login);
		$return = '<span class="user_login"'.($this -> id && $show_prev == 1?' id="user_float_info" user-id="'.$this -> id.'"':null).'>'.$return.'</span>';
		if ($url == 1)$return = \Doc::showLink($set -> profile_page.$this -> id, $return);
		return $return;
	}

	public function icon() {
		global $set;
		$dop_group = null;
		if ($this -> getGroup() -> id == 4)$dop_group .= "_adm";
		elseif ($this -> getGroup() -> id == 3)$dop_group .= "_mod";
		if (!$this -> isOnline())$dop_group .= "_off";
		if ($this -> id != 0) {
			return \Doc::showImage('/images/users_img/'.($this -> info -> sex == 1 ? 'male' : 'female').$dop_group.'.png', array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));
		} else {
			return \Doc::showImage('/images/users_img/male'.$dop_group.'.png', array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));
		}
	}

	public function pw($words = array()) {
		global $set;
		return $words[$this -> info -> sex];
	}

	public function ava_list($with_link = 0, $size = null) {
		global $set;
		if (!$set -> wb)$src = "small_list.jpg";
		else $src = "big_list.jpg";
		if ($size)$src = $size."_list.jpg";
		$orign_size = ($set -> wb ? 80 : 40);
		if ($size == 'small')$orign_size = 40;
		elseif ($size == 'big')$orign_size = 80;
		if (file_exists(DR."images/avatars/".md5($this -> id)."/".$src)) {
			$full_src = \Doc::showImage("/images/avatars/".md5($this -> id)."/".$src, array('class' => 'rad_tlr rad_blr us_ava'));
			if ($with_link)$full_src = \Doc::showLink('/images/avatars/'.md5($this -> id).'/original.jpg', $full_src);
		}
		else {
			$full_src = \Doc::showImage('/images/'.($this -> info -> sex == 1 ? "user_male.png" : "user_female.png"), array('height' => $orign_size, 'width' => $orign_size));
		}
		return $full_src;
	}

	public function ava_prof($with_link = 0) {
		global $set;
		if (!$set -> wb)$src = "small_prof.jpg";
		else $src = "big_prof.jpg";
		
		if (file_exists(DR."images/avatars/".md5($this -> id)."/".$src)) {
			$full_src = \Doc::showImage("/images/avatars/".md5($this -> id)."/".$src, array('class' => 'rad_tlr rad_blr us_ava'));
			if ($with_link)$full_src = \Doc::showLink("/images/avatars/".md5($this -> id)."/original.jpg", $full_src);
		}
		else {
			$full_src = \Doc::showImage('/images/'.($this -> info -> sex == 1?"user_male.png":"user_female.png"), array('height' => ($set -> wb?200:90), 'width' => ($set -> wb?200:90)));
		}
		return $full_src;
	}

	public function rating() {
		global $set;
		if ($this -> rating >= 6 && $this -> rating < 12) {$stars1 = 1; $stars2 = 4;}
		if ($this -> rating >= 12 && $this -> rating < 24) {$stars1 = 2; $stars2 = 3;}
		if ($this -> rating >= 24 && $this -> rating < 36) {$stars1 = 3; $stars2 = 2;}
		if ($this -> rating >= 36 && $this -> rating < 48) {$stars1 = 4; $stars2 = 1;}
		if ($this -> rating >= 48 && $this -> rating < 60) {$stars1 = 5; $stars2 = 0;}
		$image_full = null;
		if (isset($stars1) && isset($stars2)) {
			for ($i = 1; $i <= $stars1; $i++)$image_full .= \Doc::showImage("/images/rating_star1.png");
			for ($i = 1; $i <= $stars2; $i++)$image_full .= \Doc::showImage("/images/rating_star2.png");
		} elseif (isset($this -> pro)) {
			$image_full .= \Doc::showImage("/images/pro.gif", array('class' => 'ic2'));
		}
		return " ".$image_full;
	}

	public function newEventsCount($type) {
	}

	public static function if_user ($arg, $tf = 0) {
		global $u, $set;
		if ($arg == 'no_reg') {  // если юзер не авторизирован
			if (isset($u)) {
				if ($tf == 0) {
					header ("Location: ".$set -> profile_page);
					exit();
				} else return false;
			} else return true;
		} elseif ($arg == 'is_reg') { // если юзер авторизирован
			if (!@$u -> id) {
				if ($tf == 0) {
					\alerts::msg_sess("Для начала пройдите авторизацию");
					header ("Location: ".$set -> auth_page);
					exit();
				} else return false;
			} else return true;
		} elseif ($arg == 'is_admin') {
			if (!isset($admin)) {
				if ($tf == 0) {
					\alerts::error_sess("Вам не хватает прав");
					header ("Location: /");
					exit();
				} else return false;
			}
			else return true;
		} elseif ($arg == 'activated') {
			if (!$u -> info -> wmid || !$u -> info -> wmr) {
				if ($tf == 0) {
					\alerts::error_sess("Заполните свой WMID");
					header("Location: /cab/edit_prof/wmid_wmr");
					exit();
				} else return false;
			} else return true;
		}
	}

	public function avaInstalled () {
		if (file_exists(DR."images/avatars/".md5($this -> id)."/original.jpg"))return true;
		else return false;
	}

	public function getCounter ($item) {
		global $db;
		switch ($item):
		case 'mail':
		return $db -> res("SELECT COUNT(*) FROM `mail` WHERE `id_user` = ? AND `type` = ? AND `read` = ?", array($this -> id, 'to', 0));
		case 'journal':
		return $db -> res("SELECT COUNT(*) FROM `journal` WHERE `id_user` = ? AND `read` = ?", array($this -> id, 0));
		case 'adminka':
			$counter = 0;
			if (\adminka::access('tickets_read_ticket_to_adm') && !\adminka::access('tickets_read_ticket_to_kons'))$tickets_q_where = " AND `type` = '0'";
			elseif (\adminka::access('tickets_read_ticket_to_kons') && !\adminka::access('tickets_read_ticket_to_adm'))$tickets_q_where = " AND `type` = '1'";
			elseif (\adminka::access('tickets_read_ticket_to_adm') && \adminka::access('tickets_read_ticket_to_kons'))$tickets_q_where = '';
			if (isset($tickets_q_where)) {
				$counter += $db -> res("SELECT COUNT(*) FROM `tickets` WHERE `opened` = ?$tickets_q_where", array(1));
			}
			if (\adminka::access('shop_admit_tov')) {
				$counter += $db -> res("SELECT COUNT(*) FROM `shop_tovs` WHERE `deleted` = '0' AND `admitted` = '0'");
			}
			if (\adminka::access('adminka_withdrawals')) {
				$counter += $db -> res("SELECT COUNT(*) FROM `withdrawals` WHERE `acted` = 0");
			}
			return $counter;
		endswitch;
	}

	public function recountRating() {
		global $db;
		$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE `id_user` = ?", array($this -> id));
		$rating_seller = 0;
		while ($good_us = $q -> fetch()) {
			$rating_seller += $db -> res("SELECT SUM(`rating`) FROM `users_shop_goods_reviews` WHERE `id_good` = ?", array($good_us -> id));
		}
		$this -> rating = $rating_seller;
		$db -> q("UPDATE `users` SET `rating` = ? WHERE `id` = ?", array($rating_seller, $this -> id));
		return $rating_seller;
	}

	public function getCountGoodsInBasket() {
		global $db;
		return $db -> res("SELECT COUNT(*) FROM `basket` INNER JOIN `users_shop_goods` ON `basket`.`id_good` = `users_shop_goods`.`id` WHERE `basket`.`id_user` = ? AND `users_shop_goods`.`in_block` = '0' AND `users_shop_goods`.`deleted` = '0' AND `users_shop_goods`.`shop_id_category` != '0'", array($this -> id));
	}

	public function confirmNewEmail() {
		$info = $this -> info;
		$info -> setData('email', $info -> email_new);
		$info -> setData('email_new', '');
		$info -> setData('email_new_code', '');
	}

	public function confirmNewPhoneNumber() {
		$info = $this -> info;
		$info -> setData('phone', $info -> phone_new);
		$info -> setData('phone_new', '');
		$info -> setData('phone_new_id_sms', 0);
		$info -> setData('phone_new_code', '');
	}

	public function getInfo() {
		return new Info($this -> user_id);
	}

	public function getSettings() {
		return new Settings($this -> user_id);
	}

	public function getNotificationsSettings() {
		return new NotificationsSettings($this -> user_id);
	}

	public function getGroup() {
		return new Group($this -> group);
	}

	public function getSecCode() {
		return md5($this -> pass);
	}

	public function moneyPlus($money) {
		$this -> setData('money', $this -> money + $money);
		$this -> setData('money_personal', $this -> money_personal + $money);
	}

	public function moneyMinus($money) {
		$this -> setData('money', $this -> money - $money);
		$this -> setData('money_personal', $this -> money_personal - $money);
	}

	public function isOnline() {
		global $set;
		return $this -> info -> date_last >= time() - $set -> time_online_user;
	}
}
?>