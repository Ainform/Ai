<?php /* Smarty version 2.6.12, created on 2011-04-05 03:38:20
         compiled from /home/u197068/buketufa.ru/www/module/News/news.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', '/home/u197068/buketufa.ru/www/module/News/news.tpl', 13, false),)), $this); ?>
<p><a href="javascript:history.go(-1)">Назад</a></p>
<?php if (isset ( $this->_tpl_vars['Data']['isshow'] )):  if (isset ( $this->_tpl_vars['Data']['FirstPhoto'] )): ?>
<div style="float:left;margin-right:12px;margin-bottom:12px;">
    <a href="<?php echo $this->_tpl_vars['Data']['FirstPhoto']['Path']; ?>
" rel="shadowbox[news]" title="<?php echo $this->_tpl_vars['Data']['FirstPhoto']['Title']; ?>
">
        <img style="border:1px solid #fff" src="<?php echo $this->_tpl_vars['Data']['FirstPhoto']['Path']; ?>
&width=315" alt="<?php echo $this->_tpl_vars['Data']['FirstPhoto']['Title']; ?>
" title="<?php echo $this->_tpl_vars['Data']['FirstPhoto']['Title']; ?>
" />
    </a>
    <?php if (count ( $this->_tpl_vars['Data']['Images'] ) > 0): ?>
    <table cellpadding="0" cellspacing="0" style="margin-top: 10px;"><tr>
	<?php $this->assign('common', 0); ?>
	<?php $_from = $this->_tpl_vars['Data']['Images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Image']):
?>
	  <?php if ($this->_tpl_vars['common'] == 3): ?>
	   <?php echo smarty_function_math(array('equation' => "sum-3",'sum' => $this->_tpl_vars['common'],'count' => 1,'assign' => 'common'), $this);?>

        </tr>
        <tr valign="top">
	  <?php endif; ?>
	  <?php echo smarty_function_math(array('equation' => "sum+count",'sum' => $this->_tpl_vars['common'],'count' => 1,'assign' => 'common'), $this);?>

            <td style="padding:4px 8px 4px 0px;">
                <a href="<?php echo $this->_tpl_vars['Image']['Path']; ?>
" rel="shadowbox[news]" title="<?php echo $this->_tpl_vars['Image']['Title']; ?>
">
                    <img src="<?php echo $this->_tpl_vars['Image']['Path']; ?>
&width=100&border=1" alt="<?php echo $this->_tpl_vars['Image']['Title']; ?>
" title="<?php echo $this->_tpl_vars['Image']['Title']; ?>
" width="100" />
                </a>
            </td>
	<?php endforeach; endif; unset($_from); ?>
        </tr>
    </table>
    <?php endif; ?>
</div>
<?php endif; ?>
<h3 class="news_title"><?php echo $this->_tpl_vars['Data']['News']['title']; ?>
</h3>
<div class="news_text_page">
    <?php echo $this->_tpl_vars['Data']['News']['text']; ?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../media.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>