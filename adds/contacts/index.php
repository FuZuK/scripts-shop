<?
include('../../core/st.php');
$title = 'Наши контакты';
include(HEAD);
echo "<div class='content'>\n";
echo Doc::showImage('/images/mail.png', array('width' => ICON_WH, 'height' => ICON_WH, 'class' => ICON_CLASS))." pukhliyroman@yandex.ru<br />\n";
echo Doc::showImage('http://web.icq.com/whitepages/online?icq=686579&img=27', array('width' => ICON_WH, 'height' => ICON_WH, 'class' => ICON_CLASS))." 686579<br />\n";
echo Doc::showImage('/images/webmoney_icon.png', array('width' => ICON_WH, 'height' => ICON_WH, 'class' => ICON_CLASS)).' '.Doc::showLink('http://passport.webmoney.ru/asp/certview.asp?wmid=664936584080', '664936584080')."<br />\n";
echo "</div>\n";
Doc::back("На главную", "/?");
include(FOOT);
?>