<? # by Killer
class alerts {
	public static $form_errors_reged;
	public static $form_errors_used;
	public static function error($error_text=null) {
		global $error;
		if ($error_text != null)$error_text = $error_text;
		elseif (isset($error))$error_text = $error;
		if ($error_text)return "<div class=\"content\" style=\"background: transparent;\"><div class=\"error_outline\"><div class=\"error_inline\">$error_text</div></div></div>";
	}
	public static function msg_sess($msg_text) {
		$_SESSION['msg_sess'] = $msg_text;
	}
	public static function error_sess($error_text) {
		$_SESSION['error_sess'] = $error_text;
	}
	public static function msg($msg_text=null) {
		if ($msg_text) {
			return "<div class=\"content\" style=\"margin: -4px; background: transparent;\"><div class=\"msg_outline\"><div class=\"msg_inline\">$msg_text</div></div></div>";
		}
	}
	public static function list_empty($msg) {
		return self::error($msg);
	}
	public static function reg_form_error($key) {
		self::$form_errors_reged[$key] = true;
	}
	public static function use_form_error($key) {
		self::$form_errors_used[$key] = true;
		if (isset(self::$form_errors_reged[$key]))return true;
		else return false;
	}
	public static function _destruct () {
		print_r(self::$form_errors_reged);
		print_r(self::$form_errors_used);
	}
}
?>