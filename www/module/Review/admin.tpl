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
<form>
  <div style="margin:5px 0px 5px 0px">Емейл на который будут приходить отзывы: <input type="hidden" name="ReviewEmailId" value="{$Data.ReviewEmailId}"><input type="text" name="EditReviewEmail" value="{$Data.ReviewEmail}"><input class="button" type="submit" value="Сменить email" name="changeemail"></div>
</form>
{ajax_update}
<table class="admin_table">
  <tr>
    <th>ФИО</th>
    <th>Заголовок</th>
    <th>Текст</th>
    <th width="150">Показывать на сайте</th>
    <th colspan="4">Функции</th>
  </tr>
  {foreach item=LeaderItem from=$Data.ReviewList name=reviews}
  <tr>
    <td>{$LeaderItem.FIO}</td>
    <td>{$LeaderItem.Fone}</td>
    <td>{$LeaderItem.Text}</td>
    <td align="center"><input type="checkbox" {if $LeaderItem.Show==1}checked{/if} name="handlerShow:{$LeaderItem.ReviewId}" /></td>
    <td width="16">
   <input type="image" name="handlerBtnUp:{$LeaderItem.ReviewId}" src="/img/admin/arrow_up_16.png" alt="Поднять" />
    </td>
    <td width="16">
      <input type="image" name="handlerBtnDown:{$LeaderItem.ReviewId}" src="/img/admin/arrow_down_16.png" alt="Опустить" />
    </td>
    <td align="center" width="30"><input type="image" name="handlerBtnEdit:{$LeaderItem.ReviewId}" id="btnEdit" src="{$Address}img/admin/op_edit.gif" alt="Просмотреть" title="Просмотреть" height="16" width="16" onclick="editReview('{$LeaderItem.ReviewId}'); return false;" /></td>
    <td align="center" width="30"><input type="image" name="handlerBtnDel:{$LeaderItem.ReviewId}" src="/img/admin/close_16.png" alt="Удаление заказа" height="16" width="16" onclick="return confirm('Удалить заказ?');" /></td></tr>
  {/foreach}
</table>
{/ajax_update}
{$Data.Pager}
