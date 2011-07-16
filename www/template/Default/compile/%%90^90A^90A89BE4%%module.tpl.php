<?php /* Smarty version 2.6.12, created on 2011-04-04 05:11:02
         compiled from /home/u197068/buketufa.ru/www/module/News/module.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'newsLink', '/home/u197068/buketufa.ru/www/module/News/module.tpl', 7, false),)), $this); ?>
<h1><?php echo $this->_tpl_vars['Title']; ?>
</h1>
<?php if (count ( $this->_tpl_vars['Data']['NewsList'] ) > 0): ?>
<table>
    <?php $_from = $this->_tpl_vars['Data']['NewsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['news'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['news']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['News']):
        $this->_foreach['news']['iteration']++;
?>
    <tr>
        <td colspan="2">
            <h3 class="news_title"><a href="<?php echo $this->_plugins['function']['newsLink'][0][0]->GetLink(array('id' => $this->_tpl_vars['News']['id']), $this);?>
"><?php echo $this->_tpl_vars['News']['title']; ?>
</a></h3>
        </td></tr>
    <tr>
        <td class="news_preview_image">
            <?php if (isset ( $this->_tpl_vars['News']['Image'] )): ?>
            <a href="<?php echo $this->_plugins['function']['newsLink'][0][0]->GetLink(array('id' => $this->_tpl_vars['News']['id']), $this);?>
">
                <img src="<?php echo $this->_tpl_vars['News']['Image']; ?>
&width=150&height=100&crop=1" alt="" title="" class="news_preview_image" align="left" />
            </a>
            <?php endif; ?>
        </td><td class="news_preview_text">
            <?php echo $this->_tpl_vars['News']['anons']; ?>

        </td></tr>
    <?php endforeach; endif; unset($_from); ?>
</table>
<?php else: ?>
<div class="">Раздел в разработке</div>
<?php endif; ?>
<?php echo $this->_tpl_vars['Data']['Pager']; ?>