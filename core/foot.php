<?
if (!file_exists(DR."css/themes/{$theme -> theme}/{$theme -> includes}/foot.php"))die("Не удалось найти файл инициализации <b>".DR."css/themes/{$theme -> theme}/{$theme -> includes}/foot.php</b>");
include_once(DR."css/themes/{$theme -> theme}/{$theme -> includes}/foot.php");
exit();
?>