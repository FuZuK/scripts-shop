<?
class navi {
	public $count_pages = 0;
	public $page = 1;
	public $start = 0;
	public $show = null;
	public $rop = 10; // к-тво результатов на странице
	public function __construct($count_results, $page_link) {
		global $set;
		$this -> rop = $set -> results_on_page;
		# считаем к-тво страниц
		if ($count_results <> 0) {
			$this -> count_pages = ceil($count_results / $this -> rop);
		} else $this -> count_pages = 1;
		# узнаем текущую страницу
		if (isset($_GET['page'])) {
			if (is_numeric($_GET['page']))$this -> page = intval($_GET['page']);
		}
		if ($this -> page < 1)$this -> page = 1;
		if ($this -> page > $this -> count_pages)$this -> page = $this -> count_pages;
		# узнаем начальный результат
		$this -> start = $this -> rop * $this -> page - $this -> rop;
		# выводим навигацию
		if ($this -> count_pages > 1) {
			$this -> show .= "<hr>\n";
			$this -> show .= "<ul class='navigation'>\n";
			if ($this -> page != 1)$this -> show .= "<li><a href='".$page_link."page=1' title='Страница №1'>1</a></li>";
			else $this -> show .= "<li><span>1</span></li>";
			for ($ot=-3; $ot<=3; $ot++) {
				if ($this -> page + $ot > 1 && $this -> page + $ot < $this -> count_pages) {
					if ($ot == -3 && $this -> page + $ot > 2)$this -> show .= "..";
					if ($ot != 0)$this -> show .= "<li><a href='".$page_link."page=".($this -> page + $ot)."' title='Страница №".($this -> page + $ot)."'>".($this -> page + $ot)."</a></li>";
					else $this -> show .= "<li><span>".($this -> page + $ot)."</span></li>";
					if ($ot == 3 && $this -> page + $ot < $this -> count_pages - 1)$this -> show .= "..";
				}
			}
			if ($this -> page != $this -> count_pages)$this -> show .= "<li><a href='".$page_link."page=".$this -> count_pages."' title='Страница №".$this -> count_pages."'>".$this -> count_pages."</a></li>";
			elseif ($this -> count_pages > 1)$this -> show .= "<li><span>".$this -> count_pages."</span></li>";
			$this -> show .= "</ul>\n";
		}
	}
	public function __call ($fnc_name, $args) {
		switch ($fnc_name):
		case 'count_pages':
		return @$this -> count_pages;
		case 'page':
		return @$this -> page;
		case 'start':
		return @$this -> start;
		case 'show':
		return @$this -> show;
		case 'rop':
		return @$this -> rop;
		endswitch;
	}
}
?>