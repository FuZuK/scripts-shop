<?
class Configs {
	public $dir, $file;
	private static $_configs;

	public function __construct($file) {
		$this -> dir = DR.'core/settings/';
		$this -> file = $file;
		$this -> open();
	}

	private function open() {
		if (!is_file($this -> getPath())) 
			@fclose(fopen($this -> getPath(), 'w'));
		$this -> fillData();
	}

	public function getPath() {
		return $this -> dir . $this -> file;
	}

	private function fillData() {
		if (!isset(self::$_configs[$this -> getPath()])) {
			self::$_configs[$this -> getPath()] = unserialize(file_get_contents($this -> getPath()));
		}
	}

	private function getAllData() {
		return self::$_configs[$this -> getPath()];
	}

	private function getData($arg) {
		$data = $this -> getAllData();
		return isset($data[$arg]) ? $data[$arg] : null;
	}

	public function save() {
		file_put_contents($this -> getPath(), serialize($this -> getAllData()));
	}

	public function __get($arg) {
		return $this -> getData($arg);
	}

	public function __set($arg, $val) {
		self::$_configs[$this -> getPath()][$arg] = $val;
	}
}
?>