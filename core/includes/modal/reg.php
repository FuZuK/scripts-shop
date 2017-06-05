<div class="hidden" id="reg">
	<div class="title">
		Регистрация
	</div>
	<div class="modalContent">
		<?
		$elms = array();
		$elms[] = array('type' => 'title', 'value' => 'Желаемый логин:', 'br' => true);
		$elms[] = array('type' => 'text', 'name' => 'login', 'value' => '', 'br' => true);
		$elms[] = array('type' => 'title', 'value' => 'E-mail:', 'br' => true);
		$elms[] = array('type' => 'text', 'name' => 'email', 'value' => '', 'br' => true);
		$elms[] = array('type' => 'title', 'value' => 'Пол:', 'br' => true);
		$elms[] = array('type' => 'select', 'name' => 'sex', 'options' => array('1' => 'Мужской', 0 => 'Женский'), 'selected' => 1, 'br' => true);
		$elms[] = array('type' => 'captcha', 'br' => true);
		$elms[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Далее', 'br' => true);
		new SMX(array('el' => $elms, 'method' => 'POST', 'action' => '/reg/'), 'form.tpl');
		?>
	</div>
</div>