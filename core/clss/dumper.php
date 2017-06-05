<? # by Killer
class dumper {
	public static function get(&$obj, $leftSp="") {
		if (is_array($obj)) {
			$type = "Array[".count($obj)."]";
		} elseif (is_object($obj)) {
			$type = "Object";
		} elseif (gettype($obj) == 'boolean') {
			return $obj?"true" : "false";
		} else {
			return "\"$obj\"";
		}
		$buf = $type;
		$leftSp .= "         ";
		for (Reset($obj); list($k, $v) = each($obj);) {
			if ($k === "GLOBALS")continue;
			$buf .= "\n$leftSp$k => ".self :: get($v, $leftSp);
		}
		return $buf;
	}
	public static function dump($obj) {
		return "<font size=2'><pre>".htmlspecialchars(self :: get($obj))."</pre></font>";
	}
	public static function arrayToObject($array) {
		$obj = (object)null;
		foreach ($array as $key => $value) {
			$obj -> $key = is_array($value) || is_object($value) ? self::arrayToObject($value) : $value;
		}
		return $obj;
	}
	public static function objectToArray($object) {
		$arr = array();
		foreach ($object as $key => $value) {
			$arr[$key] = is_object($value) || is_array($value) ? self::objectToArray($value) : $value;
		}
		return $arr;
	}
}
?>