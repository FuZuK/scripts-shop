<?
$q = $db -> q("SELECT * FROM `advt` WHERE `time_to` > '".time()."' ORDER BY rand() ASC LIMIT 5");
if ($q -> rowCount()):
?>
<div class="boxWrapper">
	<div class="boxPanel">
		<div class="boxTitle">Реклама</div>
		<?
		while ($post = $q -> fetch()) {
			echo Doc::showLink($post -> link, Doc::showImage($post -> icon ? $post -> icon : '/images/chat.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' '.TextUtils::DBFilter($post -> name));
		}
		?>
	</div>
</div>
<?endif?>