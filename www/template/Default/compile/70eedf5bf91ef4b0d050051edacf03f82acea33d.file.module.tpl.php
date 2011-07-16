<?php /* Smarty version Smarty-3.0.8, created on 2011-07-16 20:03:46
         compiled from "V:/home/buket_debug/www/module/Catalog/module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:135064e219a42d1ea06-28106916%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '70eedf5bf91ef4b0d050051edacf03f82acea33d' => 
    array (
      0 => 'V:/home/buket_debug/www/module/Catalog/module.tpl',
      1 => 1310825023,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '135064e219a42d1ea06-28106916',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php echo $_smarty_tpl->getVariable('BreadCrumbs')->value;?>

<?php  $_smarty_tpl->tpl_vars['Section'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['SectionList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Section']->key => $_smarty_tpl->tpl_vars['Section']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['Section']->key;
?>
<div class="section_div">
    <div class="section_image">
        <?php if ($_smarty_tpl->tpl_vars['Section']->value['Image']!=''){?>
         <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['sectionLink'][0][0]->GetSectionLink(array('id'=>$_smarty_tpl->tpl_vars['Section']->value['SectionId']),$_smarty_tpl);?>
"> <img src="<?php echo $_smarty_tpl->tpl_vars['Section']->value['Image'];?>
?width=111&height=111&crop=1" alt="" style="border:none;"/></a>
        <?php }else{ ?>
         <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['sectionLink'][0][0]->GetSectionLink(array('id'=>$_smarty_tpl->tpl_vars['Section']->value['SectionId']),$_smarty_tpl);?>
"> <img src="/css/images/no_foto.png" alt="" style="border:none;" /></a>
         <?php }?>
    </div>
    <div>
        <a class="section_title" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['sectionLink'][0][0]->GetSectionLink(array('id'=>$_smarty_tpl->tpl_vars['Section']->value['SectionId']),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['Section']->value['Title'];?>
</a>
    </div>
    <div style="clear:both;height:10px;"></div>
</div>
<?php }} ?>

<?php if (!empty($_smarty_tpl->getVariable('Data',null,true,false)->value['Description'])){?>
<div style="padding-top: 15px" class="goods_description">
		<?php echo $_smarty_tpl->getVariable('Data')->value['Description'];?>

</div>
<?php }?>
<?php if ($_smarty_tpl->getVariable('Data')->value['GoodsList']){?>
<?php  $_smarty_tpl->tpl_vars['Good'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['GoodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Good']->key => $_smarty_tpl->tpl_vars['Good']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['Good']->key;
?>
<div class="good_div">
    <div class="good_image">
        <?php if ($_smarty_tpl->tpl_vars['Good']->value['Image']!=''){?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
" title='<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
'>
            <img src="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Image'];?>
&width=111&height=111&crop=1"  alt="<?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
" style="border:none;"/>
        </a>
        <?php }else{ ?>
        <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['goodLink'][0][0]->GetGoodLink(array('id'=>$_smarty_tpl->tpl_vars['Good']->value['GoodId']),$_smarty_tpl);?>
">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
        </a>
        <?php }?>
    </div>
    <p class="good_title"><?php echo $_smarty_tpl->tpl_vars['Good']->value['Title'];?>
</p>
    <?php if ($_smarty_tpl->tpl_vars['Good']->value['Price']==0){?>
    <p class="good_price">цена не указана</p>
    <?php }else{ ?>
    <p class="good_price">от&nbsp;<span style="padding-left: 0px;" class="good_price"><?php echo $_smarty_tpl->tpl_vars['Good']->value['Price'];?>
</span>&nbsp;руб.</p>
    <?php }?>

        <?php if (!isset($_smarty_tpl->getVariable('Data',null,true,false)->value['AddedToCart'][$_smarty_tpl->tpl_vars['Good']->value['GoodId']])){?>
    <p class="good_buy"><input type="submit" name="handlerBtnAdd:<?php echo $_smarty_tpl->tpl_vars['Good']->value['GoodId'];?>
" class="button" value="Заказать"/>
        <?php }else{ ?>
    <p class="hello"><?php echo $_smarty_tpl->getVariable('Data')->value['AddedToCart'][$_smarty_tpl->tpl_vars['Good']->value['GoodId']];?>

        <?php }?>
</div>
<?php }} ?>
<div style="clear:both"></div>
	<?php echo $_smarty_tpl->getVariable('Data')->value['Pager'];?>

<?php }?>

<?php if (isset($_smarty_tpl->getVariable('Data',null,true,false)->value['EmptyGoodListText'])){?>
<div style="text-align: center;" class="text"><?php echo $_smarty_tpl->getVariable('Data')->value['EmptyGoodListText'];?>
</div>
<?php }?>

