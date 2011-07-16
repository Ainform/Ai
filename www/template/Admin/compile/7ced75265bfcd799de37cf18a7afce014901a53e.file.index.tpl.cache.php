<?php /* Smarty version Smarty-3.0.8, created on 2011-07-13 09:52:19
         compiled from "V:/home/buket_debug/www/template/Admin/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4244e1d167319bbe8-67617201%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7ced75265bfcd799de37cf18a7afce014901a53e' => 
    array (
      0 => 'V:/home/buket_debug/www/template/Admin/index.tpl',
      1 => 1310528434,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4244e1d167319bbe8-67617201',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_block_php')) include 'V:\home\buket_debug\www\App_Code\PHP\Smarty\plugins\block.php.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php echo $_smarty_tpl->getVariable('Title')->value;?>
</title><link rel="stylesheet" href="/css/admin/reset.css" type="text/css" media="screen" /><link rel="stylesheet" href="/css/admin/style.css" type="text/css" media="screen" /><link rel="stylesheet" href="/css/admin/invalid.css" type="text/css" media="screen" /><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script><script type="text/javascript" src="/js/simpla.jquery.configuration.js"></script><script type="text/javascript" src="/js/main.js"></script></head><body><div id="body-wrapper"><div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu --><h1 id="sidebar-title"><a href="#">Ainform</a></h1><a href="http://ainform.com"><img id="logo" src="/img/admin/New/logo%20(1).png" alt="Ainform" /></a><div id="profile-links">Привет, <a href="#" title="Edit your profile">Администратор</a><br /><br /><a href="/" title="Вернуться на сайт">Вернуться на сайт</a> | <a href="/admin/SignOut" title="Выйти">Выйти</a></div><?php $sha = sha1("menu.tpl" . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template("menu.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></div></div> <!-- End #sidebar --><div id="main-content"><h2>Возможные действия</h2><p id="page-intro"></p><ul class="shortcut-buttons-set"><?php $sha = sha1("adminmenu.tpl" . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template("adminmenu.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></ul><div class="clear"></div><div class="content-box"><div class="content-box-header"><h3 style="cursor: s-resize; "><?php echo $_smarty_tpl->getVariable('Title')->value;?>
</h3><?php echo $_smarty_tpl->getVariable('BreadCrumbs')->value;?>
<!-- <ul class="content-box-tabs"><li><a href="#tab1" class="default-tab current">Table</a></li><li><a href="#tab2">Forms</a></li></ul>--><div class="clear"></div></div><div class="content-box-content"><div class="tab-content default-tab" id="tab1" style="display: block; "><?php $sha = sha1("../modules.tpl" . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template("../modules.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?></div></div></div><div class="clear"></div><div id="footer"><small>© Copyright 2011 <a href="http://ainform.com">Ainform</a> | Powered by Ai | <a href="#">Top</a><span> | Время генерации: <?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; smarty_block_php(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
echo sprintf("%.5f", microtime(true) - START_TIME);<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_php(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 сек</span></small></div></div></div></body></html>