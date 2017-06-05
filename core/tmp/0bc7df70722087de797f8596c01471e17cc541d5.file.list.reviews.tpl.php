<?php /* Smarty version Smarty-3.0.6, created on 2016-05-02 21:46:05
         compiled from "E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\list.reviews.tpl" */ ?>
<?php /*%%SmartyHeaderCode:230165727a06d6e0464-74049531%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0bc7df70722087de797f8596c01471e17cc541d5' => 
    array (
      0 => 'E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\\list.reviews.tpl',
      1 => 1409648068,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '230165727a06d6e0464-74049531',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_cycle')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.cycle.php';
?><?php  $_smarty_tpl->tpl_vars["rev"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('reviews')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars["rev"]->key => $_smarty_tpl->tpl_vars["rev"]->value){
?>
<?php $_smarty_tpl->tpl_vars["border"] = new Smarty_variable(false, null, null);?>
<div class="content_mess"<?php echo smarty_function_cycle(array('values'=>", style='background-color: #EFFFE2;'"),$_smarty_tpl);?>
>
<div class="left">
<?php echo $_smarty_tpl->getVariable('rev')->value['us']->ava_list();?>

</div>
<div class="lst_h">
<div class="list_us_info">
<?php echo $_smarty_tpl->getVariable('rev')->value['us']->icon();?>
<?php echo $_smarty_tpl->getVariable('rev')->value['us']->login();?>
 <span class="time_show">(<?php echo TimeUtils::show($_smarty_tpl->getVariable('rev')->value['data']->time);?>
)</span><br />
</div>
<?php if (isset($_smarty_tpl->getVariable('rev',null,true,false)->value['good'])){?>
<hr class="custom">
<div style="padding-left: 7px;">
<a href="/shop/good/<?php echo $_smarty_tpl->getVariable('rev')->value['good']->id;?>
"><?php echo TextUtils::escape($_smarty_tpl->getVariable('rev')->value['good']->name);?>
</a>
</div>
<?php }?>
<hr class="custom">
<div class="mess_list">
<div>
<?php if ($_smarty_tpl->getVariable('rev')->value['data']->type=='bad'){?>
<span class="red">Отрицательный</span>
<?php }else{ ?>
<span class="green">Положительный</span>
<?php }?>
<br />
</div>
<?php echo TextUtils::show($_smarty_tpl->getVariable('rev')->value['data']->mess);?>

</div>
<?php if (isset($_smarty_tpl->getVariable('rev',null,true,false)->value['actions'])&&count($_smarty_tpl->getVariable('rev')->value['actions'])){?>
<hr class="custom">
<div class="mess_mod">
<?php  $_smarty_tpl->tpl_vars["action"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('rev')->value['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
<?php }} ?>