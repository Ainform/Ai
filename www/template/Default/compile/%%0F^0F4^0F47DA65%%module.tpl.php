<?php /* Smarty version 2.6.12, created on 2010-05-14 12:09:29
         compiled from Z:/home/buket/www/module/Gallery/module.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sectionLink', 'Z:/home/buket/www/module/Gallery/module.tpl', 6, false),array('function', 'goodLink', 'Z:/home/buket/www/module/Gallery/module.tpl', 32, false),)), $this); ?>
<?php echo $this->_tpl_vars['BreadCrumbs']; ?>

<?php $_from = $this->_tpl_vars['Data']['SectionList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['Section']):
?>
<div class="section_div">
    <div class="section_image">
        <?php if ($this->_tpl_vars['Section']['Image'] != ""): ?>
         <a href="<?php echo $this->_plugins['function']['sectionLink'][0][0]->GetSectionLink(array('id' => $this->_tpl_vars['Section']['SectionId']), $this);?>
"> <img src="<?php echo $this->_tpl_vars['Section']['Image']; ?>
&width=111&height=111&crop=1" alt="" /></a>
        <?php else: ?>
         <a href="<?php echo $this->_plugins['function']['sectionLink'][0][0]->GetSectionLink(array('id' => $this->_tpl_vars['Section']['SectionId']), $this);?>
"> <img src="/css/images/no_foto.png" alt="" /></a>
         <?php endif; ?>
    </div>
    <div>
        <a class="section_title" href="<?php echo $this->_plugins['function']['sectionLink'][0][0]->GetSectionLink(array('id' => $this->_tpl_vars['Section']['SectionId']), $this);?>
"><?php echo $this->_tpl_vars['Section']['Title']; ?>
</a>
    </div>
    <div style="clear:both;height:10px;"></div>
</div>
<?php endforeach; endif; unset($_from); ?>

<?php if (! empty ( $this->_tpl_vars['Data']['Description'] )): ?>
<div style="padding-top: 15px" class="goods_description">
		<?php echo $this->_tpl_vars['Data']['Description']; ?>

</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['Data']['GoodsList']): ?>
<?php $_from = $this->_tpl_vars['Data']['GoodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['Good']):
?>
<div class="good_div">
    <div class="good_image">
        <?php if ($this->_tpl_vars['Good']['Image'] != ""): ?>
        <a href="<?php echo $this->_tpl_vars['Good']['Image']; ?>
" rel="shadowbox[gallery]" title='<?php echo $this->_tpl_vars['Good']['Title']; ?>
'>
            <img src="<?php echo $this->_tpl_vars['Good']['Image']; ?>
&width=111&height=111&crop=1"  alt="<?php echo $this->_tpl_vars['Good']['Title']; ?>
" style="border:none;"/>
        </a>
        <?php else: ?>
        <a href="<?php echo $this->_plugins['function']['goodLink'][0][0]->GetGoodLink(array('id' => $this->_tpl_vars['Good']['GoodId']), $this);?>
">
            <img src="/css/images/no_foto.png"  alt="" style="border:none;"/>
        </a>
        <?php endif; ?>
    </div>
    <p class="good_title"><?php echo $this->_tpl_vars['Good']['Title']; ?>
</p>
</div>
<?php endforeach; endif; unset($_from); ?>
<div style="clear:both"></div>
	<?php echo $this->_tpl_vars['Data']['Pager']; ?>

<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['Data']['EmptyGoodListText'] )): ?>
<div style="text-align: center;" class="text"><?php echo $this->_tpl_vars['Data']['EmptyGoodListText']; ?>
</div>
<?php endif; ?>
