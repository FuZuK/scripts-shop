<?php /* Smarty version Smarty-3.0.6, created on 2016-05-02 21:39:16
         compiled from "E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\list.comments.tpl" */ ?>
<?php /*%%SmartyHeaderCode:536257279ed42f1bc4-42208916%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f47f64aef0bb9bd96395fd59d9a22566f9375d4' => 
    array (
      0 => 'E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\\list.comments.tpl',
      1 => 1380916740,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '536257279ed42f1bc4-42208916',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_cycle')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.cycle.php';
?>
<?php  $_smarty_tpl->tpl_vars["post"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('posts')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars["post"]->key => $_smarty_tpl->tpl_vars["post"]->value){
?>
<?php $_smarty_tpl->tpl_vars["border"] = new Smarty_variable(false, null, null);?>
<div class="content_mess"<?php echo smarty_function_cycle(array('values'=>", style='background-color: #EFFFE2;'"),$_smarty_tpl);?>
>
<?php if (isset($_smarty_tpl->getVariable('post',null,true,false)->value['show_ava'])&&$_smarty_tpl->getVariable('post')->value['show_ava']==true||!isset($_smarty_tpl->getVariable('post',null,true,false)->value['show_ava'])){?>
<div class="left">
<?php echo $_smarty_tpl->getVariable('post')->value['us']->ava_list();?>

</div>
<?php }?>
<div class="lst_h">
<div class="list_us_info">
<?php echo $_smarty_tpl->getVariable('post')->value['us']->icon();?>
<?php echo $_smarty_tpl->getVariable('post')->value['us']->login(1);?>
 <span class="time_show">(<?php echo $_smarty_tpl->getVariable('post')->value['time_form'];?>
)</span><br />
</div>
<hr class="custom">
<div class="mess_list">
<?php if (isset($_smarty_tpl->getVariable('post',null,true,false)->value['data']->hidden)&&$_smarty_tpl->getVariable('post')->value['data']->hidden){?><span class="red">Скрыл<?php echo $_smarty_tpl->getVariable('post')->value['hus']->pw(array('a',''));?>
 <?php echo $_smarty_tpl->getVariable('post')->value['hus']->login;?>
</span><br /><?php }?>
<?php if (isset($_smarty_tpl->getVariable('post',null,true,false)->value['reply_us'])&&$_smarty_tpl->getVariable('post')->value['reply_us']->id){?><b><?php echo $_smarty_tpl->getVariable('post')->value['reply_us']->login;?>
</b>, <?php }?><?php echo $_smarty_tpl->getVariable('post')->value['msg_form'];?>

</div>
<?php if (isset($_smarty_tpl->getVariable('post',null,true,false)->value['actions'])&&count($_smarty_tpl->getVariable('post')->value['actions'])){?>
<hr class="custom">
<div class="mess_mod">
<?php  $_smarty_tpl->tpl_vars["action"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('post')->value['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars["action"]->key => $_smarty_tpl->tpl_vars["action"]->value){
?>
<?php if ($_smarty_tpl->getVariable('border')->value==true){?> - <?php }?><a href="<?php echo $_smarty_tpl->getVariable('action')->value['link'];?>
"><?php echo $_smarty_tpl->getVariable('action')->value['name'];?>
</a>
<?php $_smarty_tpl->tpl_vars["border"] = new Smarty_variable(true, null, null);?>
<?php }} ?>
</div>
<?php }?>
</div>
<div class="clear"></div>
</div>
<!-- end of block -->
<?php }} else { ?>
<div class="content" style="margin: -4px; background: transparent;">
<div class="error_outline">
<div class="error_inline">
Комментарии отсутствуют
</div>
</div>
</div>
<!-- end of block -->
<?php } ?>