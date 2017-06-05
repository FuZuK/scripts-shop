<?
include('../core/st.php');
if (Sys::incFileExists('act', 'inc', @$_GET['act']))
	include_once(Sys::getIncFile('act', 'inc', @$_GET['act']));
else {
	header("Location: /")
	exit();
}
include_once(FOOT);
?>