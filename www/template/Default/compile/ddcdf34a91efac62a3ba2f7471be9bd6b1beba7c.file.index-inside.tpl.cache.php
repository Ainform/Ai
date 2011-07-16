<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 22:37:05
         compiled from "V:/home/buket_debug/www/template/Default/index-inside.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5944e1c7831518606-26038221%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ddcdf34a91efac62a3ba2f7471be9bd6b1beba7c' => 
    array (
      0 => 'V:/home/buket_debug/www/template/Default/index-inside.tpl',
      1 => 1310488621,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5944e1c7831518606-26038221',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php echo $_smarty_tpl->getVariable('Title')->value;?>
</title><?php echo $_smarty_tpl->getVariable('MetaTags')->value;?>
<?php  $_smarty_tpl->tpl_vars['CSSFile'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('CSSFiles')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['CSSFile']->key => $_smarty_tpl->tpl_vars['CSSFile']->value){
?><link href="<?php echo $_smarty_tpl->tpl_vars['CSSFile']->value;?>
" type="text/css" rel="stylesheet" /><?php }} ?><link href="/css/style_inside.css" type="text/css" rel="stylesheet" /><link rel="stylesheet" type="text/css" href="/css/shadowbox.css"><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script><script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
/js/buket.js"></script><script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('Address')->value;?>
/js/shadowbox/shadowbox.js"></script><script>/*$(document).ready(function(){$(".menu_left_link").toggle(function(){$(".id"+$(this).attr("id")).show();},function(){$(".id"+$(this).attr("id")).hide();});});*/</script></head><body><div id="container"><div id="header"><div id="logo_outside"><div id="logo"><div onclick="window.location='/'"></div></div></div><div id="top_cart_outside"><div id="top_cart"><p class="top_cart_link"><a href="/cart/">Ваша корзина</a></p><p class="top_cart_count">В корзине <?php echo $_smarty_tpl->getVariable('Cart_count')->value;?>
</p></div></div><div id="menu_top_slider_outside"><div id="menu_top_slider" class="inside"><div id="menu_slider_2" class="menu_slider"><?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
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
</a><?php }} ?></div>--><div id="menu_slider_4" class="menu_slider"><?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuSubItems3')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['parent_alias'];?>
/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Alias'];?>
/" class="menu_sub_item"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</a><?php }} ?></div><div id="menu_slider_5" class="menu_slider"><?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuSubItems4')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['parent_alias'];?>
/<?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Alias'];?>
/" class="menu_sub_item"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</a><?php }} ?></div></div></div><div id="menu_top_outside"><div id="menu_top" class="inside"><span id="menu_1_link" class="menu_link"><a href="/">Главная</a></span><span id="menu_2_link" class="menu_link">О компании</span><span id="menu_3_link" class="menu_link"><a href="/internet-magazin/">Интернет-магазин</a></span><span id="menu_4_link" class="menu_link">Галерея работ</span><span id="menu_5_link" class="menu_link">Услуги</span><!--<?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuItems')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menuitems']['index']++;
?><span id="menu_<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['menuitems']['index']+1;?>
_link" class="menu_link"><?php echo $_smarty_tpl->tpl_vars['MenuItem']->value['Name'];?>
</span><?php }} ?>--></div></div></div><div id="content"><div id="menu_left_inside"><?php if (isset($_smarty_tpl->getVariable('ParentTitle',null,true,false)->value)){?><h1><?php echo $_smarty_tpl->getVariable('ParentTitle')->value;?>
</h1><?php }?><?php if (!isset($_smarty_tpl->getVariable('shop',null,true,false)->value)){?><?php  $_smarty_tpl->tpl_vars['MenuLeftItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuLeftItems1')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuLeftItem']->key => $_smarty_tpl->tpl_vars['MenuLeftItem']->value){
?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem']->value['Selected']==true){?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['SectionId'];?>
" class="menu_left_link selected id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['depth']*10;?>
px;"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Name'];?>
</a><?php }else{ ?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['SectionId'];?>
" class="menu_left_link id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['depth']*10;?>
px;<?php if ($_smarty_tpl->tpl_vars['MenuLeftItem']->value['depth']>1){?>display:none;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Name'];?>
</a><?php }?><?php }} ?><?php }else{ ?><div class="catalog_search"><form action="/search/" method="get"><input type="text" name="q" value="<?php if (isset($_GET['q'])){?><?php echo $_GET['q'];?>
<?php }?>"><input type="submit" value="Найти" /></form></div><?php  $_smarty_tpl->tpl_vars['MenuLeftItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuLeftItems1')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuLeftItem']->key => $_smarty_tpl->tpl_vars['MenuLeftItem']->value){
?><?php if ($_smarty_tpl->tpl_vars['MenuLeftItem']->value['ParentId']==-1){?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem']->value['hasChild'])&&$_smarty_tpl->tpl_vars['MenuLeftItem']->value['hasChild']==true){?><div class="plus" id="menu<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['SectionId'];?>
">+</div><?php }?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem']->value['Selected']==true){?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['SectionId'];?>
" class="menu_left_link selected id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['depth']*10;?>
px;"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Name'];?>
</a><?php }else{ ?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['SectionId'];?>
" class="menu_left_link id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['depth']*10;?>
px;<?php if (false&&$_smarty_tpl->tpl_vars['MenuLeftItem']->value['depth']>1){?>display:none;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['Name'];?>
</a><?php }?><div class="sub<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem']->value['SectionId'];?>
 submenu <?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem']->value['Selected']==true){?>active<?php }?>"><?php  $_smarty_tpl->tpl_vars['MenuLeftItem1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuLeftItems1')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuLeftItem1']->key => $_smarty_tpl->tpl_vars['MenuLeftItem1']->value){
?><?php if ($_smarty_tpl->tpl_vars['MenuLeftItem1']->value['ParentId']==$_smarty_tpl->tpl_vars['MenuLeftItem']->value['SectionId']){?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem1']->value['hasChild'])&&$_smarty_tpl->tpl_vars['MenuLeftItem1']->value['hasChild']==true){?><div class="plus" id="menu<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['SectionId'];?>
">+</div><?php }?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Selected']==true){?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['SectionId'];?>
" class="menu_left_link selected id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['depth']*10;?>
px;"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Name'];?>
</a><?php }else{ ?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['SectionId'];?>
" class="menu_left_link id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['depth']*10;?>
px;<?php if (false&&$_smarty_tpl->tpl_vars['MenuLeftItem1']->value['depth']>1){?>display:none;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Name'];?>
</a><?php }?><div class="sub<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem1']->value['SectionId'];?>
 submenu <?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem1']->value['Selected']==true){?>active<?php }?>"><?php  $_smarty_tpl->tpl_vars['MenuLeftItem2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuLeftItems1')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuLeftItem2']->key => $_smarty_tpl->tpl_vars['MenuLeftItem2']->value){
?><?php if ($_smarty_tpl->tpl_vars['MenuLeftItem2']->value['ParentId']==$_smarty_tpl->tpl_vars['MenuLeftItem1']->value['SectionId']){?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem2']->value['hasChild'])&&$_smarty_tpl->tpl_vars['MenuLeftItem2']->value['hasChild']==true){?><div class="plus" id="menu<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['SectionId'];?>
">+</div><?php }?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Selected']==true){?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['SectionId'];?>
" class="menu_left_link selected id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['depth']*10;?>
px;"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Name'];?>
</a><?php }else{ ?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['SectionId'];?>
" class="menu_left_link id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['depth']*10;?>
px;<?php if (false&&$_smarty_tpl->tpl_vars['MenuLeftItem2']->value['depth']>1){?>display:none;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Name'];?>
</a><?php }?><div class="sub<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem2']->value['SectionId'];?>
 submenu <?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem2']->value['Selected']==true){?>active<?php }?>"><?php  $_smarty_tpl->tpl_vars['MenuLeftItem3'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuLeftItems1')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuLeftItem3']->key => $_smarty_tpl->tpl_vars['MenuLeftItem3']->value){
?><?php if ($_smarty_tpl->tpl_vars['MenuLeftItem3']->value['ParentId']==$_smarty_tpl->tpl_vars['MenuLeftItem2']->value['SectionId']){?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem3']->value['hasChild'])&&$_smarty_tpl->tpl_vars['MenuLeftItem3']->value['hasChild']==true){?><div class="plus" id="menu<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['SectionId'];?>
">+</div><?php }?><?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Selected']==true){?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['SectionId'];?>
" class="menu_left_link selected id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['depth']*10;?>
px;"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Name'];?>
</a><?php }else{ ?><a href="/<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Url'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['SectionId'];?>
" class="menu_left_link id<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['ParentId'];?>
" style="padding-left:<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['depth']*10;?>
px;<?php if (false&&$_smarty_tpl->tpl_vars['MenuLeftItem3']->value['depth']>1){?>display:none;<?php }?>"><?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Name'];?>
</a><?php }?><div class="sub<?php echo $_smarty_tpl->tpl_vars['MenuLeftItem3']->value['SectionId'];?>
 submenu <?php if (isset($_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Selected'])&&$_smarty_tpl->tpl_vars['MenuLeftItem3']->value['Selected']==true){?>active<?php }?>"></div><?php }?><?php }} ?></div><?php }?><?php }} ?></div><?php }?><?php }} ?></div><?php }?><?php }} ?><a class="oplata_i_dostavka menu_left_link <?php if (isset($_smarty_tpl->getVariable('oplata_i_dostavka',null,true,false)->value)){?>selected<?php }?>" href="/internet-magazin/oplata_i_dostavka/">Оплата и доставка</a><?php }?></div><div id="inside_content"><?php if (count($_smarty_tpl->getVariable('Modules')->value)!=0){?><?php $sha = sha1("../modules.tpl" . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template("../modules.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><?php }else{ ?><h2>Раздел в разработке</h2><?php }?></div><div style="clear:both"></div></div><div id="clear"></div><div id="rasporka"></div></div><div id="footer"><div id="footer_inside"><div id="copyright">&copy; Букет 2010</div><div id="counters"><!-- Yandex.Metrika counter --><div style="display:none;"><script type="text/javascript">(function(w, c) {(w[c] = w[c] || []).push(function() {try {w.yaCounter5960620 = new Ya.Metrika(5960620);yaCounter5960620.clickmap(true);yaCounter5960620.trackLinks(true);} catch(e) { }});})(window, 'yandex_metrika_callbacks');</script></div><script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script><noscript><div><img src="//mc.yandex.ru/watch/5960620" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter --></div><div id="address">Мустая Карима, 41. тел. 273-55-66.</div><div id="welcome">Мы дарим вам Букет хорошего настроения!</div><div id="ai"><a href="http://ainform.com">Разработка сайта</a></div></div></div></body></html>