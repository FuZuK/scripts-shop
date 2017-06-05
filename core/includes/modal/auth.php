<div class="hidden" id="auth">
	<div class="title">
		Авторизация
	</div>
	<div class="modalContent">
		<?
		$elms = array();
		$elms[] = array('type' => 'title', 'value' => 'E-mail:', 'br' => true);
		$elms[] = array('type' => 'text', 'name' => 'email', 'value' => '', 'br' => true);
		$elms[] = array('type' => 'title', 'value' => 'Пароль:', 'br' => true);
		$elms[] = array('type' => 'password', 'name' => 'pass', 'value' => null);
		$elms[] = array('type' => 'checkbox', 'name' => 'in_cookies', 'value' => '1', 'text' => 'Запомнить меня', 'labels' => true);
		$elms[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Войти', 'br' => true);
		new SMX(array('el' => $elms, 'method' => 'POST', 'action' => '/auth/'), 'form.tpl');
		?>
	</div>
</div>