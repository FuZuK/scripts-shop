<div class="hidden" id="adminka">
	<div class="title">
		Админка
	</div>
	<div class="modalContent">
		<?
		if (!isset($_SESSION['adminka_enter'])) {
			$sb = new SMBuilder();
			$sb -> addCat('el');
			$sb -> addElm('{type}captcha', '{br}');
			$sb -> addElm('{type}ussec');
			$sb -> addElm('{type}submit', '{name}sfsk', '{value}Продолжить', '{br}');
			$sb -> addVar('{method}POST', '{action}/adminka/');
			$sb -> show('form.tpl');
		} else
			include(DR.'adminka/inc/act.index.php');
		?>
	</div>
</div>