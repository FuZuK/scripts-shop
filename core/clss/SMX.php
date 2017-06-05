<?
require_once DR.'core/libs/Smarty/Smarty.class.php';
class SMX extends Smarty {
	public function __construct ($assigns = array(), $tpl = null) {
		global $u, $db, $set, $theme;
		if (count($assigns)) {
			foreach ($assigns as $key => $value) {
				$this -> assign($key, $value);
			}
		}
		parent::__construct();
		$this -> template_dir = DR.'css/themes/'.$theme -> theme.'/templates';
		$this -> compile_dir = DR.'core/tmp';
		$this -> assign('URL', $_SERVER['REQUEST_URI']);
		$this -> assign('DR', DR);
		$this -> assign('u', $u);
		$this -> assign('set', $set);
		$this -> assign('theme', $theme);
		$this -> assign('_SESSION', $_SESSION);
		$this -> assign('SESS', @session_id());
		$this -> assign('SESSNAME', @session_name());

		$this -> assign("form_sets", array('class' => 'content', 'text_class' => 'rad_tlr rad_blr main_inp', 'select_class' => 'rad_tlr rad_blr main_inp', 'submit_class' => 'rad_tlr rad_blr main_sub', 'title_class' => 'form_q'));
        $this -> allow_php_tag = true;
        $this -> setCaching(Smarty::CACHING_OFF);
        $this -> setCompileCheck(true);
        // $this -> error_reporting = false;
		if ($tpl)
			$this -> display($tpl);
	}
	function BlockS() {
		ob_start();
	}
	function BlockF() {
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	function display ($template, $cache_id = NULL, $compile_id = NULL, $parent = NULL) {
		if (!file_exists($this -> template_dir.'/'.$template))
			$this -> template_dir = TPLS;
		parent::display($template);
	}
}
?>