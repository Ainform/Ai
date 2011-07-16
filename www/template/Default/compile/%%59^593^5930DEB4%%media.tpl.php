<?php /* Smarty version 2.6.12, created on 2011-07-09 17:05:27
         compiled from ../media.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fileimagemine', '../media.tpl', 76, false),)), $this); ?>
﻿<?php if (count ( $this->_tpl_vars['Data']['VideoList'] ) > 0): ?>
<table width="100%">
		<?php $_from = $this->_tpl_vars['Data']['VideoList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['Item']):
?>
			<tr>
				<td align="center">
					<script language="javascript">
						flowplayer("player<?php echo $this->_tpl_vars['i']; ?>
", "../players/flowplayer/flowplayer-3.1.3.swf", { clip: { autoPlay: false, autoBuffering: true } });
					</script>
					<a 
						 href="<?php echo $this->_tpl_vars['Item']; ?>
"
						 style="display:block;width:520px;height:330px"  
						 id="player<?php echo $this->_tpl_vars['i']; ?>
">
					</a>
					<br />
					<br />
				</td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>

<?php if (count ( $this->_tpl_vars['Data']['SoundList'] ) > 0): ?>

		<?php if (count ( $this->_tpl_vars['Data']['SoundList'] ) == 1): ?>
		<table width="100%">
			<?php $_from = $this->_tpl_vars['Data']['SoundList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['Item']):
?>
				<tr>
					<td align="right" width="50%">
						<?php echo $this->_tpl_vars['Item']['Title']; ?>
:
					</td>
					<td>
						<div id="flashPlayer<?php echo $this->_tpl_vars['i']; ?>
"></div>
						<script type="text/javascript">
						   var so = new SWFObject("<?php echo $this->_tpl_vars['Address']; ?>
/players/mp3/playerSingle.swf", "mymovie", "192", "67", "7", "#FFFFFF");
						   so.addVariable("autoPlay", "no");
						   so.addVariable("soundPath", "<?php echo $this->_tpl_vars['Item']['Path']; ?>
");
						   //so.addVariable("playlistPath", "<?php echo $this->_tpl_vars['Data']['PlayList']; ?>
");
						   so.addVariable("playerSkin","<?php echo $this->_tpl_vars['i']; ?>
");
						   so.addVariable("overColor","#0000ff");
						   so.write("flashPlayer<?php echo $this->_tpl_vars['i']; ?>
");
						</script>
					</td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
		</table>
	<?php else: ?>
		<table width="100%">
			<tr>
				<td align="center">
					<div id="flashPlayer"></div>
				</td>
			</tr>
		</table>
		<script type="text/javascript">
		   var so = new SWFObject("<?php echo $this->_tpl_vars['Address']; ?>
/players/mp3/playerMultipleList.swf", "mymovie", "192", "<?php echo $this->_tpl_vars['Data']['Mp3PlayerHeight']; ?>
", "7", "#FFFFFF");
		   so.addVariable("autoPlay", "no");
		   //so.addVariable("soundPath", "");
		   so.addVariable("playlistPath", "<?php echo $this->_tpl_vars['Data']['PlayList']; ?>
");
		   so.addVariable("playerSkin","1");
		   so.addVariable("overColor","#0000ff");
		   so.write("flashPlayer");
		</script>
	<?php endif; ?>
<?php endif; ?>

<div class="text">
	<?php if (count ( $this->_tpl_vars['Data']['Files'] ) > 0): ?>
	<?php $this->assign('common', 0); ?>
	<table cellpadding="0" cellspacing="10" style="margin-top: 10px;">
		<?php $_from = $this->_tpl_vars['Data']['Files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['files'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['files']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['File']):
        $this->_foreach['files']['iteration']++;
?>
			<tr>
				<td>
					<a class="download_file" rel="shadowbox;" href="<?php echo $this->_tpl_vars['File']['Path'];  echo $this->_tpl_vars['File']['folder'];  echo $this->_tpl_vars['File']['filename']; ?>
" id="chgImgLink<?php echo $this->_tpl_vars['File']['id']; ?>
" title="<?php echo $this->_tpl_vars['File']['title']; ?>
" style="text-decoration:none !important">
						<img src="<?php echo $this->_plugins['function']['fileimagemine'][0][0]->GetFileImageMine(array('id' => $this->_tpl_vars['File']['filename']), $this);?>
" alt="<?php echo $this->_tpl_vars['File']['title']; ?>
" border="0"/>
					</a>
				</td>
				<td style="padding-right: 10px;text-align:left">	
					<a class="download_file" rel="shadowbox;" href="<?php echo $this->_tpl_vars['File']['Path'];  echo $this->_tpl_vars['File']['folder'];  echo $this->_tpl_vars['File']['filename']; ?>
" id="chgImgLink<?php echo $this->_tpl_vars['File']['id']; ?>
" title="<?php echo $this->_tpl_vars['File']['title']; ?>
" style="text-decoration:none !important">
						<?php echo $this->_tpl_vars['File']['title']; ?>

					</a>
				</td>
			</tr>
		 <?php endforeach; endif; unset($_from); ?>
	</table> 
	<?php endif; ?>
</div>