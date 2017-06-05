<?
class Doc {
	public static $elements;
	public static $title;

	public static function addCSS($href) {
		self::addLink(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $href));
	}

	public static function addJS($src) {
		self::addElement('script', array('src' => $src), true);
	}

	public static function addLink($params) {
		self::addElement('link', $params);
	}

	public static function addElement($name, $params = array(), $double = false) {
		if (!is_array(self::$elements))
			self::$elements = array();
		if (!is_array($params))
			$params = array();
		$element = '<'.$name;
		foreach ($params as $key => $value) {
			$element .= ' '.$key.'="'.$value.'"';
		}
		$element .= ($double ? '></'.$name.'>' : ' />')."\n";
		self::$elements[$name][] = $element;
	}

	public static function getTitle() {
		return self::$title;
	}

	public static function setTitle($title) {
		self::$title = $title;
	}

	public static function appendTitle($append_title) {
		self::setTitle(self::getTitle().$append_title);
	}

	public static function prependTitle($prepend_title) {
		self::setTitle($prepend_title.self::getTitle());
	}

	public static function getElements($name) {
		if (!(isset(self::$elements[$name]) && count(self::$elements[$name])))return;
		$head_elements = '';
		foreach (self::$elements[$name] as $element) {
			$head_elements .= $element;
		}
		return $head_elements;
	}

	public static function getLinks() {
		return self::getElements('link');
	}

	public static function getJSs() {
		return self::getElements('script');
	}

	public static function addVerticalSpace() {
		return "<div class='vertical_space'></div>\n";
	}

	public static function addClear() {
		return "<div class='clear'></div>\n";
	}

	public static function loc($link) {
		header("Location: ".$link);
	}

	public static function showImage($src, $params = false) {
		$image = '<img src="'.$src.'"';
		if (is_array($params) && count($params) > 0)
			foreach ($params as $attr_name => $attr_value)
				$image .= ' '.$attr_name.'="'.$attr_value.'"';
		$image .= ' />';
		return $image;
	}

	public static function showLink($link_href, $link_text, $params = false) {
		$link = '<a href="'.$link_href.'"';
		if (is_array($params) && count($params) > 0)
			foreach ($params as $attr_name => $attr_value)
				$link .= ' '.$attr_name.'="'.$attr_value.'"';
		$link .= '>'.$link_text.'</a>';
		return $link;
	}

	public static function back($name, $link) {
		if (is_array($name)) {
		} else {
			echo "<hr>\n<a href='{$link}' class='back'>{$name}</a>\n";
		}
	}

	public static function listEmpty($msg) {
		echo alerts::list_empty($msg);
	}
}
?>