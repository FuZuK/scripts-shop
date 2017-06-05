<?
include_once('../core/st.php');
Users\User::if_user('is_reg');
$us = $u;
$link_back = "/edit_profile";
if (Sys::incFileExists('act', 'inc', @$_GET['act']))
	include_once(Sys::getIncFile('act', 'inc', @$_GET['act']));
else
	include_once(Sys::getIncFilePath('act', 'inc', 'index'));
include(FOOT);
?>