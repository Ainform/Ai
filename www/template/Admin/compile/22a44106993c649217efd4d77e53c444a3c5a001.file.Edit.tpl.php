<?php /* Smarty version Smarty-3.0.8, created on 2011-07-17 18:36:51
         compiled from "V:/home/buket_debug/www/module/News/Edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:77004e22d763d40711-59419098%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22a44106993c649217efd4d77e53c444a3c5a001' => 
    array (
      0 => 'V:/home/buket_debug/www/module/News/Edit.tpl',
      1 => 1310750427,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '77004e22d763d40711-59419098',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_TextBox')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.TextBox.php';
if (!is_callable('smarty_function_Validator')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.Validator.php';
if (!is_callable('smarty_function_FCKEditor')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.FCKEditor.php';
if (!is_callable('smarty_function_ImageUploader')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.ImageUploader.php';
if (!is_callable('smarty_function_AnalyticFilesUploader')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.AnalyticFilesUploader.php';
?><script language="JavaScript" type="text/javascript">
    function Check() {
        return true;
    }
</script>
<table class="admin_table">
    <tr>
        <td style="width:140px">Заголовок</td>
        <td>
        <?php echo smarty_function_TextBox(array('id'=>"txtTitle",'value'=>$_smarty_tpl->getVariable('Data')->value['title'],'class'=>"text-input small-input"),$_smarty_tpl);?>

			<?php echo smarty_function_Validator(array('for'=>"txtTitle",'rule'=>"NotNull",'message'=>"Введите заголовок"),$_smarty_tpl);?>

        </td>
    </tr>
    <tr>
        <td>Дата</td>
        <td>
        <?php echo smarty_function_TextBox(array('id'=>"txtDate",'value'=>$_smarty_tpl->getVariable('Data')->value['date'],'class'=>"text-input small-input"),$_smarty_tpl);?>

			<?php echo smarty_function_Validator(array('for'=>"txtDate",'rule'=>"NotNull",'message'=>"Введите дату"),$_smarty_tpl);?>

        </td>
    </tr>
</table>
<!--<table class="admin_table">
	<tr>
		<td align="right" width="140">Показывать на главной</td>
		<td>
			<select name="selectOnFront">
                <option value="0" <?php if ($_smarty_tpl->getVariable('Data')->value['selectOnFront']==0){?>selected="true"<?php }?>>Не выводить на главную</option>
                <option value="1" <?php if ($_smarty_tpl->getVariable('Data')->value['selectOnFront']==1){?>selected="true"<?php }?>>Высший приоритет</option>
                <option value="2" <?php if ($_smarty_tpl->getVariable('Data')->value['selectOnFront']==2){?>selected="true"<?php }?>>Средний приоритет</option>
                <option value="3" <?php if ($_smarty_tpl->getVariable('Data')->value['selectOnFront']==3){?>selected="true"<?php }?>>Низкий приоритет</option>
			</select>
		</td>
	</tr>
</table>-->
<table class="admin_table">
    <thead>
    <tr>
        <th>Анонс (краткое описание)</th>
    </tr>
    </thead>
    <tr>
        <td>
        <?php echo smarty_function_FCKEditor(array('id'=>"fckAnons",'height'=>"200",'value'=>$_smarty_tpl->getVariable('Data')->value['anons']),$_smarty_tpl);?>

        </td>
    </tr>
    <tr>
        <th>Текст</th>
    </tr>
    <tr>
        <td>
        <?php echo smarty_function_FCKEditor(array('id'=>"fckText",'height'=>"400",'value'=>$_smarty_tpl->getVariable('Data')->value['text']),$_smarty_tpl);?>

        </td>
    </tr>
</table>
<?php echo smarty_function_ImageUploader(array('id'=>"uplimg",'thumbWidth'=>"100",'thumbHeight'=>"100",'value'=>$_smarty_tpl->getVariable('Data')->value['Images'],'folder'=>$_smarty_tpl->getVariable('Data')->value['ImagesFolder']),$_smarty_tpl);?>

<?php echo smarty_function_AnalyticFilesUploader(array('id'=>"uplfile",'value'=>$_smarty_tpl->getVariable('Data')->value['Files'],'folder'=>$_smarty_tpl->getVariable('Data')->value['FilesFolder']),$_smarty_tpl);?>

<p align="center">
    <INPUT type="submit" class="button" value="Сохранить" name="handlerBtnNewsSave" title="Сохранить изменения"
           onclick="return Check();"/>
    <INPUT type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения"/>
</p>
