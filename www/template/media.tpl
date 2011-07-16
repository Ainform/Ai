{*видео-плееры*}
{if count($Data.VideoList) > 0}
<table width="100%">
		{foreach item=Item from=$Data.VideoList key=i}
			<tr>
				<td align="center">
					<script language="javascript">
						flowplayer("player{$i}", "../players/flowplayer/flowplayer-3.1.3.swf", { clip: { autoPlay: false, autoBuffering: true } });
					</script>
					<a 
						 href="{$Item}"
						 style="display:block;width:520px;height:330px"  
						 id="player{$i}">
					</a>
					<br />
					<br />
				</td>
			</tr>
		{/foreach}
</table>
{/if}

{if count($Data.SoundList) > 0}

	{*sound-плееры*}
	{if count($Data.SoundList) == 1}
		<table width="100%">
			{foreach item=Item from=$Data.SoundList key=i}
				<tr>
					<td align="right" width="50%">
						{$Item.Title}:
					</td>
					<td>
						<div id="flashPlayer{$i}"></div>
						<script type="text/javascript">
						   var so = new SWFObject("{$Address}/players/mp3/playerSingle.swf", "mymovie", "192", "67", "7", "#FFFFFF");
						   so.addVariable("autoPlay", "no");
						   so.addVariable("soundPath", "{$Item.Path}");
						   //so.addVariable("playlistPath", "{$Data.PlayList}");
						   so.addVariable("playerSkin","{$i}");
						   so.addVariable("overColor","#0000ff");
						   so.write("flashPlayer{$i}");
						</script>
					</td>
				</tr>
			{/foreach}
		</table>
	{else}
		<table width="100%">
			<tr>
				<td align="center">
					<div id="flashPlayer"></div>
				</td>
			</tr>
		</table>
		<script type="text/javascript">
		   var so = new SWFObject("{$Address}/players/mp3/playerMultipleList.swf", "mymovie", "192", "{$Data.Mp3PlayerHeight}", "7", "#FFFFFF");
		   so.addVariable("autoPlay", "no");
		   //so.addVariable("soundPath", "{*$Item.Path*}");
		   so.addVariable("playlistPath", "{$Data.PlayList}");
		   so.addVariable("playerSkin","1");
		   so.addVariable("overColor","#0000ff");
		   so.write("flashPlayer");
		</script>
	{/if}
{/if}

<div class="text">
	{if count($Data.Files) > 0}
	{assign var="common" value=0}
	<table cellpadding="0" cellspacing="10" style="margin-top: 10px;">
		{foreach item=File from=$Data.Files name="files" key=i}
			<tr>
				<td>
					<a class="download_file" rel="shadowbox;" href="{$File.Path}{$File.folder}{$File.filename}" id="chgImgLink{$File.id}" title="{$File.title}" style="text-decoration:none !important">
						<img src="{fileimagemine id=$File.filename}" alt="{$File.title}" border="0"/>
					</a>
				</td>
				<td style="padding-right: 10px;text-align:left">	
					<a class="download_file" rel="shadowbox;" href="{$File.Path}{$File.folder}{$File.filename}" id="chgImgLink{$File.id}" title="{$File.title}" style="text-decoration:none !important">
						{$File.title}
					</a>
				</td>
			</tr>
		 {/foreach}
	</table> 
	{/if}
</div>