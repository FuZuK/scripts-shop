<?
# Генерация случайных символов
$use_symbols = "123456790abcdefghijhkmntwpuvxyz";
$use_symbols_len = strlen($use_symbols);
$passgen_ject = '';
for($i=0; $i<10; $i++)$passgen_ject .= $use_symbols{mt_rand(0,$use_symbols_len-1)};
$passgen = $passgen_ject;
?>