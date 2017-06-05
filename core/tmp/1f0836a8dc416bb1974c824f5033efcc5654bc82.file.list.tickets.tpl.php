<?php /* Smarty version Smarty-3.0.6, created on 2016-05-02 21:42:02
         compiled from "E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\list.tickets.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2778157279f7a606f54-40952693%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f0836a8dc416bb1974c824f5033efcc5654bc82' => 
    array (
      0 => 'E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\\list.tickets.tpl',
      1 => 1409648068,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2778157279f7a606f54-40952693',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_cycle')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.cycle.php';
?><?php  $_smarty_tpl->tpl_vars["ticket"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('tickets')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars["ticket"]->key => $_smarty_tpl->tpl_vars["ticket"]->value){
?>
<div class="content_mess" style="overflow: hidden;<?php echo smarty_function_cycle(array('values'=>", background-color: #EFFFE2;"),$_smarty_tpl);?>
">
<?php if (isset($_smarty_tpl->getVariable('ticket',null,true,false)->value['us'])){?>
<div class="wety">
<?php echo $_smarty_tpl->getVariable('ticket')->value['us']->icon();?>
<?php echo $_smarty_tpl->getVariable('ticket')->value['us']->login(1);?>

<br />
</div>
<?php }?>
<div class="lst_h">
<a href="/support/ticket/<?php echo $_smarty_tpl->getVariable('ticket')->value['data']->id;?>
"><?php echo TextUtils::escape($_smarty_tpl->getVariable('ticket')->value['data']->title);?>
</a> (<span class="time_show"><?php echo TimeUtils::show($_smarty_tpl->getVariable('ticket')->value['data']->time);?>
</span>)
<?php if ($_smarty_tpl->getVariable('ticket')->value['data']->opened==1){?>
<span style="color: green;">(открыт)</span>
<?php }else{ ?>
<span style="color: red;">(закрыт)</span>
<?php }?>
<br />
<span style="color: blue;">
<?php if ($_smarty_tpl->getVariable('ticket')->value['data']->type==0){?>
Администратору
<?php }else{ ?>
Консультанту
<?php }?>
</span>
<br />
<?php echo TextUtils::show(TextUtils::cut($_smarty_tpl->getVariable('ticket')->value['data']->msg,300));?>

<br />
</div>
<?php if ($_smarty_tpl->getVariable('ticket')->value['data']->id_user==$_smarty_tpl->getVariable('u')->value->id||$_smarty_tpl->getVariable('ticket')->value['data']->opened==0||adminka::access('tickets_open_ticket')||$_smarty_tpl->getVariable('ticket')->value->opened==1&&adminka::access('tickets_close_ticket')){?>
<hr class="custom">
<div class="mess_mod">
<?php if ($_smarty_tpl->getVariable('ticket')->value['data']->opened==1){?>
<a href="/support/close_ticket/<?php echo $_smarty_tpl->getVariable('ticket')->value['data']->id;?>
">Закрыть</a>
<?php }else{ ?>
<a href="/support/open_ticket/<?php echo $_smarty_tpl->getVariable('ticket')->value['data']->id;?>
">Открыть</a>
<?php }?>
</div>
<?php }?>
</div>
<!-- end of block -->
<?php }} ?>