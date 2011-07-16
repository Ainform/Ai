<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 22:33:52
         compiled from "V:/home/buket_debug/www/module/Catalog/good.tpl" */ ?>
<?php /*%%SmartyHeaderCode:107144e1c777031dfd9-88416072%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5f1992e9081f9582d0e489b2f0f5f1a253576a9' => 
    array (
      0 => 'V:/home/buket_debug/www/module/Catalog/good.tpl',
      1 => 1310488427,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '107144e1c777031dfd9-88416072',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<link rel="stylesheet" href="/js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<?php echo $_smarty_tpl->getVariable('BreadCrumbs')->value;?>

<br />
<div class="good_page_left">
	<?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['Good']['Images'])){?>
	<?php  $_smarty_tpl->tpl_vars['Image'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['Good']['Images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Image']->key => $_smarty_tpl->tpl_vars['Image']->value){
?>
    <div class="good_image_inside">
        <?php if (isset($_smarty_tpl->tpl_vars['Image']->value)){?>
        <a href="<?php echo $_smarty_tpl->tpl_vars['Image']->value['Path'];?>
" rel="goodimages" class="group" title='<?php echo (($tmp = @$_smarty_tpl->tpl_vars['Image']->value['Title'])===null||$tmp==='' ? $_smarty_tpl->getVariable('Data')->value['Good']['Title'] : $tmp);?>
'>
            <img src="<?php echo $_smarty_tpl->tpl_vars['Image']->value['Path'];?>
&width=250&height=350&prop=1"  alt="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['Image']->value['Title'])===null||$tmp==='' ? $_smarty_tpl->getVariable('Data')->value['Good']['Title'] : $tmp);?>
" border=none/>
        </a>
        <?php }?>
    </div>
    <?php }} ?>
    <?php }else{ ?>
    <div class="good_image">
        <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
    </div>
    <?php }?>
</div>
<div>
    <h1 class="goodtitle"><?php echo $_smarty_tpl->getVariable('Data')->value['Good']['Title'];?>
</h1>
    <p>Цена: <span style="padding-left: 0px;" class="good_price"><?php echo $_smarty_tpl->getVariable('Data')->value['Good']['Price'];?>
</span> руб.</p>
    <p>Описание:</p>
    <p><?php echo $_smarty_tpl->getVariable('Data')->value['Good']['Abstract'];?>
</p>
    <?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['AddedToCart'][$_smarty_tpl->getVariable('Data',null,true,false)->value['Good']['GoodId']])){?>
    <?php echo $_smarty_tpl->getVariable('Data')->value['AddedToCart'][$_smarty_tpl->getVariable('Data')->value['Good']['GoodId']];?>

    <?php }else{ ?>
    <p class="good_buy">
        <input type="submit" name="handlerBtnAdd" class="button" value="Заказать" />
    </p>
    <?php }?>
    <div class="nextprev">
        <?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['prevGood'])){?>
        <a class="prev" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->getVariable('Data')->value['prevGood']['GoodId']),$_smarty_tpl);?>
" >
            « Предыдущий
        </a>
        <?php }?>
        <?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['nextGood'])){?>
        <a class="next" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->getVariable('Data')->value['nextGood']['GoodId']),$_smarty_tpl);?>
" >
            Следующий »
        </a>
        <?php }?>
        <div style="clear:both;"></div>
    </div>

</div>

<?php if ($_smarty_tpl->getVariable('Data')->value['GoodsList']){?>
<div class="goods_list">
    <?php  $_smarty_tpl->tpl_vars['Gooditem'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['GoodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Gooditem']->key => $_smarty_tpl->tpl_vars['Gooditem']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['Gooditem']->key;
?>
    <div class="good_div">
        <div class="good_image">
            <?php if ($_smarty_tpl->tpl_vars['Gooditem']->value['Image']!=''){?>
            <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Gooditem']->value['GoodId']),$_smarty_tpl);?>
" title="<?php echo $_smarty_tpl->tpl_vars['Gooditem']->value['Title'];?>
">
                <img src="<?php echo $_smarty_tpl->tpl_vars['Gooditem']->value['Image'];?>
&width=111&height=111&crop=1"  alt="<?php echo $_smarty_tpl->tpl_vars['Gooditem']->value['Title'];?>
" style="border:none;"/>
            </a>
            <?php }else{ ?>
            <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Gooditem']->value['GoodId']),$_smarty_tpl);?>
">
                <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
            </a>
            <?php }?>
        </div>
        <p class="good_title"><?php echo $_smarty_tpl->tpl_vars['Gooditem']->value['Title'];?>
</p>
        <?php if ($_smarty_tpl->tpl_vars['Gooditem']->value['Price']==0){?>
        <p class="good_price">цена не указана</p>
        <?php }else{ ?>
        <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price"><?php echo $_smarty_tpl->tpl_vars['Gooditem']->value['Price'];?>
</span>&nbsp;руб.</p>
        <?php }?>
        <?php if (empty($_smarty_tpl->getVariable('Data',null,true,false)->value['AddedToCart'][$_smarty_tpl->tpl_vars['Gooditem']->value['GoodId']])){?>
        <p class="good_buy"><input type="submit" name="handlerBtnAdd:<?php echo $_smarty_tpl->tpl_vars['Gooditem']->value['GoodId'];?>
" class="button" value="Заказать"/>
            <?php }else{ ?>
        <p class="hello"><?php echo $_smarty_tpl->getVariable('Data')->value['AddedToCart'][$_smarty_tpl->tpl_vars['Gooditem']->value['GoodId']];?>

            <?php }?>
    </div>
    <?php }} ?>
</div>
<?php }?>
<script>
	$(document).ready(function() {

		$("a.group").fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	600,
			'speedOut'		:	200,
			'overlayShow'	:	false,
			'type':'image'
		});

	});
</script>
<?php $sha = sha1("../media.tpl" . $_smarty_tpl->cache_id . $_smarty_tpl->compile_id);
if (isset($_smarty_tpl->smarty->template_objects[$sha])) {
$_template = $_smarty_tpl->smarty->template_objects[$sha]; $_template->caching = 9999; $_template->cache_lifetime =  null;
} else {
$_template = new Smarty_Internal_Template("../media.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
}
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>