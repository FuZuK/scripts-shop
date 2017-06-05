<? # by Killer
class imgs {
	public static function show($file_name, $params=array(), $dir="/images/") {
		$image_src = $dir."$file_name";
		$params_img = null;
		foreach ($params as $key => $value) {
			$params_img .= " $key='$value'";
		}
		return "<img src='$image_src'$params_img>";
	}
	public static function img($src, $params=array()) {
		$params_img = null;
		foreach ($params as $key => $value) {
			$params_img .= " $key='$value'";
		}
		return "<img src='$src'$params_img>";
	}
}
?>