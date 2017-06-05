<?
$title = 'Мои деньги';
include(HEAD);
?>
<div class="content">
	У Вас <? echo $u -> money?> <span class="wmr_blue">WMR</span><br />
</div>
<?
if (isset($_POST['buy']) && isset($_POST['money'])) {
	?>
	<hr>
	<?
	$db -> q("INSERT INTO `webmoney_deals` (`ok`, `payee_purse`, `payment_amount`) VALUES (?, ?, ?)", array(0, $set -> sys_wmr, ceil($_POST['money'])));
	$wmd_id = $db -> lastInsertId();
	?>
	<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="POST" class="content">
		<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="<? echo base64_encode("Пополнение счета")?>">
		<input type="hidden" name="LMI_PAYEE_PURSE" value="<? echo $set -> sys_wmr?>">
		<input type="hidden" name="LMI_PAYMENT_NO" value="<? echo $wmd_id?>">
		<input type="hidden" name="pay_type" value="user">
		<input type="hidden" name="user_id" value="<? echo $u -> id?>">
		<? echo ussec::input()?>
		<input type="hidden" name="LMI_PAYMENT_AMOUNT" class="main_inp rad_tlr rad_blr" value="<?=ceil($_POST['money'])?>">
		<span class="form_q">Сума к оплате: <?=ceil($_POST['money'])?> <span class="wmr_blue">WMR</span></span><br />
		<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Перейти к оплате"><br />
	</form>
	<?
} else {
	?>
	<hr>
	<form action="" method="POST" class="content">
		<span class="form_q">Пополнить на:</span><br />
		<input type="text" name="money" class="main_inp rad_tlr rad_blr" value="10"> WMR<br />
		<input type="submit" name="buy" class="main_sub rad_tlr rad_blr" value="Далее"><br />
	</form>
	<?
}
doc::back("Назад", "/cab");
include(FOOT);