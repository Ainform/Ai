<script language="JavaScript" type="text/javascript">
function editReview(ReviewId)
{
	window.location.href = "{$PageAddress}?ReviewId=" + ReviewId;
	return false;
}
function newReview()
{
	window.location.href = "{$PageAddress}?newReview=1";
	return false;
}
</script>
{ajax_update}
<table class="admin_table">
<tr>
<th width="200px">ФИО</th><td>{$Data.Review.FIO}</td></tr>
<tr><th>Заголовок</th><td>{$Data.Review.Fone}</td></tr>
<tr><th>IP</th><td>{$Data.Review.IP}</td>
<tr><th>Сообщение</th><td>{FCKEditor id="fckText" height="400" value=$Data.Review.Text}</td>
</tr>
</table>
{/ajax_update} 
<div style="text-align:center;margin:5px;">
<input type="submit" class="button" value="Сохранить" name="handlerBtnSave:{$Data.Review.ReviewId}" title="Сохранить изменения" />&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="button" onclick="history.go(-1)" class="button" name="" value="Назад" title="Вернуться к просмотру" /></div>


