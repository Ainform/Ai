<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 17:05:26
         compiled from "V:/home/buket_debug/www/module/TextPage/admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:279934e1c2a7601f377-43083256%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9d29fe97ec986a88f55fabdf3fcdeb74b5790725' => 
    array (
      0 => 'V:/home/buket_debug/www/module/TextPage/admin.tpl',
      1 => 1310468584,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '279934e1c2a7601f377-43083256',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_FCKEditor')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.FCKEditor.php';
?><?php echo smarty_function_FCKEditor(array('id'=>"fckText",'height'=>"400",'value'=>$_smarty_tpl->getVariable('Data')->value['Page']['Text']),$_smarty_tpl);?>

<p align="center">
	<input type="submit" class="button" value="Сохранить" name="handlerBtnSave" title="Сохранить" />&nbsp;&nbsp;&nbsp;
	<input type="submit" class="button" name="handlerBtnCancel" value="Отменить" title="Отменить" />
</p>
