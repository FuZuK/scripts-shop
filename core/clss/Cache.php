<?
class Cache {
	const CACHE_KEY_PREFIX = 'Cache.';
	public $duration = 60;
	private $_action;
	private $_cache_dir;
	private $_cache;

	public function __construct() {
		$this -> _cache_dir = TMP;
	}

	public function setAction($action) {
		$this -> _action = $action;
	}

	public function init() {
		ob_start();
	}

	public function run() {
		if (!$this -> checkIsCached()) {
			$cache = ob_get_clean();
			$this -> addToCache($cache);
		}
		echo $this -> getCache();
	}

	public function addToCache($cache) {
		$this -> _cache = $cache;
		if (!(@$f = fopen($this -> getCacheFilePath(), 'w')))return false;
		fwrite($f, $cache);
		fclose($f);
		return true;
	}

	public function getCache() {
		if ($this -> checkIsCached()) {
			return file_get_contents($this -> getCacheFilePath());
		} elseif ($this -> _cache !== null) {
			return $this -> _cache;
		}
	}

	public function checkIsCached() {
		if (file_exists($this -> getCacheFilePath()) && (time() - $this -> duration) > filemtime($this -> getCacheFilePath())) {
			$this -> deleteCache();
		}
		return file_exists($this -> getCacheFilePath());
	}

	public function deleteCache() {
		return @unlink($this -> getCacheFilePath());
	}

	public function getCacheFilePath() {
		return $this -> _cache_dir.self::CACHE_KEY_PREFIX.md5($this -> _action);
	}

	public function clean($duration = 0) {
		if ($duration != 0)
			$this -> duration = $duration;
		if (!($odir = opendir($this -> _cache_dir))) return;
		$count_deleted_files = 0;	
		while ($file = readdir($odir)) {
			$file_full_path = $this -> _cache_dir.$file;
			if (preg_match("|^".self::CACHE_KEY_PREFIX."|", $file) && (time() - $this -> duration) > filemtime($file_full_path))
				if (unlink($file_full_path))
					$count_deleted_files++;
		}
		return $count_deleted_files;
	}
}
?>