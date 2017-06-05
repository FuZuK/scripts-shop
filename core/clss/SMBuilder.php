<?
class SMBuilder {
	public $vars = array();
	public $curVar;
	public function addElm () {
		$array = array();
		foreach (func_get_args() as $value) {
			if (!preg_match("|^{(.+?)}(.*)$|", $value, $tmpVar))
				continue;
				$array[$tmpVar[1]] = $tmpVar[2];
		}
		$this -> vars[$this -> curVar][] = $array;
	}
	public function addVar () {
		$array = array();
		foreach (func_get_args() as $value) {
			if (!preg_match("|^{(.+?)}(.*)$|", $value, $tmpVar))
				continue;
				$this -> vars[$tmpVar[1]] = $tmpVar[2];
		}
	}
	public function addCat ($cat) {
		$this -> vars[$cat] = array();
		$this -> curVar = $cat;
	}
	public function setCat ($cat) {
		if (!isset($this -> vars[$cat]))
			$this -> vars[$cat] = array();
		$this -> curVar = $cat;
	}
	public function getVars () {
		echo dumper::dump($this -> vars);
	}
	public function show ($tpl) {
		new SMX($this -> vars, $tpl);
	}
}
?>