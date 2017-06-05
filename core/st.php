<?
ob_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/core/includes/defines.php');
// включаем  показ ошибок
error_reporting(E_ALL); // включаем показ ошибок
ini_set('display_errors',true); // включаем показ ошибок

include_once(CORE.'includes/classes.php');
# старт сессии
include_once(CORE.'includes/sessionStart.php');
# подключение к бд
include_once(CORE.'includes/dataBaseConnecting.php');
$set = new configs('set.dat');
include_once(CORE.'includes/browserInfo.php');
if (isset($_SESSION['id_user']) && $db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ?", array(intval($_SESSION['id_user']))) == 1) {
	$u = new Users\User(intval($_SESSION['id_user']));
	$db -> q("UPDATE `users_infos` SET `date_last` = ? WHERE `id` = ? LIMIT 1", array(time(), $u -> info -> id));
	$u -> type_input = 'session';
	include_once(CORE.'includes/user.php');
} else {
	include_once(CORE.'includes/guest.php');
}
include_once(CORE.'includes/randomSymbols.php');
define('ICON_WH', $set -> wb ? 24 : 16);
define('ICON_CLASS', !$set -> wb ? 'ic_small' : 'ic_large');
define('PREVIEW_LIST_WH', !$set -> wb ? 50 : 90);
define('PREVIEW_PAGE_WH', !$set -> wb ? 130 : 250);
define('PREVIEW_ZOOM_WH', !$set -> wb ? 400 : 800);
include_once(CORE.'includes/theme.php');
?>