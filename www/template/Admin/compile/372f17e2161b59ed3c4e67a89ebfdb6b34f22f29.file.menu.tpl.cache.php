<?php /* Smarty version Smarty-3.0.8, created on 2011-07-12 17:05:25
         compiled from "V:/home/buket_debug/www/template/Admin/menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:137784e1c2a75b11805-11222375%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '372f17e2161b59ed3c4e67a89ebfdb6b34f22f29' => 
    array (
      0 => 'V:/home/buket_debug/www/template/Admin/menu.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '137784e1c2a75b11805-11222375',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<ul id="main-nav">
    <?php  $_smarty_tpl->tpl_vars['MenuItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Menu')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['MenuItem']->key => $_smarty_tpl->tpl_vars['MenuItem']->value){
?>
    <?php if ($_smarty_tpl->getVariable('MenuItem')->value->IsSeparator()){?>
    <br />
    <?php }else{ ?>
    <?php if ($_smarty_tpl->getVariable('MenuItem')->value->Visible!=''&&$_smarty_tpl->getVariable('MenuItem')->value->Level==1){?>
    <li>
        <a href="<?php echo $_smarty_tpl->getVariable('MenuItem')->value->Url;?>
" class="level<?php echo $_smarty_tpl->getVariable('MenuItem')->value->Level;?>

           <?php if ($_smarty_tpl->getVariable('MenuItem')->value->thisIsModule){?>thisismodule <?php }?>
           <?php if ($_smarty_tpl->getVariable('MenuItem')->value->Selected){?>current<?php }?>
           <?php if ($_smarty_tpl->getVariable('MenuItem')->value->HasSelectedChild){?> current<?php }?>
           nav-top-item nav <?php if ($_smarty_tpl->getVariable('MenuItem')->value->HasChild()){?>childed<?php }else{ ?>no-submenu<?php }?>">
            <?php echo $_smarty_tpl->getVariable('MenuItem')->value->Title;?>
</a>
        <?php if ($_smarty_tpl->getVariable('MenuItem')->value->HasChild()){?>
        <ul class=" <?php if ($_smarty_tpl->getVariable('MenuItem')->value->HasSelectedChild){?> current<?php }?>">
            <?php  $_smarty_tpl->tpl_vars['SubItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('MenuItem')->value->Childs; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['SubItem']->key => $_smarty_tpl->tpl_vars['SubItem']->value){
?>
            <?php if ($_smarty_tpl->getVariable('SubItem')->value->Visible!=false&&$_smarty_tpl->getVariable('SubItem')->value->Title!=''&&$_smarty_tpl->getVariable('SubItem')->value->HideInMenu==0){?>
            <li>
                <a href="<?php echo $_smarty_tpl->getVariable('SubItem')->value->Url;?>
" title='<?php if ($_smarty_tpl->getVariable('SubItem')->value->thisIsModule){?>Модуль <?php }else{ ?>Подраздел <?php }?>"<?php echo $_smarty_tpl->getVariable('SubItem')->value->Title;?>
"' class="level<?php echo $_smarty_tpl->getVariable('SubItem')->value->Level;?>

                   nav
                   <?php if ($_smarty_tpl->getVariable('SubItem')->value->thisIsModule){?>thisismodule <?php }?>
                   <?php if ($_smarty_tpl->getVariable('SubItem')->value->Selected){?>current<?php }?>
                   <?php if ($_smarty_tpl->getVariable('SubItem')->value->HasSelectedChild){?> current<?php }?>
                   <?php if ($_smarty_tpl->getVariable('SubItem')->value->HasChild()){?>childed subchilded <?php }else{ ?>no-submenu<?php }?>" title="<?php echo $_smarty_tpl->getVariable('SubItem')->value->Title;?>
"><?php echo $_smarty_tpl->getVariable('SubItem')->value->Title;?>
</a>
                <?php if ($_smarty_tpl->getVariable('SubItem')->value->HasChild()){?>
                <ul  class=" <?php if ($_smarty_tpl->getVariable('SubItem')->value->HasSelectedChild){?> current<?php }?>">
                    <?php  $_smarty_tpl->tpl_vars['SubSubItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('SubItem')->value->Childs; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['SubSubItem']->key => $_smarty_tpl->tpl_vars['SubSubItem']->value){
?>
                    <?php if ($_smarty_tpl->getVariable('SubSubItem')->value->Visible!=false&&$_smarty_tpl->getVariable('SubSubItem')->value->Title!=''&&$_smarty_tpl->getVariable('SubSubItem')->value->HideInMenu==0){?>
                    <li>
                        <a href="<?php echo $_smarty_tpl->getVariable('SubSubItem')->value->Url;?>
" title='<?php if ($_smarty_tpl->getVariable('SubSubItem')->value->thisIsModule){?>Модуль <?php }else{ ?>Подраздел <?php }?>"<?php echo $_smarty_tpl->getVariable('SubSubItem')->value->Title;?>
"' class="level<?php echo $_smarty_tpl->getVariable('SubSubItem')->value->Level;?>
 nav
                           <?php if ($_smarty_tpl->getVariable('SubSubItem')->value->thisIsModule){?>thisismodule <?php }?>
                           <?php if ($_smarty_tpl->getVariable('SubSubItem')->value->Selected){?>current<?php }?>
                           <?php if ($_smarty_tpl->getVariable('SubSubItem')->value->HasSelectedChild){?> current<?php }?>
                           <?php if ($_smarty_tpl->getVariable('SubSubItem')->value->HasChild()){?>childed subsubchilded<?php }else{ ?>no-submenu<?php }?>" title="<?php echo $_smarty_tpl->getVariable('SubSubItem')->value->Title;?>
"><?php echo $_smarty_tpl->getVariable('SubSubItem')->value->Title;?>
</a>

                            <?php if ($_smarty_tpl->getVariable('SubSubItem')->value->HasChild()){?>
                <ul  class=" <?php if ($_smarty_tpl->getVariable('SubSubItem')->value->HasSelectedChild){?> current<?php }?>">
                    <?php  $_smarty_tpl->tpl_vars['SubSubSubItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('SubSubItem')->value->Childs; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['SubSubSubItem']->key => $_smarty_tpl->tpl_vars['SubSubSubItem']->value){
?>
                    <?php if ($_smarty_tpl->getVariable('SubSubSubItem')->value->Visible!=false&&$_smarty_tpl->getVariable('SubSubSubItem')->value->Title!=''&&$_smarty_tpl->getVariable('SubSubSubItem')->value->HideInMenu==0){?>
                    <li>
                        <a href="<?php echo $_smarty_tpl->getVariable('SubSubSubItem')->value->Url;?>
" title='<?php if ($_smarty_tpl->getVariable('SubSubSubItem')->value->thisIsModule){?>Модуль <?php }else{ ?>Подраздел <?php }?>"<?php echo $_smarty_tpl->getVariable('SubSubSubItem')->value->Title;?>
"' class="level<?php echo $_smarty_tpl->getVariable('SubSubSubItem')->value->Level;?>
 nav
                           <?php if ($_smarty_tpl->getVariable('SubSubSubItem')->value->thisIsModule){?>thisismodule <?php }?>
                           <?php if ($_smarty_tpl->getVariable('SubSubSubItem')->value->Selected){?>current<?php }?>
                           <?php if ($_smarty_tpl->getVariable('SubSubSubItem')->value->HasSelectedChild){?> current<?php }?>
                           <?php if ($_smarty_tpl->getVariable('SubSubSubItem')->value->HasChild()){?>childed<?php }else{ ?>no-submenu<?php }?>" title="<?php echo $_smarty_tpl->getVariable('SubSubSubItem')->value->Title;?>
"><?php echo $_smarty_tpl->getVariable('SubSubSubItem')->value->Title;?>
</a>
                    </li>
                    <?php }?>
                    <?php }} ?>
                </ul>
                <?php }?>

                    </li>
                    <?php }?>
                    <?php }} ?>
                </ul>
                <?php }?>

            </li>
            <?php }?>
            <?php }} ?>
        </ul>
        <?php }?>
    </li>
    <?php }?>
    <?php }?>
    <?php }} ?>
</ul>