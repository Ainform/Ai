<?php /* Smarty version Smarty-3.0.8, created on 2011-07-16 15:15:21
         compiled from "V:/home/buket_debug/www/module/Catalog/goodedit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:297774e2156a93eebf3-87070156%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f4940a2c0b46dba85494fe929f585903c6a1c2e' => 
    array (
      0 => 'V:/home/buket_debug/www/module/Catalog/goodedit.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '297774e2156a93eebf3-87070156',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_TextBox')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.TextBox.php';
if (!is_callable('smarty_function_Validator')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.Validator.php';
if (!is_callable('smarty_function_DropDownList')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.DropDownList.php';
if (!is_callable('smarty_function_FCKEditor')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.FCKEditor.php';
if (!is_callable('smarty_function_ImageUploader')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.ImageUploader.php';
?><table class="admin_table" border="1">
    <tr>
        <td style="width:150px;">Название</td>
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtName",'maxlength'=>"100",'class'=>"text-input small-input"),$_smarty_tpl);?>

		<?php echo smarty_function_Validator(array('for'=>"txtName",'rule'=>"NotNull",'message'=>"Введите название товара"),$_smarty_tpl);?>

    </tr>
    <tr>
        <td>Цена</td>
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtPrice",'maxlength'=>"100",'class'=>"text-input small-input"),$_smarty_tpl);?>

    </tr>
    <!--<tr>
        <td>Артикул</td>
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtGoodCode",'maxlength'=>"100",'class'=>"text-input small-input"),$_smarty_tpl);?>

		<?php echo smarty_function_Validator(array('for'=>"txtGoodCode",'rule'=>"NotNull",'message'=>"Введите артикул товара"),$_smarty_tpl);?>

    </tr>
    <tr>
        <td>Код
        <td>
		<?php echo smarty_function_TextBox(array('id'=>"txtGoodTrueCode",'maxlength'=>"100",'class'=>"text-input small-input"),$_smarty_tpl);?>

    </tr>-->
    <tr>
        <td>
            Раздел товара</td>
        <td>
		<?php echo smarty_function_DropDownList(array('id'=>"ddrSection",'values'=>$_smarty_tpl->getVariable('Data')->value['Sections']),$_smarty_tpl);?>

    </tr>
    <!--<tr>
        <td>Производитель товара</td>
        <td>
		<?php echo smarty_function_DropDownList(array('id'=>"ddrManufacturer",'values'=>$_smarty_tpl->getVariable('Data')->value['Manufacturers']),$_smarty_tpl);?>

    </tr>-->
</table>
<!--<table class="admin_table">
    <tr>
        <th align="center">Характеристики</th>
    </tr>
    <tr>
        <td>
            <textarea id="Properties" name="Properties" rows="10" cols="50" style="width:100%"><?php echo $_smarty_tpl->getVariable('Data')->value['Properties'];?>
</textarea>
            <p style="font-size:8pt;color:$999;margin-bottom:5px;">Каждая характеристика должна быть на <strong>отдельной строке</strong>, название характеристики и её значение должно разделять <strong>двоеточие</strong>.</p>
        </td>
    </tr>
</table>-->
<table class="admin_table">
    <thead>
    <tr>
        <th>Краткое описание</th>
    </tr>
    </thead>
    <tr>
        <td>
			<?php echo smarty_function_FCKEditor(array('id'=>"fckDescription",'height'=>"100",'simple'=>"True",'value'=>$_smarty_tpl->getVariable('Data')->value['Good']['Description']),$_smarty_tpl);?>

        </td>
    </tr>
</table>
<table class="admin_table">
    <thead>
    <tr>
        <th>Описание товара</th>
    </tr>
    </thead>
    <tr>
        <td>
            <input type="hidden" name="txtCode" value="" />
            <input type="hidden" name="onMain" value="" />
			<?php echo smarty_function_FCKEditor(array('id'=>"fckAbstract",'height'=>"300",'value'=>$_smarty_tpl->getVariable('Data')->value['Good']['Abstract']),$_smarty_tpl);?>

        </td>
    </tr>
</table>

<br />

<?php echo smarty_function_ImageUploader(array('id'=>"uplGoods",'thumbWidth'=>"100",'thumbHeight'=>"100",'value'=>$_smarty_tpl->getVariable('Data')->value['Images'],'folder'=>$_smarty_tpl->getVariable('Data')->value['ImageFolder']),$_smarty_tpl);?>


<br />

<p align="center">
	<?php if ($_smarty_tpl->getVariable('Data')->value['NewPage']==true){?>
    <input type="submit" name="handlerBtnGoodSave" value="Добавить" title="Добавить страницу" class="button" />
	<?php }else{ ?>
    <input type="submit" name="handlerBtnGoodSave" value="Сохранить" title="Сохранить изменения" class="button" />
	<?php }?>
    <input type="submit" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" class="button" />
</p>