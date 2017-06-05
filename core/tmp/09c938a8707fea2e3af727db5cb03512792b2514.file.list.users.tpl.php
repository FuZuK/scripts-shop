<?php /* Smarty version Smarty-3.0.6, created on 2016-05-02 21:43:18
         compiled from "E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\list.users.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2059857279fc602e282-64378661%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09c938a8707fea2e3af727db5cb03512792b2514' => 
    array (
      0 => 'E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\\list.users.tpl',
      1 => 1380916740,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2059857279fc602e282-64378661',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_cycle')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.cycle.php';
?><?php  $_smarty_tpl->tpl_vars["us"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('users')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars["us"]->index=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars["us"]->key => $_smarty_tpl->tpl_vars["us"]->value){
 $_smarty_tpl->tpl_vars["us"]->index++;
 $_smarty_tpl->tpl_vars["us"]->first = $_smarty_tpl->tpl_vars["us"]->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["foreach"]['first'] = $_smarty_tpl->tpl_vars["us"]->first;
?>
<?php $_smarty_tpl->tpl_vars["border"] = new Smarty_variable(false, null, null);?>
<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['hr'])&&$_smarty_tpl->getVariable('sets')->value['hr']==true&&$_smarty_tpl->getVariable('smarty')->value['foreach']['foreach']['first']!=true){?><hr><?php }?>
<div class="<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['div'])){?><?php echo $_smarty_tpl->getVariable('sets')->value['div'];?>
<?php }else{ ?>content_list<?php }?>"<?php echo smarty_function_cycle(array('values'=>", style='background-color: #EFFFE2;'"),$_smarty_tpl);?>
>
<div class="left">
<?php echo $_smarty_tpl->getVariable('us')->value['us']->ava_list();?>

</div>
<div class="lst_h">
<div class="list_us_info">
<?php echo $_smarty_tpl->getVariable('us')->value['us']->icon();?>
<?php echo $_smarty_tpl->getVariable('us')->value['us']->login(1);?>
<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['rating'])){?><?php echo $_smarty_tpl->getVariable('us')->value['us']->rating();?>
<?php }?><br />
</div>
<?php if (isset($_smarty_tpl->getVariable('us',null,true,false)->value['info'])){?>
<hr class="custom">
<!-- user "<?php echo $_smarty_tpl->getVariable('us')->value['us']->login;?>
" -->
<div class="mess_list">
<?php echo $_smarty_tpl->getVariable('us')->value['info'];?>

</div>
<?php }?>
<?php if (isset($_smarty_tpl->getVariable('us',null,true,false)->value['actions'])&&count($_smarty_tpl->getVariable('us')->value['actions'])){?>
<hr class="custom">
<div class="mess_mod">
<?php  $_smarty_tpl->tpl_vars["action"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('us')->value['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
<?php }} ?>