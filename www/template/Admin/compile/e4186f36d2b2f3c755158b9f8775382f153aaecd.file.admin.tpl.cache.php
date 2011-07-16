<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 17:15:04
         compiled from "V:/home/buket_debug/www/module/News/admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:223814e1c2cb8d598f7-31090011%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e4186f36d2b2f3c755158b9f8775382f153aaecd' => 
    array (
      0 => 'V:/home/buket_debug/www/module/News/admin.tpl',
      1 => 1310469302,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '223814e1c2cb8d598f7-31090011',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_date_format')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\modifier.date_format.php';
?><script language="JavaScript" type="text/javascript">
function editNews(newsId)
{
	window.location.href = "<?php echo $_smarty_tpl->getVariable('PageAddress')->value;?>
?NewsId=" + newsId;
	return false;
}
function newNews()
{
	window.location.href = "<?php echo $_smarty_tpl->getVariable('PageAddress')->value;?>
?newNews=1";
	return false;
}
</script>
<table class="admin_table">
    <thead>
<tr>
<th width="50">Дата</th>
<th>Заголовок</th>
<th colspan="2">Опции</th>
</tr>
</thead>
<?php  $_smarty_tpl->tpl_vars['LeaderItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['NewsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['LeaderItem']->key => $_smarty_tpl->tpl_vars['LeaderItem']->value){
?>
<tr>
	<td width="50"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['LeaderItem']->value['date'],"%d.%m.%Y");?>
</td>
	<td><?php echo $_smarty_tpl->tpl_vars['LeaderItem']->value['title'];?>
</td>
	<td align="center" width="30">
		<a href="../../admin/modules/<?php echo $_smarty_tpl->getVariable('Data')->value['ModuleId'];?>
.php?NewsId=<?php echo $_smarty_tpl->tpl_vars['LeaderItem']->value['id'];?>
"><img src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/op_edit.gif"  /></a>
	</td>
	<td align="center" width="30"><input type="image" name="handlerBtnDel:<?php echo $_smarty_tpl->tpl_vars['LeaderItem']->value['id'];?>
" src="/img/admin/close_16.png" alt="Удаление" height="16" width="16" onclick="return confirm('Удалить?');" /></td></tr>
<?php }} ?>
</table>
<?php echo $_smarty_tpl->getVariable('Data')->value['Pager'];?>

<p align="center">
	<input type="submit" name="handlerBtnNewNews" value="Добавить" title="Добавить" width="90px" class="button" onclick="return newNews();"></input>
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" width="90px" class="button"></input>
</p>