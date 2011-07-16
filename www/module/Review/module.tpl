{if count($Data.ReviewList)>0}
{foreach item=LeaderItem from=$Data.ReviewList}
{if $LeaderItem.Show==1}
<p>Имя: {$LeaderItem.FIO}</p>
<p>Тема: {$LeaderItem.Fone}</p>
<p>Отзыв: {$LeaderItem.Text}</p>
<hr />
{/if}
{/foreach}
{$Data.Pager}
{/if}

{if isset($Data.request)}

<p>{$Data.request}</p>
{else}
<style>
.validateError{
text-indent:5px!important;
color:#7D2325;
font-size:8pt;
}
</style>
<h2 style="font-size:10pt;">Вы можете оставить свой отзыв, заполнив форму ниже:</h2>
<table cellpadding="5" cellspacing="0" border="0" class="request_table text" style="width:auto;">

	 <!--<tr>
		  <td align="right" width="120px"><label for="txtEmail">email:</label><span style="color: #FF1111;">*</span></td>
		  <td width="220px">{TextBox id="txtEmail" maxlength="100" class="text_box_contact" value=""}</td>
		  <td width=100% style="color: #FFF;">{Validator for="txtEmail" rule="NotNull Email" message="Введите e-mail"}</td>
	 </tr>-->
	 <tr>
		  <td align="right" width="150"><label for="txtFIO">ФИО:</label><span style="color: #FF1111;">*</span></td>
		  <td>{TextBox id="txtFIO" maxlength="100" class="text_box_contact" value=""}</td>
		  <td style="color: #FFF;">{Validator for="txtFIO" rule="NotNull" message="Введите ФИО"}</td>
	 </tr>

	 <tr>
		  <td align="right"><label for="txtFone">Тема:</label><span style="color: #FF1111;">*</span></td>
		  <td>{TextBox id="txtFone" maxlength="100" class="text_box_contact" value=""}</td>
		  <td style="color: #FFF;">{Validator for="txtFone" rule="NotNull" message="Введите телефон"}</td>
	 </tr>

	 <tr>
		  <td align="right" valign="top"><label for="txtMessage">Отзыв</label><span style="color: #FF1111;">*</span></td>
		  <td ><textarea id="txtMessage" name="txtMessage" cols="50" rows="8" style="font-family:Arial,sans-serif;font-size:10pt">{$Data.txtMessage}</textarea></td>
		  <td><span style="color: #FFF;">{Validator for="txtMessage" rule="NotNull" message="Введите текст"}</span></td>
	 </tr>

	 <tr>
		  <td id="captcha_overview" align=right>Введите текст с картинки:<span style="color: #FF1111;">*</span></td>
		  <td>
		  <table cellpadding=0 cellspacing=0><tr><td style="padding:0px;width:120px">
		  <img alt="" width="120" id="captcha_image"src="/util/captcha.php?{$Data.session_name}={$Data.session_id}"></td>
		<td style="padding-top:0;padding-bottom:0;padding-left:10px;">
		  <input id="captcha_text" type="text" name="keystring"></td>
			 </tr>
		  </table></td>
		<td><span style="color: #FFF;">{Validator for="keystring" rule="Captcha" message="Текст неверный или не введён"}</span></td>
		  <tr><td>&nbsp;</td><td colspan=2 align=left>
		  <p class="good_buy">
		  <input type="submit" id="send_message" class="button" name="handlerSendMessage" value="Отправить" val="noajax"></p></td></tr>
</tr>
	  <tr>
	 <td colspan="3" id="required_field" style="padding-bottom:10px;"><span style="color: #FF1111;">*</span> Поля обязательные для заполнения.</td>
</tr>
</table>
{/if}