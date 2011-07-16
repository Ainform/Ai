{include file="../nojs.tpl"}

{foreach item=Module from=$Modules}
	{assign var=Data value=$Module->getData()}
	{if isset($ShowOnlyThisModuleId)}
		{if $ShowOnlyThisModuleId == $Module->moduleId}
			<form method="post" action="" name="form{$Module->moduleId}" id="form{$Module->moduleId}" enctype="multipart/form-data">
				<input type="hidden" name="moduleId" value="{$Module->moduleId}"/>
				<div class="module {$Module->cssClass}">
				{include file=$Module->getTemplatePath()}
				</div>
			</form>
		{/if}
	{else}
		{if !isset($Module->noform)}<form method="post" action="" name="form{$Module->moduleId}" id="form{$Module->moduleId}" enctype="multipart/form-data">{/if}
			<input type="hidden" name="moduleId" value="{$Module->moduleId}"/>
			<div class="module {$Module->cssClass}">
			{include file=$Module->getTemplatePath()}
			</div>
		{if !isset($Module->noform)}</form>{/if}
	{/if}
{/foreach}