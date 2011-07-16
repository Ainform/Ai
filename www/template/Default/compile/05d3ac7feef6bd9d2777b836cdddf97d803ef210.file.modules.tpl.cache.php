<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 18:17:56
         compiled from "V:/home/buket_debug/www/template/Default/../modules.tpl" */ ?>
<?php /*%%SmartyHeaderCode:225764e1c3b749eb4e7-99236912%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '05d3ac7feef6bd9d2777b836cdddf97d803ef210' => 
    array (
      0 => 'V:/home/buket_debug/www/template/Default/../modules.tpl',
      1 => 1310473073,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '225764e1c3b749eb4e7-99236912',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $sha = sha1("../nojs.tpl" . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template("../nojs.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<?php  $_smarty_tpl->tpl_vars['Module'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Modules')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Module']->key => $_smarty_tpl->tpl_vars['Module']->value){
?>
	<?php $_smarty_tpl->tpl_vars['Data'] = new Smarty_variable($_smarty_tpl->getVariable('Module')->value->getData(), null, null);?>
	<?php if (isset($_smarty_tpl->getVariable('ShowOnlyThisModuleId',null,true,false)->value)){?>
		<?php if ($_smarty_tpl->getVariable('ShowOnlyThisModuleId')->value==$_smarty_tpl->getVariable('Module')->value->moduleId){?>
			<form method="post" action="" name="form<?php echo $_smarty_tpl->getVariable('Module')->value->moduleId;?>
" id="form<?php echo $_smarty_tpl->getVariable('Module')->value->moduleId;?>
" enctype="multipart/form-data">
				<input type="hidden" name="moduleId" value="<?php echo $_smarty_tpl->getVariable('Module')->value->moduleId;?>
"/>
				<div class="module <?php echo $_smarty_tpl->getVariable('Module')->value->cssClass;?>
">
				<?php $sha = sha1($_smarty_tpl->getVariable('Module')->value->getTemplatePath() . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template($_smarty_tpl->getVariable('Module')->value->getTemplatePath(), $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
				</div>
			</form>
		<?php }?>
	<?php }else{ ?>
		<?php if (!isset($_smarty_tpl->getVariable('Module',null,true,false)->value->noform)){?><form method="post" action="" name="form<?php echo $_smarty_tpl->getVariable('Module')->value->moduleId;?>
" id="form<?php echo $_smarty_tpl->getVariable('Module')->value->moduleId;?>
" enctype="multipart/form-data"><?php }?>
			<input type="hidden" name="moduleId" value="<?php echo $_smarty_tpl->getVariable('Module')->value->moduleId;?>
"/>
			<div class="module <?php echo $_smarty_tpl->getVariable('Module')->value->cssClass;?>
">
			<?php $sha = sha1($_smarty_tpl->getVariable('Module')->value->getTemplatePath() . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template($_smarty_tpl->getVariable('Module')->value->getTemplatePath(), $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
			</div>
		<?php if (!isset($_smarty_tpl->getVariable('Module',null,true,false)->value->noform)){?></form><?php }?>
	<?php }?>
<?php }} ?>