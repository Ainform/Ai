<?php /* Smarty version Smarty-3.0.8, created on 2011-07-15 21:23:38
         compiled from "V:/home/buket_debug/www/module/Cart/admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:113054e205b7ad64e44-37762299%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '749755b3b6842a36d7ca0c6dba4b999010a38e73' => 
    array (
      0 => 'V:/home/buket_debug/www/module/Cart/admin.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '113054e205b7ad64e44-37762299',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script language="JavaScript" type="text/javascript">
    function editArchive(orderId)
    {
        window.location.href = "<?php echo $_smarty_tpl->getVariable('PageAddress')->value;?>
?OrderId=" + orderId;
        return false;
    }
</script>
<table class="admin_table">
    <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Дата доставки</th>
            <th>Оплачен</th>
            <th colspan="2">Опции</th>
        </tr>
    </thead>
    <?php  $_smarty_tpl->tpl_vars['Item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['OrderList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Item']->key => $_smarty_tpl->tpl_vars['Item']->value){
?>
    <tr>
        <td><?php echo $_smarty_tpl->tpl_vars['Item']->value['id'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['Item']->value['date'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['Item']->value['recipient_fio'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['Item']->value['recipient_phone'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['Item']->value['address_date'];?>
</td>
        <td><?php if ($_smarty_tpl->tpl_vars['Item']->value['paid']!=0){?><span class="paid">Оплачен</span><?php }else{ ?>Не оплачен<?php }?></td>
        <td align="center" width="30"><input type="image" name="handlerBtnEdit:<?php echo $_smarty_tpl->tpl_vars['Item']->value['id'];?>
" id="btnEdit" src="/img/admin/op_edit.gif" alt="Изменить" title="Изменить" height="16" width="16" onclick="editArchive('<?php echo $_smarty_tpl->tpl_vars['Item']->value['id'];?>
'); return false;" /></td>
        <td align="center" width="30"><input type="image" name="handlerBtnDel:<?php echo $_smarty_tpl->tpl_vars['Item']->value['id'];?>
" src="/img/admin/close_16.png" alt="Удаление" height="16" width="16" onclick="return confirm('Удалить?');" /></td></tr>
    <?php }} ?>
</table>

<?php echo $_smarty_tpl->getVariable('Data')->value['Pager'];?>

<div class="buttons">
    <label for="email">Адрес для отправки заказов</label>
    <input type="text" class="text-input small-input" value="<?php echo $_smarty_tpl->getVariable('Data')->value['email'];?>
" id="email" name="email">

    <!--Курс доллара <input size=4 type="text" value="<?php echo $_smarty_tpl->getVariable('Data')->value['dollar'];?>
" name="dollar">
    Курс евро    <input size=4 type="text" value="<?php echo $_smarty_tpl->getVariable('Data')->value['euro'];?>
" name="euro">-->
    <input class="button" type="submit" name="Save" value="сохранить изменения">
</div>
<!--<p align="center">
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" width="90px" class="button"></input>
</p>-->
