<?php /* Smarty version Smarty-3.0.8, created on 2011-07-16 15:15:06
         compiled from "V:/home/buket_debug/www/module/Catalog/sectionedit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:259764e21569a9b35b9-00247629%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '70d2a95d47254baa1ade5d2c2bf9c22a09f40207' => 
    array (
      0 => 'V:/home/buket_debug/www/module/Catalog/sectionedit.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '259764e21569a9b35b9-00247629',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_TextBox')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.TextBox.php';
if (!is_callable('smarty_function_Validator')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.Validator.php';
if (!is_callable('smarty_function_FCKEditor')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.FCKEditor.php';
if (!is_callable('smarty_block_ajax_update')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\block.ajax_update.php';
if (!is_callable('smarty_function_OneImageUploader')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.OneImageUploader.php';
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

<table class="admin_table">
    <thead>
        <tr>
            <th style="width:150px;">Название товара</th>
            <th>Описание товара</th>
            <th style="width:100px;">Фотография</th>
            <th colspan="4">&nbsp;</th>
        </tr>
    </thead>
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
        <td width="100"><?php if (isset($_smarty_tpl->tpl_vars['Good']->value['Image'])&&$_smarty_tpl->tpl_vars['Good']->value['Image']!=''){?><img src="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Image'];?>
&width=100&height=100&crop=1"  alt="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" width="100" /><?php }?></td>
        <td align="center" width="30"><input type="image" name="handlerBtnGoodUp:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" id="btnUp" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/arrow_up_16.png" alt="Поднять на 1 позицию" title="Поднять на 1 позицию"/></td>
        <td align="center" width="30"><input type="image" name="handlerBtnGoodDown:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" id="btnDown" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/arrow_down_16.png" alt="Опустить на 1 позицию" title="Опустить на 1 позицию"/></td>
        <td align="center" width="30"><input type="image" name="handlerBtnEdit:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" id="btnEdit" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/op_edit.gif" alt="Изменить" title="Изменить" onclick="editgood('<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
'); return false;" /></td>
        <td align="center" width="30"><input type="image" name="handlerBtnGoodDel:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
img/admin/close_16.png" alt="Удаление" onclick="return confirm('Удалить новость?');" /></td>
    </tr>
    <?php }} else { ?>
    <tr><td align="center" colspan="6">Товаров в данном разделе нет</td></tr>
    <?php } ?>
</table>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ajax_update(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<p align="center">
    <?php if ($_smarty_tpl->getVariable('Data')->value['NewPage']==false){?>
    <input type="submit" name="handlerBtnAddGood" value="Добавить товар" title="Добавить товар" width="90px" class="button" onclick="return newgood();"></input>
    <?php }?>
    <br />
    <br />
    <?php if ($_smarty_tpl->getVariable('Data')->value['ImageFolder']){?>
    <?php echo smarty_function_OneImageUploader(array('id'=>"uplGoods",'thumbWidth'=>"100",'thumbHeight'=>"100",'value'=>$_smarty_tpl->getVariable('Data')->value['Images'],'folder'=>$_smarty_tpl->getVariable('Data')->value['ImageFolder']),$_smarty_tpl);?>

    <?php }?>
    <?php if ($_smarty_tpl->getVariable('Data')->value['FilesFolder']){?>
    <?php }?>
    <br/>
<div class="buttons">
    <input type="submit" name="handlerBtnSave" value="Сохранить" title="Сохранить изменения" width="90px" class="button"></input>
    <input type="submit" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" width="90px" class="button"></input>
</div>
</p>