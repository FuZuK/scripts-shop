<?
class CActions {
	const SHOW_ALL = 1, SHOW_ONLY_LINKS = 2, SHOW_ONLY_ICONS = 3, COMMENT_ACTIONS = 4;
	private static $useSeparator = true;
	private static $separator = ' - ';
	private static $elements;
	private static $class = 'mod';
	private static $style = '';
	private static $showType = self::SHOW_ONLY_LINKS;

	private function __construct() {}

	public static function addAction($action_link, $action_text, $action_icon = null) {
		self::$elements[] = array($action_link, $action_text, $action_icon);
	}

	public static function setShowType($type) {
		self::$showType = $type;
	}

	public static function setStyle($style) {
		self::$style = (self::$style ? ' ' : '').$style;
	}

	public static function setClass($class) {
		self::$class = $class;
	}

	public static function setSeparator($separator) {
		self::$separator = $separator;
	}

	public static function setUseSeparator($useSeparator = true) {
		self::$useSeparator = $useSeparator;
	}

	public static function showActions($clean = true) {
		if (count(self::$elements) == 0) return;
		$echo_actions = null;
		foreach (self::$elements as $element) {
			list($action_link, $action_text, $action_icon) = $element;
			if (empty($echo_actions)) {
				if (self::$showType == self::COMMENT_ACTIONS)
					$echo_actions .= "<hr class='custom'>\n";
				$echo_actions .= "<div class='".self::$class."' style='".self::$style."'>\n";
			}
			elseif (count(self::$elements) > 1 && self::$useSeparator) $echo_actions .= self::$separator;
			switch (self::$showType) {
				case self::SHOW_ALL:
				$echo_actions .= ($action_icon ? Doc::showImage($action_icon, ARRAY('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)).' ':'').Doc::showLink($action_link, $action_text);
				break;
				case self::SHOW_ONLY_ICONS:
				if ($action_icon)
					$echo_actions .= Doc::showLink($action_link, Doc::showImage($action_icon, ARRAY('class' => ICON_CLASS)));
				break;
				case self::SHOW_ONLY_LINKS:
				case self::COMMENT_ACTIONS:
				$echo_actions .= Doc::showLink($action_link, $action_text);
				break;
			}
		}
		$echo_actions .= "</div>\n";
		if ($clean)
			self::clean();
		return $echo_actions;
	}

	public static function clean() {
		self::$elements = array();
	}

	public static function getCount() {
		return count(self::$elements);
	}
}
?>