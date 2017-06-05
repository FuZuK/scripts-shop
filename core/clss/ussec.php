<?
class ussec {
	# проверки
	public static function check_g() {
		if (self::get() == TextUtils::escape($_GET['ussec']) || self::get() == TextUtils::escape($_GET['ussec']))return true;
		else return false;
	}
	public static function check_p() {
		if (self::get() == TextUtils::escape($_POST['ussec']))return true;
		else return false;
	}
	public static function get() {
		global $u;
		if (!isset($u))return "NO_AUTH";
		return $u -> getSecCode();
	}
	public static function input() {
		return "<input type='hidden' name='ussec' value='".self::get()."'>\n";
	}
	public static function link() {
		return "ussec=".self::get();
	}
}
?>