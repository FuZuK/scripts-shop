<?
class TimeUtils {
	private function __construct() {}

	public static function currentTime() {
		return time();
	}

	public static function show($time) {
		$formatted = '';
		$format_hm = 'H:m';
		$format_jnY = 'j n Y';
		$format_dMY = 'd M Y';
		$format = $format_dMY.' в '.$format_hm;
		if (date($format_jnY) == date($format_jnY, $time))
			$format = 'Сегодня в '.$format_hm;
		if (date($format_jnY, strtotime('yesterday')) == date($format_jnY, $time))
			$format = 'Вчера в '.$format_hm;
		$formatted = date($format, $time);
		$array_months_eng = array('Jan', 'Feb', 'Mar', 'May', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		$array_months_ru = array('Янв', 'Фев', 'Марта', 'Мая', 'Апр', 'Июня', 'Июля', 'Авг', 'Сент', 'Окт', 'Ноября', 'Дек');
		$formatted = str_replace($array_months_eng, $array_months_ru, $formatted);
		return $formatted;
	}

	public static function showOnlyWithDayMonthYear($time) {
		$formatted = '';
		$format_dMY = 'd M Yг.';
		$format = $format_dMY;
		$formatted = date($format, $time);
		$array_months_eng = array('Jan', 'Feb', 'Mar', 'May', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		$array_months_ru = array('Янв', 'Фев', 'Марта', 'Мая', 'Апр', 'Июня', 'Июля', 'Авг', 'Сент', 'Окт', 'Ноября', 'Дек');
		$formatted = str_replace($array_months_eng, $array_months_ru, $formatted);
		return $formatted;
	}
}
?>