<?php /* Smarty version Smarty-3.0.6, created on 2016-05-02 21:39:16
         compiled from "E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\list.counter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:409357279ed40f6421-75369230%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6b27bc97cb759c79e22030262a095e7f15d90469' => 
    array (
      0 => 'E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\\list.counter.tpl',
      1 => 1395608101,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '409357279ed40f6421-75369230',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['counter_class'])){?>
 <span class="<?php echo $_smarty_tpl->getVariable('sets')->value['counter_class'];?>
">
<?php echo $_smarty_tpl->getVariable('list_item')->value['counter'];?>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['counter_new'])&&$_smarty_tpl->getVariable('list_item')->value['counter_new']!=0){?>/+<?php echo $_smarty_tpl->getVariable('list_item')->value['counter_new'];?>
<?php }?>
</span>
<?php }else{ ?>
<span>
(<?php echo $_smarty_tpl->getVariable('list_item')->value['counter'];?>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['counter_new'])&&$_smarty_tpl->getVariable('list_item')->value['counter_new']!=0){?>/+<?php echo $_smarty_tpl->getVariable('list_item')->value['counter_new'];?>
<?php }?>)
</span>
<?php }?>