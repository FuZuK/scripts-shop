<?
$title = 'Главная';
include('core/st.php');
include(HEAD);
$main_page = 1;
$modules = new modules();
$modules -> LoadModules(array(
	"newGoods", 
	"lastSolds", 
	"monthLeaders", 
	"mainMenu"
));
include(FOOT);
?>