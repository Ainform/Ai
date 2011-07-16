{if !isset($Data.ErrorsList)}
<div class="notification information png_bg"><div>Сообщения об ошибках отсутствуют</div></div>
{else}
<div class="buttons">
    <input class="button" type="submit" name="handlerBtnClearClick" value="Очистить" title="Очистить отчет об ошибках" onclick="return confirm(\'Очистить отчет об ошибках?\');" />
</div>
<br>
{$Data.ErrorsList}
<br>
<div class="buttons">
    <input class="button" type="submit" name="handlerBtnClearClick" value="Очистить" title="Очистить отчет об ошибках" onclick="return confirm(\'Очистить отчет об ошибках?\');" />
</div>
{/if}