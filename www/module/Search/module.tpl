<!--<form action="/Search" method="GET">
	<input type="text" name="q" id="Searher" value="{if isset($SearchQuery)}{$SearchQuery}{/if}">
	<input type="submit" value="Найти">
	<input type="submit" value="В Яндексе" onclick='moveLoc("http://yandex.ru/yandsearch?rpt=rad&site=mgudt.ru&text=" + $("Searher").value);return false;'>
	<input type="submit" value="В Google" onclick='moveLoc("http://www.google.ru/search?hl=ru&q=" + $("Searher").value + "+site%3Amgudt.ru");return false;'>
</form><br>-->
По вашему запросу "{$Query}" найдено страниц: {$Data.SearchAll}<br>
{foreach item=Result from=$Data.SearchResult}
<div>	
	<h3 style="margin-bottom:3px"><a href="{$Result.Url}">{$Result.Numer}. {$Result.Title}</a></h3>
	{*$Result.Body|strip_tags|truncate:500:"...":false*}
	{$Result.Body}
</div>	
{/foreach}
<table style="margin-top:20px;"><tr><td >{$Data.PagerTitle}</td><td> {$Data.Pager}</td></tr></table>