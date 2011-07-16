<?php /* Smarty version Smarty-3.0.8, created on 2011-07-14 11:58:21
         compiled from "V:/home/buket_debug/www/template/Default/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:208454e1e857d6b0881-01396982%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99cdd4dd185f5f1ed8633f9aded20a683b209e5d' => 
    array (
      0 => 'V:/home/buket_debug/www/template/Default/index.tpl',
      1 => 1310488582,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '208454e1e857d6b0881-01396982',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title><?php echo $_smarty_tpl->getVariable('Title')->value;?>
</title><?php echo $_smarty_tpl->getVariable('MetaTags')->value;?>
<?php  $_smarty_tpl->tpl_vars['CSSFile'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('CSSFiles')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['CSSFile']->key => $_smarty_tpl->tpl_vars['CSSFile']->value){
?><link href="<?php echo $_smarty_tpl->tpl_vars['CSSFile']->value;?>
" type="text/css" rel="stylesheet" /><?php }} ?><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link href="/css/style.css" type="text/css" rel="stylesheet" /><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script><script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
/js/buket.js"></script><script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
/js/swfobject.js"></script><script type="text/javascript">var flashvars = {name1: "hello",name2: "world",name3: "foobar"};var params = {menu: "false",wmode: "transparent"};var attributes = {id: "myDynamicContent",name: "myDynamicContent",style:"left:220px;top:-250px;position:absolute;"};var attributes2 = {id: "myDynamicContent",name: "myDynamicContent",style:"left:660px;top:-250px;position:absolute;"};swfobject.embedSWF("/css/images/firefly.swf", "flash", "140", "140", "9.0.0","expressInstall.swf", flashvars, params, attributes);swfobject.embedSWF("/css/images/firefly.swf", "flash2", "140", "140", "9.0.0","expressInstall.swf", flashvars, params, attributes2);</script></head><body><div id="container"><div id="header"><div id="menu_top_slider_outside"><div id="menu_top_slider"><div id="menu_slider_1" class="menu_slider"><?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuSubItems1')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['parent_alias'];?>
/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Alias'];?>
/" class="menu_sub_item"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</a><?php }} ?></div><!-- <div id="menu_slider_2" class="menu_slider"><?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuSubItems2')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['parent_alias'];?>
/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Alias'];?>
/" class="menu_sub_item"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</a><?php }} ?></div>--><div id="menu_slider_3" class="menu_slider"><?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuSubItems3')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['parent_alias'];?>
/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Alias'];?>
/" class="menu_sub_item"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</a><?php }} ?></div><div id="menu_slider_4" class="menu_slider"><?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuSubItems4')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['parent_alias'];?>
/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Alias'];?>
/" class="menu_sub_item"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</a><?php }} ?></div></div></div><div id="menu_top_outside"><div id="menu_top"><div id="flash" style="position:absolute;margin-left:200px;margin-top:-200px;"></div><div id="flash2" style="position:absolute;margin-left:200px;margin-top:-200px;"></div><span id="menu_1_link" class="menu_link">О компании</span><span id="menu_2_link" class="menu_link"><a href="/internet-magazin/">Интернет-магазин</a></span><span id="menu_3_link" class="menu_link">Галерея работ</span><span id="menu_4_link" class="menu_link">Услуги</span><!--<?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuItems')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><span id="menu_<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['menuitems']['index']+1;?>
_link" class="menu_link"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</span><?php }} ?>--></div></div></div><div id="content"><div id="about"></div><a href="/internet-magazin/" id="gallery"></a><div id="proposal"></div><div id="contacts"></div><div style="clear:both"></div><div id="main_left_menu"><p><a href="/uslugi/svadebnaja_floristika/">Свадебная флористика</a></p><p><a href="/uslugi/korporativnym_klientam/">Корпоративным клиентам</a></p><p><a href="/uslugi/oformlenie_interjerov/">Оформление интерьеров</a></p><p><a href="/uslugi/master-klassy/">Мастер-классы</a></p><p><a href="/uslugi/ekskljuzivnye_napravlenija/">Эксклюзивные направления</a></p></div><div id="center_place"><?php if (count($_smarty_tpl->getVariable('Modules')->value)!=0){?><?php $_template = new Smarty_Internal_Template("../modules.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><?php }else{ ?><h2>Мы дарим вам Букет хорошего настроения!</h2><?php }?></div><div id="mashinka"><p><a href="/uslugi/dostavka_tsvetov/">Доставка букетов</a></p></div><div style="clear:both"></div></div><div id="clear"></div><div id="rasporka"></div></div><div id="footer"><div id="footer_inside"><div id="copyright">&copy; Букет 2010</div><div id="counters"><!-- Yandex.Metrika counter --><div style="display:none;"><script type="text/javascript">(function(w, c) {(w[c] = w[c] || []).push(function() {try {w.yaCounter5960620 = new Ya.Metrika(5960620);yaCounter5960620.clickmap(true);yaCounter5960620.trackLinks(true);} catch(e) { }});})(window, 'yandex_metrika_callbacks');</script></div><script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script><noscript><div><img src="//mc.yandex.ru/watch/5960620" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter --></div><div id="address">Мустая Карима, 41. тел. 273-55-66.</div><div id="welcome">Мы дарим вам Букет хорошего настроения!</div><div id="ai"><a href="http://ainform.com">Разработка сайта</a></div></div></div></body></html>