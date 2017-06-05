<div class="boxWrapper">
	<div class="boxPanel">
		<div class="boxTitle">Меню</div>
		<?
		echo Doc::showLink('/news/', Doc::showImage('/images/news.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' Новости');
		echo Doc::showLink('/forum/', Doc::showImage('/images/forum.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' Форум');
		echo Doc::showLink('/chatik/', Doc::showImage('/images/chat.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' Мини-чат');
		echo Doc::showLink('/wiki/', Doc::showImage('/images/wiki.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' Справка');
		echo Doc::showLink('/users/all', Doc::showImage('/images/prof.png', array('height' => ICON_WH, 'width' => ICON_WH, 'class' => ICON_CLASS)).' Пользователи');
		?>
	</div>
</div>