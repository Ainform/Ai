<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 17:26:24
         compiled from "V:/home/buket_debug/www/admin/module/Backup/module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:129264e1c2f60635638-36814720%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7d54a5ad0de4707063bf419b6c5caf9d0ebbb657' => 
    array (
      0 => 'V:/home/buket_debug/www/admin/module/Backup/module.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '129264e1c2f60635638-36814720',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (count($_smarty_tpl->getVariable('Data')->value['Backups'])==0){?>
<p align="center"><b>Резервные копии отсутствуют</b></p>
<?php }else{ ?>
<table class="admin_table">
    <thead>
<tr>
	<th>Номер</th>
	<th>Дата создания</th>
	<th colspan="2">Востановить</th>
	<th>Востановление</th>
	<th>Удаление</th>
</tr>
</thead>
<?php  $_smarty_tpl->tpl_vars['Backup'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['Backups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Backup']->key => $_smarty_tpl->tpl_vars['Backup']->value){
?>
<tr>
	<td width="50"><?php echo $_smarty_tpl->tpl_vars['Backup']->value['Id'];?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['Backup']->value['Date'];?>
</td>
	<td width="30" align="center"><input class="button" type="submit" name="handlerBtnRestoreSql:<?php echo $_smarty_tpl->tpl_vars['Backup']->value['Folder'];?>
" value="БД" title="Востановление базы данных" onclick="return confirm('Восстановить БД?');" /></td>
	<td width="50" align="center"><input class="button" type="submit" name="handlerBtnRestoreFiles:<?php echo $_smarty_tpl->tpl_vars['Backup']->value['Folder'];?>
" value="Файлы" title="Востановление файлов" onclick="return confirm('Восстановить файлы?');" /></td>
	<td width="70" align="center"><input class="button" type="submit" name="handlerBtnRestore:<?php echo $_smarty_tpl->tpl_vars['Backup']->value['Folder'];?>
" value="Восстановить" title="Востановление резервной копии" onclick="return confirm('Восстановить резервную копию?');" /></td>
	<td width="70" align="center"><input type="image" name="handlerBtnDelete:<?php echo $_smarty_tpl->tpl_vars['Backup']->value['Folder'];?>
" src="/img/admin/close_16.png" alt="Удаление резервной копии" height="16" width="16" onclick="return confirm('Удалить резервную копию?');" /></td>
</tr>
<?php }} ?>
</table>
<?php }?>
<p align="center">
	<input class="button" type="submit" name="handlerBtnCreate" value="Создать резервную копию" title="Создать резервную копию информации на сайте" />
</p>
<?php echo $_smarty_tpl->getVariable('Data')->value['litMessage'];?>
