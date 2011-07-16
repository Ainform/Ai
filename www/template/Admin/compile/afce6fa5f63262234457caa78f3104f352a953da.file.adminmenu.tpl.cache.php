<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 17:05:25
         compiled from "V:/home/buket_debug/www/template/Admin/adminmenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:57954e1c2a75e2ed66-55925004%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'afce6fa5f63262234457caa78f3104f352a953da' => 
    array (
      0 => 'V:/home/buket_debug/www/template/Admin/adminmenu.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '57954e1c2a75e2ed66-55925004',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php  $_smarty_tpl->tpl_vars['AdminMenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('AdminMenu')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['AdminMenuItem']->key => $_smarty_tpl->tpl_vars['AdminMenuItem']->value){
?>
<li><a class="shortcut-button" href="<?php echo $_smarty_tpl->tpl_vars['AdminMenuItem']->value['url'];?>
"><span>
                                <img src="<?php echo $_smarty_tpl->tpl_vars['AdminMenuItem']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['AdminMenuItem']->value['title'];?>
"/>
                                <br /><?php echo $_smarty_tpl->tpl_vars['AdminMenuItem']->value['title'];?>

                            </span>
                        </a>
                    </li>
<?php }} ?>