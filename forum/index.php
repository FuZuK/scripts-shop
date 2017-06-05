<?
include('../core/st.php');
$title = 'Форум';
if (Sys::incFileExists('act', 'inc', @$_GET['act']))
	include_once(Sys::getIncFile('act', 'inc', @$_GET['act']));
else
	include_once(Sys::getIncFilePath('act', 'inc', 'index'));
include_once(FOOT);
?>