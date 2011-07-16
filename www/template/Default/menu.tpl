{if ($IsSystemsAndSolutonsPage)}

{foreach item=MenuItem from=$Menu}

{if $MenuItem->Visible != false && $MenuItem->IsCurrent!==false && $MenuItem->Level==1 && count($MenuItem->Childs) > 0 }
<tr><td width="214" align="left" style="padding: 0px 15px 0px 15px;"><h2 class="prod">{$MenuItem->Title}</h2></td></tr>
<tr><td><div class="moduletableleft"><ul class="menu">
	 </ul></div></td></tr>
<tr><td><div class="moduletableleft">
  {foreach item=SubItem from=$MenuItem->Childs name=terminator2return} 
		 <h3 style="background-color:#BBBBBB;text-transform: uppercase;" 
	 {if $smarty.foreach.terminator2return.index==0}id="manufacturerheader" onclick="showFilterPanel(0);{else}id="typeheader" onclick="showFilterPanel(1);{/if} 
	 ">{$SubItem->Title}</h3>
  {if $SubItem->Visible != false && $SubItem->Title != "" && $SubItem->HideInMenu == 0}
		{if count($SubItem->Childs) > 0}
		<ul class="menu" {if $smarty.foreach.terminator2return.index==0}id="manufacturerlist"{else}id="typelist"{/if} style="">
		
		{foreach item=SubSubItem from=$SubItem->Childs}  
		
			{if $SubSubItem->Visible != false && $SubSubItem->Title != "" && $SubSubItem->HideInMenu == 0}
			<li>
				 <a {if $SubSubItem->IsCurrent!==false}style="font-weight:bold;"{/if} href="{$SubSubItem->Path}/" title="{$SubSubItem->Title}">{$SubSubItem->Title}</a>
			</li>
			{/if}
		{/foreach}
		
		</ul>
		{/if}

  {/if}
  {/foreach}
  
</div></td></tr>
{/if}
{/foreach}

{else}

{foreach item=MenuItem from=$Menu}
{if $MenuItem->Visible != false && $MenuItem->IsCurrent!==false && $MenuItem->Level==1 && count($MenuItem->Childs) > 0 }
<tr><td width="214" align="left" style="padding-left:15px;padding-right:15px;"><h2 class="prod">{$MenuItem->Title}</h2></td></tr>
<tr><td><div class="moduletableleft"><ul class="menu">
  {foreach item=SubItem from=$MenuItem->Childs}  
  {if $SubItem->Visible != false && $SubItem->Title != "" && $SubItem->HideInMenu == 0}
  <li>
	 <a {if $SubItem->IsCurrent!==false}style="font-weight:bold;"{/if} href="{$SubItem->Path}/" title="{$SubItem->Title}">{$SubItem->Title}</a>
  </li>
  {/if}
  {/foreach}
</ul></div></td></tr>
{/if}
{/foreach}

{/if}

