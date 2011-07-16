<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 16:59:54
         compiled from "V:/home/buket_debug/www/admin/module/ErrorsList/module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:97824e1c292a00b881-88286304%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2f6dd8cea16f507dc0f82bc8257bbf3fb17f66ee' => 
    array (
      0 => 'V:/home/buket_debug/www/admin/module/ErrorsList/module.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '97824e1c292a00b881-88286304',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!isset($_smarty_tpl->getVariable('Data',null,true,false)->value['ErrorsList'])){?>
<div class="notification information png_bg"><div>Сообщения об ошибках отсутствуют</div></div>
<?php }else{ ?>
<div class="buttons">
    <input class="button" type="submit" name="handlerBtnClearClick" value="Очистить" title="Очистить отчет об ошибках" onclick="return confirm(\'Очистить отчет об ошибках?\');" />
</div>
<br>
<?php echo $_smarty_tpl->getVariable('Data')->value['ErrorsList'];?>

<br>
<div class="buttons">
    <input class="button" type="submit" name="handlerBtnClearClick" value="Очистить" title="Очистить отчет об ошибках" onclick="return confirm(\'Очистить отчет об ошибках?\');" />
</div>
<?php }?>