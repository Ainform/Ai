{if isset($Data.Info)}
	<b>Последния индексация: </b>{$Data.Info.Date}<br><br><br><br>
	<form action="" method="GET"><input class="button" type="submit" name="act" value="Переиндексировать"></form>
{else}
	Проиндексировано: {$Data.Result}
{/if}