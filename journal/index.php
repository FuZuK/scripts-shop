<?
include('../core/st.php');
Users\User::if_user('is_reg');
if (Sys::incFileExists('act', 'inc', @$_GET['act']))
	include_once(Sys::getIncFile('act', 'inc', @$_GET['act']));
else
	include_once(Sys::getIncFilePath('act', 'inc', 'index'));
include_once(FOOT);
?>