<?
$array_mod_includes = array(
	'seller', 
	'site'
);
if (in_array(TextUtils::escape(@$_GET['mod']), $array_mod_includes) && file_exists("inc/act.subscribe.".TextUtils::escape(@$_GET['mod']).".php")) {
	include("inc/act.subscribe.".TextUtils::escape(@$_GET['mod']).".php");
} else {
	header("Location: /?");
	exit();
}
include(FOOT);
?>