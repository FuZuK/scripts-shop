<?
// функция автоматической загрузки классов
include_once(CLASSES.'Sys.php');
function _class_autoload($class_name) {
	Sys::loadClass($class_name);
}
spl_autoload_register('_class_autoload');
?>