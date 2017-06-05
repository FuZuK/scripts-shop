<?php
	@session_name('SHOP_SESS');
	@session_start();
	$sess = htmlspecialchars(session_id());
	if (!preg_match('#[A-z0-9]{32}#i', $sess))$sess = md5(rand(09009, 999999));
?>