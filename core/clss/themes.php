<? # by Killer
class themes {
	public $theme;
	public $_settings;
	public function setTheme($theme) {
		$this -> theme = $theme;
		if (!self::themeExists($theme))die("Error: Theme \"$theme\" not founded!");
		self::loadSettings();
	}


	public function themeExists($theme) {
		if (is_dir(DR."css/themes/".$theme))return true;
		return false;
	}

	public function loadSettings () {
		if (!$this -> theme)die("Theme not initialized!");
		$ini = new ini();
		$ini_read = $ini -> read(DR."css/themes/".$this -> theme."/.settings");
		$this -> _settings = $ini_read;
	}

	public function __get($var) {
		if (isset($this -> _settings[$var]))return $this -> _settings[$var];
		else return false;
	}
}
?>