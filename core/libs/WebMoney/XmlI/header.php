<?php
if (!function_exists('bcpowmod')) {
	function bcpowmod($number, $degree, $remnant_divider) {
		if ($degree < 0 || $remnant_divider == 0) return;
		if ($degree == 0) return 1;
		$new_number = pow($number, $degree);
		$full_fraction = floor($new_number / $remnant_divider);
		$remnant = ceil($new_number - ($full_fraction * $remnant_divider));
		if ($remnant < 0) $remnant = 1;
		return $remnant;
	}
}
if (!isset($class)) { $class = 'WMXI'; }
require_once(DR."core/libs/WebMoney/XmlI/$class.php");
define('WMXI_LOG', DR.'core/libs/WebMoney/XmlI/wmxi.log');
$wmxi = new $class(realpath(DR."core/libs/WebMoney/XmlI/WMXI.crt"), 'UTF-8');
define('WMID', $set -> sys_wmid);
define('PASS', '1nt70rwboe17s0up');
define('KWMFILE', DR."core/libs/WebMoney/XmlI/keys/".WMID.".kwm");
if (defined('EKEY') && defined('NKEY')) { $wmkey = array('ekey' => EKEY, 'nkey' => NKEY); }
elseif (defined('KWMDATA')) { $wmkey = array('pass' => PASS, 'data' => KWMDATA); }
elseif (defined('KWMFILE')) { $wmkey = array('pass' => PASS, 'file' => KWMFILE); }
if (isset($wmkey)) { $wmxi->Classic(WMID, $wmkey); }
?>