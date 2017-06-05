<?php /* Smarty version Smarty-3.0.6, created on 2016-05-02 21:42:38
         compiled from "E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1105357279f9e94cf44-35791807%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8342a29e14db046f78d0f8b2c74aadeab3f3474' => 
    array (
      0 => 'E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\\form.tpl',
      1 => 1416920747,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1105357279f9e94cf44-35791807',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_html_radios')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.html_radios.php';
if (!is_callable('smarty_function_html_options')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.html_options.php';
if (!is_callable('smarty_function_html_select_date')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.html_select_date.php';
?><form method="<?php echo (($tmp = @$_smarty_tpl->getVariable('method')->value)===null||$tmp==='' ? "post" : $tmp);?>
" action="<?php echo (($tmp = @$_smarty_tpl->getVariable('action')->value)===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('files',null,true,false)->value)){?> enctype="multipart/form-data"<?php }?><?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['class'])&&$_smarty_tpl->getVariable('form_sets')->value['class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['class'];?>
"<?php }?>>
<?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['sect']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['name'] = 'sect';
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('el')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['sect']['total']);
?>
<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['type'])){?>
<?php if ($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='title'){?>
<span<?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['title_class'])&&$_smarty_tpl->getVariable('form_sets')->value['title_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['title_class'];?>
"<?php }?>><?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['value'];?>
</span>
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='radio'){?>
<?php echo smarty_function_html_radios(array('name'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'],'options'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['options'],'checked'=>(($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['checked'])===null||$tmp==='' ? null : $tmp),'separator'=>(($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['separator'])===null||$tmp==='' ? "<br />" : $tmp),'labels'=>(($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['labels'])===null||$tmp==='' ? true : $tmp)),$_smarty_tpl);?>

<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='checkbox'){?>
<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['labels'])){?><label for="<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?><?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
<?php }else{ ?>id_<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'];?>
<?php }?>"><?php }?><input type="checkbox" name="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'];?>
" id="<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?><?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
<?php }else{ ?>id_<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'];?>
<?php }?>" value="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['value'];?>
"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['checked'])&&$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['checked']==true){?> checked="checked"<?php }?> /> <?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['text'];?>
<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['labels'])){?></label><?php }?>
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='select'){?>
<select name="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['select_class'])&&$_smarty_tpl->getVariable('form_sets')->value['select_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['select_class'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?>>
<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['options'],'selected'=>(($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['selected'])===null||$tmp==='' ? null : $tmp)),$_smarty_tpl);?>

</select>
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='text'){?>
<input type="text"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['size'])){?> size="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['size'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['disabled'])&&$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['disabled']==true){?> disabled="disabled"<?php }?> name="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'])===null||$tmp==='' ? null : $tmp);?>
" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['value'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['maxlength'])){?> maxlength="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['maxlength'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['text_class'])&&$_smarty_tpl->getVariable('form_sets')->value['text_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['text_class'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?> />
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='hidden'){?>
<input type="hidden" name="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'])===null||$tmp==='' ? null : $tmp);?>
" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['value'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?> />
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='password'){?>
<input type="password"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['size'])){?> size="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['size'];?>
"<?php }?> name="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'])===null||$tmp==='' ? null : $tmp);?>
" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['value'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['maxlength'])){?> maxlength="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['maxlength'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['text_class'])&&$_smarty_tpl->getVariable('form_sets')->value['text_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['text_class'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?> />
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='textarea'){?>
<textarea<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['disabled'])&&$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['disabled']==true){?> disabled="disabled"<?php }?> name="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['text_class'])&&$_smarty_tpl->getVariable('form_sets')->value['text_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['text_class'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['cols'])){?> cols="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['cols'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['rows'])){?> rows="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['rows'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('fastSend',null,true,false)->value)){?> onkeypress="if(event.keyCode==10||(event.ctrlKey && event.keyCode==13))fastSendButton.click();"<?php }?>><?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['value'])===null||$tmp==='' ? null : $tmp);?>
</textarea>
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='submit'){?>
<input type="submit" name="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?> value="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['value'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['submit_class'])&&$_smarty_tpl->getVariable('form_sets')->value['submit_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['submit_class'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('fastSend',null,true,false)->value)){?> id="fastSendButton"<?php }?> />
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='file'){?>
<input type="file" name="<?php echo (($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['name'])===null||$tmp==='' ? null : $tmp);?>
"<?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['text_class'])&&$_smarty_tpl->getVariable('form_sets')->value['text_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['text_class'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['accept'])){?>accept="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['accept'];?>
"<?php }?><?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?> />
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='captcha'){?>
<span<?php if (isset($_smarty_tpl->getVariable('form_sets',null,true,false)->value['title_class'])&&$_smarty_tpl->getVariable('form_sets')->value['title_class']){?> class="<?php echo $_smarty_tpl->getVariable('form_sets')->value['title_class'];?>
"<?php }?>>Код с картинки:</span><br />
<img src="<?php echo Captcha::getCaptchaImageSource();?>
"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['idImg'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['idImg'];?>
"<?php }?> alt=""><br />
<input type="text" name="captcha"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['idInput'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['idInput'];?>
"<?php }?> value="" class="<?php echo (($tmp = @$_smarty_tpl->getVariable('form_sets')->value['text_class'])===null||$tmp==='' ? null : $tmp);?>
">
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='select_date'){?>
<?php echo smarty_function_html_select_date(array('start_year'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['start_year'],'end_year'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['end_year'],'prefix'=>(($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['prefix'])===null||$tmp==='' ? null : $tmp),'set_day'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['selected_day'],'set_month'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['selected_month'],'set_year'=>$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['selected_year'],'field_order'=>(($tmp = @$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['order'])===null||$tmp==='' ? "DMY" : $tmp),'class'=>$_smarty_tpl->getVariable('form_sets')->value['select_class']),$_smarty_tpl);?>

<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='ussec'){?>
<input type="hidden" name="ussec" value="<?php if (isset($_smarty_tpl->getVariable('u',null,true,false)->value)){?><?php echo $_smarty_tpl->getVariable('u')->value->getSecCode();?>
<?php }?>"<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['id'])){?> id="<?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['id'];?>
"<?php }?> />
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='hp_smiles'){?>
<a href="/adds/smiles" class="hp_bb">Смайлы</a>
<?php }elseif($_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['type']=='hp_tags'){?>
<a href="/adds/tags" class="hp_bb">Теги</a>
<?php }?>
<?php }?>
<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['br'])){?><br />
<?php }?>
<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['alert'])){?>
<span class="alert<?php if (isset($_smarty_tpl->getVariable('el',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['sect']['index']]['warning'])&&$_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['warning']==true){?> warning<?php }?>"><?php echo $_smarty_tpl->getVariable('el')->value[$_smarty_tpl->getVariable('smarty')->value['section']['sect']['index']]['alert'];?>
<br /></span>
<?php }?>
<?php endfor; endif; ?>
</form>
<!-- end of block -->
