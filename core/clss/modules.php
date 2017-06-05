<? # by Killer
class modules {
	public $modulesDir;
	public $modulesThemeDir;
	public function __construct () {
		global $theme, $set;
		$this -> setModulesDir = DR."core/mods";
		$this -> setThemeModulesDir = DR."css/themes/".$theme -> theme."/".$theme -> modules;
	}

	public function LoadModules($modules) {
		global $u, $db, $set, $theme;
		if (is_array($modules)) {
			foreach ($modules as $module) {
				self::LoadModules($module);
			}
		} else {
			if (self::moduleExists($modules)) {
				include(self::modulePath($modules));
			}
		}
	}

	public function moduleExists($module) {
		global $u, $sys, $text, $db;
		if (!self::modulePath($module))return false;
		else return true;
	}

	public function modulePath($module) {
		global $u, $sys, $text, $db;
		if (file_exists($this -> modulesThemeDir."/".TextUtils::escape($module).".php")) {
			return $this -> modulesThemeDir."/".TextUtils::escape($module).".php";
		} elseif (file_exists($this -> modulesDir."/".TextUtils::escape($module).".php")) {
			return $this -> modulesDir."/".TextUtils::escape($module).".php";
		} else {
			return false;
		}
	}

	static function getIncFiles ($dir, $prefix) {
		$d = opendir($dir);
		$files = array();
		while ($file = readdir($d)) {
			if ($file == '.' || $file == '..' || !preg_match("|^$prefix\.(.+?)\.php$|", $file, $tmpVar))
				continue;
			preg_match("|^$prefix\.(.+?)\.php$|", $file, $tmpVar);
			$files[] = $tmpVar[1];
		}
		return $files;
	}

	public function __set($var, $value) {
		switch ($var):
		case 'setModulesDir':
		$this -> modulesDir = $value;
		case 'setThemeModulesDir':
		$this -> modulesThemeDir = $value;
		endswitch;
	}
}
?>