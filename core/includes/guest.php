<?
// if (!isset($u) && $ip && $browser) {
// 	if ($db -> res("SELECT COUNT(*) FROM `guests` WHERE `ip` = ? AND `browser` = ? AND `browser_full` = ? LIMIT 1", array($ip, $browser, $_SERVER['HTTP_USER_AGENT']))) {
// 		$guest = $db -> farr("SELECT * FROM `guests` WHERE `ip` = ? AND `browser` = ? AND `browser_full` = ? LIMIT 1", array($ip, $browser, $_SERVER['HTTP_USER_AGENT']));
// 		$db -> q("UPDATE `guests` SET `date_last` = ?, `url` = ?, `pereh` = ? WHERE `id` = ? LIMIT 1", array(time(), $_SERVER['SCRIPT_NAME'], $guest -> pereh + 1, $guest -> id));
// 	} else {
// 		$db -> q("INSERT INTO `guests` (`ip`, `browser`, `browser_full`, `date_aut`, `date_last`, `url`, `pereh`) VALUES (?, ?, ?, ?, ?, ?, ?)", array($ip, $browser, $_SERVER['HTTP_USER_AGENT'], time(), time(), $_SERVER['SCRIPT_NAME'], 1));
// 	}
// }
?>