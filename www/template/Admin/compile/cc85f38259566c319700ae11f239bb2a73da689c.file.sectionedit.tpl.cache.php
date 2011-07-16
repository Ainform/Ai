<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 17:50:02
         compiled from "V:/home/buket_debug/www/module/Gallery/sectionedit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:140414e1c34ea7c5ff5-05043292%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cc85f38259566c319700ae11f239bb2a73da689c' => 
    array (
      0 => 'V:/home/buket_debug/www/module/Gallery/sectionedit.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '140414e1c34ea7c5ff5-05043292',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_OneImageUploader')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.OneImageUploader.php';
if (!is_callable('smarty_function_TextBox')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.TextBox.php';
if (!is_callable('smarty_function_Validator')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.Validator.php';
if (!is_callable('smarty_function_FCKEditor')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.FCKEditor.php';
if (!is_callable('smarty_block_ajax_update')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\block.ajax_update.php';
?><script language="JavaScript" type="text/javascript">
    function editgood(goodId)
    {
        window.location.href = "<?php echo $_smarty_tpl->getVariable('PageAddress')->value;?>
?sectionId=<?php echo $_smarty_tpl->getVariable('Data')->value['SectionId'];?>
&goodId=" + goodId;
        return false;
    }
    function newgood()
    {
        window.location.href = "<?php echo $_smarty_tpl->getVariable('PageAddress')->value;?>
?sectionId=<?php echo $_smarty_tpl->getVariable('Data')->value['SectionId'];?>
&goodNew=1";
        return false;
    }
</script>
<?php if ($_smarty_tpl->getVariable('Data')->value['ImageFolder']){?>
<?php echo smarty_function_OneImageUploader(array('id'=>"uplGoods",'thumbWidth'=>"100",'thumbHeight'=>"100",'title'=>"Изображение раздела",'value'=>$_smarty_tpl->getVariable('Data')->value['Images'],'folder'=>$_smarty_tpl->getVariable('Data')->value['ImageFolder']),$_smarty_tpl);?>

<?php }?>
<br />
<table class="admin_table">
    <tr>
        <td style="width:150px;">Заголовок</td>
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtName",'maxlength'=>"100",'class'=>"text-input small-input"),$_smarty_tpl);?>

		<?php echo smarty_function_Validator(array('for'=>"txtName",'rule'=>"NotNull",'message'=>"Введите заголовок раздела"),$_smarty_tpl);?>

    </tr>
    <tr>
        <td>Описание раздела</td>
        <td>
		<?php echo smarty_function_FCKEditor(array('id'=>"fckText",'height'=>"150",'value'=>$_smarty_tpl->getVariable('Data')->value['Description'],'simple'=>"True"),$_smarty_tpl);?>

    </tr>
</table>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('ajax_update', array()); $_block_repeat=true; smarty_block_ajax_update(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

<table class="admin_table" border="1">
    <tr><th width="150">Название</th><th>Описание</th><th width="100">Фотография</th><th colspan="4">&nbsp;</th></tr>
    <?php  $_smarty_tpl->tpl_vars['Good'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['Goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Good']->key => $_smarty_tpl->tpl_vars['Good']->value){
?>
    <tr>
        <td><?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['Good']->value['Description'];?>
</td>
        <td width="100">
            <?php if (isset($_smarty_tpl->tpl_vars['Good']->value['Image'])&&$_smarty_tpl->tpl_vars['Good']->value['Image']!=''){?>
            <img src="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Image'];?>
&width=100&height=100&crop=1"  alt="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" width="100" />
            <?php }?>
        </td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnGoodUp:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" id="btnUp" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/arrow_up_16.png" alt="Поднять на 1 позицию" title="Поднять на 1 позицию" height="16" width="16"  /></td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnGoodDown:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" id="btnDown" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/arrow_down_16.png" alt="Опустить на 1 позицию" title="Опустить на 1 позицию" height="16" width="16"  /></td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnEdit:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" id="btnEdit" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/op_edit.gif" alt="Изменить" title="Изменить" height="16" width="16" onclick="editgood('<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
'); return false;" /></td>
        <td align="center" width="30">
            <input type="image" name="handlerBtnGoodDel:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/close_16.png" alt="Удаление" height="16" width="16" onclick="return confirm('Удалить?');" /></td>
    </tr>
    <?php }} else { ?>
    <tr><td align="center" colspan="6">Фотографий в данном разделе нет</td></tr>
    <?php } ?>
</table>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ajax_update(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<?php if ($_smarty_tpl->getVariable('Data')->value['NewPage']==false){?>
<div style="text-align:center">
<br />
<input type="submit" name="handlerBtnAddGood" value="Добавить фото" title="Добавить фото" class="button" onclick="return newgood();" />
</div>
<?php }?>

<br />
<div class="buttons">
<input type="submit" name="handlerBtnSave" value="Сохранить" title="Сохранить изменения"  class="button" />
<input type="submit" name="handlerBtnCancel" value="Назад" title="Назад"  class="button" />
</div>