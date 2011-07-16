<?php /* Smarty version Smarty-3.0.8, created on 2011-07-16 16:53:06
         compiled from "V:/home/buket_debug/www/template/Default/../media.tpl" */ ?>
<?php /*%%SmartyHeaderCode:30814e216d920e1f87-94630688%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa0f682d7ca1f87c604e186813da1cc5df1d5b51' => 
    array (
      0 => 'V:/home/buket_debug/www/template/Default/../media.tpl',
      1 => 1310467796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '30814e216d920e1f87-94630688',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
﻿
<?php if (count($_smarty_tpl->getVariable('Data')->value['VideoList'])>0){?>
<table width="100%">
		<?php  $_smarty_tpl->tpl_vars['Item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['VideoList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Item']->key => $_smarty_tpl->tpl_vars['Item']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['Item']->key;
?>
			<tr>
				<td align="center">
					<script language="javascript">
						flowplayer("player<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
", "../players/flowplayer/flowplayer-3.1.3.swf", { clip: { autoPlay: false, autoBuffering: true } });
					</script>
					<a 
						 href="<?php echo $_smarty_tpl->tpl_vars['Item']->value;?>
"
						 style="display:block;width:520px;height:330px"  
						 id="player<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
">
					</a>
					<br />
					<br />
				</td>
			</tr>
		<?php }} ?>
</table>
<?php }?>

<?php if (count($_smarty_tpl->getVariable('Data')->value['SoundList'])>0){?>
	<?php if (count($_smarty_tpl->getVariable('Data')->value['SoundList'])==1){?>
		<table width="100%">
			<?php  $_smarty_tpl->tpl_vars['Item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['SoundList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['Item']->key => $_smarty_tpl->tpl_vars['Item']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['Item']->key;
?>
				<tr>
					<td align="right" width="50%">
						<?php echo $_smarty_tpl->tpl_vars['Item']->value['Title'];?>
:
					</td>
					<td>
						<div id="flashPlayer<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"></div>
						<script type="text/javascript">
						   var so = new SWFObject("<?php echo $_smarty_tpl->getVariable('Address')->value;?>
/players/mp3/playerSingle.swf", "mymovie", "192", "67", "7", "#FFFFFF");
						   so.addVariable("autoPlay", "no");
						   so.addVariable("soundPath", "<?php echo $_smarty_tpl->tpl_vars['Item']->value['Path'];?>
");
						   //so.addVariable("playlistPath", "<?php echo $_smarty_tpl->getVariable('Data')->value['PlayList'];?>
");
						   so.addVariable("playerSkin","<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
");
						   so.addVariable("overColor","#0000ff");
						   so.write("flashPlayer<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
");
						</script>
					</td>
				</tr>
			<?php }} ?>
		</table>
	<?php }else{ ?>
		<table width="100%">
			<tr>
				<td align="center">
					<div id="flashPlayer"></div>
				</td>
			</tr>
		</table>
		<script type="text/javascript">
		   var so = new SWFObject("<?php echo $_smarty_tpl->getVariable('Address')->value;?>
/players/mp3/playerMultipleList.swf", "mymovie", "192", "<?php echo $_smarty_tpl->getVariable('Data')->value['Mp3PlayerHeight'];?>
", "7", "#FFFFFF");
		   so.addVariable("autoPlay", "no");
		   //so.addVariable("soundPath", "");
		   so.addVariable("playlistPath", "<?php echo $_smarty_tpl->getVariable('Data')->value['PlayList'];?>
");
		   so.addVariable("playerSkin","1");
		   so.addVariable("overColor","#0000ff");
		   so.write("flashPlayer");
		</script>
	<?php }?>
<?php }?>

<div class="text">
	<?php if (count($_smarty_tpl->getVariable('Data')->value['Files'])>0){?>
	<?php $_smarty_tpl->tpl_vars["common"] = new Smarty_variable(0, null, null);?>
	<table cellpadding="0" cellspacing="10" style="margin-top: 10px;">
		<?php  $_smarty_tpl->tpl_vars['File'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('Data')->value['Files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['File']->key => $_smarty_tpl->tpl_vars['File']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['File']->key;
?>
			<tr>
				<td>
					<a class="download_file" rel="shadowbox;" href="<?php echo $_smarty_tpl->tpl_vars['File']->value['Path'];?>
<?php echo $_smarty_tpl->tpl_vars['File']->value['folder'];?>
<?php echo $_smarty_tpl->tpl_vars['File']->value['filename'];?>
" id="chgImgLink<?php echo $_smarty_tpl->tpl_vars['File']->value['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['File']->value['title'];?>
" style="text-decoration:none !important">
						<img src="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['fileimagemine'][0][0]->GetFileImageMine(array('id'=>$_smarty_tpl->tpl_vars['File']->value['filename']),$_smarty_tpl);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['File']->value['title'];?>
" border="0"/>
					</a>
				</td>
				<td style="padding-right: 10px;text-align:left">	
					<a class="download_file" rel="shadowbox;" href="<?php echo $_smarty_tpl->tpl_vars['File']->value['Path'];?>
<?php echo $_smarty_tpl->tpl_vars['File']->value['folder'];?>
<?php echo $_smarty_tpl->tpl_vars['File']->value['filename'];?>
" id="chgImgLink<?php echo $_smarty_tpl->tpl_vars['File']->value['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['File']->value['title'];?>
" style="text-decoration:none !important">
						<?php echo $_smarty_tpl->tpl_vars['File']->value['title'];?>

					</a>
				</td>
			</tr>
		 <?php }} ?>
	</table> 
	<?php }?>
</div>