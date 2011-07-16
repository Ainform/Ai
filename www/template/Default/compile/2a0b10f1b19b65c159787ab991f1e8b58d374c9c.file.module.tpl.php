<?php /* Smarty version Smarty-3.0.8, created on 2011-07-16 20:17:18
         compiled from "V:/home/buket_debug/www/module/TextPage/module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:63774e219d6e45beb0-98247378%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a0b10f1b19b65c159787ab991f1e8b58d374c9c' => 
    array (
      0 => 'V:/home/buket_debug/www/module/TextPage/module.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '63774e219d6e45beb0-98247378',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<h1 class="textpage_title"><?php echo $_smarty_tpl->getVariable('Title')->value;?>
</h1>
<?php if (!empty($_smarty_tpl->getVariable('Data',null,true,false)->value['Text'])){?>
<?php echo $_smarty_tpl->getVariable('Data')->value['Text'];?>

<?php }else{ ?>
<div>Раздел в разработке</div>
<?php }?>