<?
$title = 'Подписка';
include('../../core/st.php');
$act = TextUtils::escape(@$_GET['act']);
$array_act_includes = array(
	'subscribe',  
	'unsubscribe'
);
if (in_array($act, $array_act_includes) && file_exists("inc/act.".$act.".php")) {
	include("inc/act.".$act.".php");
} else {
	header("Location: /?");
}
?>