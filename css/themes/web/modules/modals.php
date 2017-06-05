<?
if (!isset($u)) {
	include_once(MODALFILES.'auth.php');
	include_once(MODALFILES.'reg.php');
}
if (adminka::access('adminka_enter')) {
	include_once(MODALFILES.'adminka.php');
}
?>