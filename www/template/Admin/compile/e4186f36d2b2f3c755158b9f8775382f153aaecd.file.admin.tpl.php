<?php /* Smarty version Smarty-3.0.8, created on 2011-07-17 18:14:31
         compiled from "V:/home/buket_debug/www/module/News/admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:208094e22d227592df3-62901306%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e4186f36d2b2f3c755158b9f8775382f153aaecd' => 
    array (
      0 => 'V:/home/buket_debug/www/module/News/admin.tpl',
      1 => 1310904241,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '208094e22d227592df3-62901306',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_scaffolding')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\function.scaffolding.php';
?><?php echo smarty_function_scaffolding(array('list'=>$_smarty_tpl->getVariable('Data')->value['NewsList']),$_smarty_tpl);?>

<div class="buttons">
	<input type="submit" name="handlerBtnNewNews" value="Добавить" title="Добавить" class="button" />
	<input type="submit" name="handlerBtnCancel" value="Назад" title="Вернуться назад" class="button" />
</div>
