<form action="" method="post" enctype="application/x-www-form-urlencoded">

  <table class="admin_table" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <th width="100%">Импортирование каталога</th>
    </tr>
    <tr>
      <td width="100%">
        <input type="file" style="width: 100%" name="userfile" id="filename">
      </td>
    </tr>
    <tr><td><table cellpadding="3" border="none" style="border:none"><tr>
            <td colspan="3" style="border:none">Тип прайса:</td></tr>
          <tr><td style="border:none">
              <label for="Panduit">Panduit<br><img src="/img/panduit.png"></label>
              <input id="Panduit" type="radio" name="priceType" value="1" checked>
            </td><td style="border:none">
              <label for="Kyland">Kyland<br><img src="/img/kyland.png"></label>
              <input id="Kyland" type="radio" name="priceType" value="2">
            </td>
            <td style="border:none">
              <label for="Legrand">Legrand<br><img src="/img/legrand.gif"></label>
              <input id="Legrand" type="radio" name="priceType" value="3">
            </td>
            </tr>
            <tr><td colspan="3" style="border:none">Валюта:</td></tr>
                    <tr><td colspan="2" style="border:none">
                    <label for="rub">Рубли</label><input type="radio" checked id="rub" name="currency" value="0" />&nbsp;&nbsp;   
                    <label for="dollar">Доллары</label><input type="radio" checked id="dollar" name="currency" value="1" />&nbsp;&nbsp;
                    <label for="euro">Евро</label><input type="radio" id="euro" name="currency" value="2">
                    </td></tr>
            </table>
      </td></tr>
  </table>
  <input type="hidden" name="step" value="2">
  <p align="center">
    <input type="submit" class="button">
  </p>
</form>