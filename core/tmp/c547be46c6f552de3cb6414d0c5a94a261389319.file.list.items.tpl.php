<?php /* Smarty version Smarty-3.0.6, created on 2016-05-02 21:39:15
         compiled from "E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\list.items.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1339657279ed3c63923-31580313%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c547be46c6f552de3cb6414d0c5a94a261389319' => 
    array (
      0 => 'E:/Programming/WEB/OpenServer/domains/shop/core/templates/main\\list.items.tpl',
      1 => 1416918270,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1339657279ed3c63923-31580313',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_cycle')) include 'E:\Programming\WEB\OpenServer\domains\shop\core\libs\Smarty\plugins\function.cycle.php';
?><?php  $_smarty_tpl->tpl_vars["list_item"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list_items')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars["list_item"]->index=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars["list_item"]->key => $_smarty_tpl->tpl_vars["list_item"]->value){
 $_smarty_tpl->tpl_vars["list_item"]->index++;
 $_smarty_tpl->tpl_vars["list_item"]->first = $_smarty_tpl->tpl_vars["list_item"]->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["foreach"]['first'] = $_smarty_tpl->tpl_vars["list_item"]->first;
?>
<?php $_smarty_tpl->tpl_vars["border"] = new Smarty_variable(false, null, null);?>
<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['img'])){?><?php if (!isset($_smarty_tpl->tpl_vars['list_item']) || !is_array($_smarty_tpl->tpl_vars['list_item']->value)) $_smarty_tpl->createLocalArrayVariable('list_item', null, null);
$_smarty_tpl->tpl_vars['list_item']->value['img'] = $_smarty_tpl->getVariable('sets')->value['img'];?><?php }?>
<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['hr'])&&$_smarty_tpl->getVariable('sets')->value['hr']==true&&$_smarty_tpl->getVariable('smarty')->value['foreach']['foreach']['first']==false){?><hr><?php }?>
<div class="<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['div'])){?><?php echo $_smarty_tpl->getVariable('sets')->value['div'];?>
<?php }else{ ?>content_list<?php }?>"<?php echo smarty_function_cycle(array('values'=>", style='background-color: #EFFFE2;'"),$_smarty_tpl);?>
>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['img'])){?>
<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['img_left'])&&$_smarty_tpl->getVariable('sets')->value['img_left']==true){?><div class="left"><?php }?>
<?php echo $_smarty_tpl->getVariable('list_item')->value['img'];?>

<?php if (isset($_smarty_tpl->getVariable('sets',null,true,false)->value['img_left'])&&$_smarty_tpl->getVariable('sets')->value['img_left']==true){?></div><?php }?>
<?php }?>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['name'])){?>
<div class="list_us_info">
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['link'])){?><a href="<?php echo $_smarty_tpl->getVariable('list_item')->value['link'];?>
"><?php }?><?php echo $_smarty_tpl->getVariable('list_item')->value['name'];?>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['link'])){?></a><?php }?>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['counter'])){?>
<?php $_template = new Smarty_Internal_Template("list.counter.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?><?php }?>
</div>
<?php }?>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['content'])){?>
<div class="lst_h">
<?php echo $_smarty_tpl->getVariable('list_item')->value['content'];?>

</div>
<?php }?>
<?php if (isset($_smarty_tpl->getVariable('list_item',null,true,false)->value['actions'])&&count($_smarty_tpl->getVariable('list_item')->value['actions'])){?>
<hr class="custom">
<div class="mess_mod">
<?php  $_smarty_tpl->tpl_vars["action"] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('list_item')->value['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
<div class="clear"></div>
</div>
<!-- end of block -->
<?php }} ?>