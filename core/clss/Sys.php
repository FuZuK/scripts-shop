<?
final class Sys {
	private function __construct() {}
	public static function getIncFiles($prefix, $inc_dir) {
		$inc_dir = realpath($inc_dir);
		$cache = new Cache();
		$cache -> setAction('inc_files|'.$inc_dir);
		if (!$cache -> checkIsCached()) {
			$inc_files = array();
			$dopen = opendir($inc_dir);
			while ($inc_file = readdir($dopen)) {
				if (!preg_match("|^$prefix\.(.*)\.php|", $inc_file, $tmp_inc_file)) continue;
				$inc_files[] = $tmp_inc_file[1];
			}
			closedir($dopen);
			$cache -> addToCache(serialize($inc_files));
		}
		return unserialize($cache -> getCache());
	}

	public static function incFileExists($prefix, $inc_dir, $get_inc_file) {
		if (in_array($get_inc_file, static::getIncFiles($prefix, $inc_dir)) && file_exists(static::getIncFilePath($prefix, $inc_dir, $get_inc_file))) return true;
		return false;
	}

	public static function getIncFile($prefix, $inc_dir, $get_inc_file) {
		if (static::incFileExists($prefix, $inc_dir, $get_inc_file)) return static::getIncFilePath($prefix, $inc_dir, $get_inc_file);
		return static::getIncFilePath($prefix, $inc_dir, 'index');
	}

	public static function getIncFilePath($prefix, $inc_dir, $get_inc_file) {
		return realpath($inc_dir).'/'.$prefix.'.'.$get_inc_file.'.php';
	}

	public static function loadExceptionClass($class_name) {
		self::loadClass('Exceptions.'.$class_name);
	}

	public static function loadClass($class_name) {
		$class_full_name = $class_name;
		if (strstr($class_name, '\\')) {
			$class_full_name = str_replace('\\', '/', $class_full_name);
		}
		$class_path = CLASSES.$class_full_name.'.php';
		// echo $class_path.'<br />';
		if (file_exists($class_path)) include_once($class_path);
	}
}
?>