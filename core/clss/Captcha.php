<?
class Captcha {
	const SESSION_VAR_PREFIX = 'Captcha.';
	const FORM_INPUT_NAME = 'captcha';
	public static $minLength = 4;
	public static $maxLength = 6;
	public static $testLimit = 3;
	private static $_code;
	public static function generateVerifyCode() {
		if(self::$minLength > self::$maxLength)
			self::$maxLength = self::$minLength;
		if(self::$minLength < 3)
			self::$minLength = 3;
		if(self::$maxLength > 20)
			self::$maxLength = 20;
		$length = mt_rand(self::$minLength, self::$maxLength);

		$letters = '1234567890bcdfghjklmnpqrstvwxyz';
		$vowels = 'aeiou';
		$code = '';
		for($i = 0; $i < $length; ++$i) {
			if ($i % 2 && mt_rand(0, 10) > 2 || !($i % 2) && mt_rand(0,10) > 9)
				$code .= $vowels[mt_rand(0,4)];
			else
				$code .= $letters[mt_rand(0,20)];
		}
		self::$_code = $code;
		return $code;
	}

	public static function getVerifyCode($regenerate = false) {
		$name = self::SESSION_VAR_PREFIX.'Code';
		if(!isset($_SESSION[$name]) || $_SESSION[$name] === null || $regenerate) {
			$_SESSION[$name] = self::generateVerifyCode();
			$_SESSION[self::SESSION_VAR_PREFIX.'Count'] = 1;
		}
		return $_SESSION[$name];
	}

	public static function validate($caseSensitive = false) {
		$code = self::getVerifyCode();
		$input = $_POST[self::FORM_INPUT_NAME];
		$valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
		$name = self::SESSION_VAR_PREFIX.'Count';
		$_SESSION[$name]++;
		if ($_SESSION[$name] > self::$testLimit && self::$testLimit > 0 || $valid) {
			$cache = new Cache();
			$cache -> setAction('captcha|'.session_id());
			$cache -> deleteCache();
			self::getVerifyCode(true);
		}
		return $valid;
	}

	public static function renderImage() {
		$string = self::getVerifyCode();
		$width = \TextUtils::length($string) * 22;
		$cache = new Cache();
		$cache -> setAction('captcha|'.session_id());
		if (!$cache -> checkIsCached()) {
			$cache -> setAction('fonts|default');
			$img = imageCreateTrueColor($width, 30);
			$img_x = imagesx($img);
			$img_y = imagesy($img);
			$white = imageColorAllocate($img, 255, 255, 255);
			imageFill($img, 1, 1, $white);

			$fonts_dir = FONTS_DIR;
			$captchas_dir = CAPTCHAS_DIR;
			if (!$cache -> checkIsCached()) {
				// находим шрифты
				$dir_fonts = opendir($fonts_dir);
				$fonts = array();
				while ($file = readdir($dir_fonts)) {
					if ($file != '.' && $file != '..' && preg_match("|.ttf$|", $file)) {
						$fonts[] = $file;
					}
				}
				$cache -> addToCache(serialize($fonts));
			}
			$fonts = unserialize($cache -> getCache());

			$cache -> setAction('captchas|default');
			if (!$cache -> checkIsCached()) {
				// находим капчи
				$dir_captchas = opendir($captchas_dir);
				$captchas = array();
				while ($file = readdir($dir_captchas)) {
					if ($file != '.' && $file != '..' && preg_match("|.php$|", $file)) {
						$captchas[] = $file;
					}
				}
				$cache -> addToCache(serialize($captchas));
			}
			$captchas = unserialize($cache -> getCache());

			// выбираем случайный шрифт
			$font = $fonts_dir.'/'.$fonts[mt_rand(0, count($fonts) - 1)];

			// выбираем случайную капчу
			$captcha = $captchas_dir.'/'.$captchas[mt_rand(0, count($captchas) - 1)];
			// $captcha = $captchas_dir.'/arc_pieces.php';
			$cache -> setAction('captcha|'.session_id());
			$cache -> init();
			include_once($captcha);
			imagepng($img);
			$cache -> run();
			imagedestroy($img);
		}
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header("Content-Type: image/png");
		echo $cache -> getCache();
	}

	public static function getCaptchaImageSource() {
		return "/images/captcha/image.png?rand=".rand(10000, 99999);
	}
}
?>