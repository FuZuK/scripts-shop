<?
if (!file_exists(DR."css/themes/{$theme -> theme}/{$theme -> includes}/head.php"))die("Не удалось найти файл инициализации <b>".DR."css/themes/{$theme -> theme}/{$theme -> includes}/head.php</b>");
include_once(DR."css/themes/{$theme -> theme}/{$theme -> includes}/head.php");
if (isset($_SESSION['msg_sess'])) {
	echo alerts::msg($_SESSION['msg_sess']);
	unset($_SESSION['msg_sess']);
}
if (isset($_SESSION['error_sess'])) {
	echo alerts::error($_SESSION['error_sess']);
	unset($_SESSION['error_sess']);
}
?>