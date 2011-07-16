<?php /* Smarty version 2.6.12, created on 2011-04-02 17:26:05
         compiled from /home/u197068/buketufa.ru/www/module/Review/module.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'TextBox', '/home/u197068/buketufa.ru/www/module/Review/module.tpl', 29, false),array('function', 'Validator', '/home/u197068/buketufa.ru/www/module/Review/module.tpl', 30, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['Data']['ReviewList'] ) > 0):  $_from = $this->_tpl_vars['Data']['ReviewList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['LeaderItem']):
 if ($this->_tpl_vars['LeaderItem']['Show'] == 1): ?>
<p>Имя: <?php echo $this->_tpl_vars['LeaderItem']['FIO']; ?>
</p>
<p>Тема: <?php echo $this->_tpl_vars['LeaderItem']['Fone']; ?>
</p>
<p>Отзыв: <?php echo $this->_tpl_vars['LeaderItem']['Text']; ?>
</p>
<hr />
<?php endif;  endforeach; endif; unset($_from);  echo $this->_tpl_vars['Data']['Pager']; ?>

<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['Data']['request'] )): ?>

<p><?php echo $this->_tpl_vars['Data']['request']; ?>
</p>
<?php else: ?>
<style>
.validateError{
text-indent:5px!important;
color:#7D2325;
font-size:8pt;
}
</style>
<h2 style="font-size:10pt;">Вы можете оставить свой отзыв, заполнив форму ниже:</h2>
<table cellpadding="5" cellspacing="0" border="0" class="request_table text" style="width:auto;">

	 <!--<tr>
		  <td align="right" width="120px"><label for="txtEmail">email:</label><span style="color: #FF1111;">*</span></td>
		  <td width="220px"><?php echo smarty_function_TextBox(array('id' => 'txtEmail','maxlength' => '100','class' => 'text_box_contact','value' => ""), $this);?>
</td>
		  <td width=100% style="color: #FFF;"><?php echo smarty_function_Validator(array('for' => 'txtEmail','rule' => 'NotNull Email','message' => "Введите e-mail"), $this);?>
</td>
	 </tr>-->
	 <tr>
		  <td align="right" width="150"><label for="txtFIO">ФИО:</label><span style="color: #FF1111;">*</span></td>
		  <td><?php echo smarty_function_TextBox(array('id' => 'txtFIO','maxlength' => '100','class' => 'text_box_contact','value' => ""), $this);?>
</td>
		  <td style="color: #FFF;"><?php echo smarty_function_Validator(array('for' => 'txtFIO','rule' => 'NotNull','message' => "Введите ФИО"), $this);?>
</td>
	 </tr>

	 <tr>
		  <td align="right"><label for="txtFone">Тема:</label><span style="color: #FF1111;">*</span></td>
		  <td><?php echo smarty_function_TextBox(array('id' => 'txtFone','maxlength' => '100','class' => 'text_box_contact','value' => ""), $this);?>
</td>
		  <td style="color: #FFF;"><?php echo smarty_function_Validator(array('for' => 'txtFone','rule' => 'NotNull','message' => "Введите телефон"), $this);?>
</td>
	 </tr>

	 <tr>
		  <td align="right" valign="top"><label for="txtMessage">Отзыв</label><span style="color: #FF1111;">*</span></td>
		  <td ><textarea id="txtMessage" name="txtMessage" cols="50" rows="8" style="font-family:Arial,sans-serif;font-size:10pt"><?php echo $this->_tpl_vars['Data']['txtMessage']; ?>
</textarea></td>
		  <td><span style="color: #FFF;"><?php echo smarty_function_Validator(array('for' => 'txtMessage','rule' => 'NotNull','message' => "Введите текст"), $this);?>
</span></td>
	 </tr>

	 <tr>
		  <td id="captcha_overview" align=right>Введите текст с картинки:<span style="color: #FF1111;">*</span></td>
		  <td>
		  <table cellpadding=0 cellspacing=0><tr><td style="padding:0px;width:120px">
		  <img alt="" width="120" id="captcha_image"src="/util/captcha.php?<?php echo $this->_tpl_vars['Data']['session_name']; ?>
=<?php echo $this->_tpl_vars['Data']['session_id']; ?>
"></td>
		<td style="padding-top:0;padding-bottom:0;padding-left:10px;">
		  <input id="captcha_text" type="text" name="keystring"></td>
			 </tr>
		  </table></td>
		<td><span style="color: #FFF;"><?php echo smarty_function_Validator(array('for' => 'keystring','rule' => 'Captcha','message' => "Текст неверный или не введён"), $this);?>
</span></td>
		  <tr><td>&nbsp;</td><td colspan=2 align=left>
		  <p class="good_buy">
		  <input type="submit" id="send_message" class="button" name="handlerSendMessage" value="Отправить" val="noajax"></p></td></tr>
</tr>
	  <tr>
	 <td colspan="3" id="required_field" style="padding-bottom:10px;"><span style="color: #FF1111;">*</span> Поля обязательные для заполнения.</td>
</tr>
</table>
<?php endif; ?>