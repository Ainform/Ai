<script type="text/javascript">
    function Last(){
        window.location = '';
    }


    function Uploader(){
        window.location = '{$PageAddress}?act=Upload&UserId={$Data.Users.UserId}';
    }


    function passer(){
        if($('passRetry').value.length == $('pass').value.length){
            if($('passRetry').value == $('pass').value){
                $('pass').className = "GreenBox";
                $('passRetry').className = "GreenBox";
                //alert($('passRetry').value);
            }else{
                $('pass').className = "RedBox";
                $('passRetry').className = "RedBox";
            }
        }
    }
</script>
<form action="" method="post">
    <table class="admin_table">
        <tr>
            <td align="right" width="140">Имя</td>
            <td>
			{TextBox id="txtfirstname" value=$Data.Users.firstname class="textBox"}
			{Validator for="txtfirstname" rule="NotNull" message="Введите ФИО пользователя"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">E-mail</td>
            <td>
			{TextBox id="txtEmail" value=$Data.Users.Email class="textBox"}
			{Validator for="txtEmail" rule="NotNull" message="Введите адрес электронной почты"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Секретный вопрос</td>
            <td>
			{TextBox id="txtKeyQuestion" value=$Data.Users.KeyQuestion class="textBox"}
			{Validator for="txtKeyQuestion" rule="NotNull" message="Введите ключевой вопрос"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Ответ</td>
            <td>
			{TextBox id="txtKeyAnswer" value=$Data.Users.KeyAnswer class="textBox"}
			{Validator for="txtKeyAnswer" rule="NotNull" message="Введите ответ"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Название организации</td>
            <td>
			{TextBox id="txtOrgName" value=$Data.Users.OrgName class="textBox"}
			{Validator for="txtOrgName" rule="NotNull" message="Введите ответ"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Область деятельности</td>
            <td>
			{TextBox id="txtSpecialization" value=$Data.Users.Specialization class="textBox"}
			{Validator for="txtSpecialization" rule="NotNull" message="Введите ответ"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Адрес</td>
            <td>
			{TextBox id="txtAddress" value=$Data.Users.Address class="textBox"}
			{Validator for="txtAddress" rule="NotNull" message="Введите ответ"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Должность</td>
            <td>
			{TextBox id="txtPosition" value=$Data.Users.Position class="textBox"}
			{Validator for="txtPosition" rule="NotNull" message="Введите ответ"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Телефон</td>
            <td>
			{TextBox id="txtPhone" value=$Data.Users.Phone class="textBox"}
			{Validator for="txtPhone" rule="NotNull" message="Введите ответ"}
            </td>
        </tr>
        <tr>
            <td align="right" width="140">Сфера интересов</td>
            <td>
			{TextBox id="txtsphereofactivity" value=$Data.Users.sphereofactivity class="textBox"}
			{Validator for="txtsphereofactivity" rule="NotNull" message="Введите ответ"}
            </td>
        </tr>
        {if isset($Data.Users.UserId)}
        <tr>
            <th colspan="2">Если не хотите менять пароль, оставьте поля пустыми</th>
        </tr>
        {/if}
        <tr>
            <th align="right" width="140">Пароль</th>
            <td>
                <input type="password" name="pass" id="pass" class="textBox" value="">
            </td>
        </tr>

        <tr>
            <th align="right" width="140">Повторите пароль</th>
            <td>
                <input type="password" name="passRetry" id="passRetry" class="textBox" onkeyup="passer();">
            </td>
        </tr>


	{if $Data.ErrorMessage != ""}
        <tr>
            <td colspan="2" align="center">
				{$Data.ErrorMessage}
                </th>
        </tr>
	{/if}
    </table>
    {if $Data.Users.Partner}
    <table class="admin_table" style="margin-top:20px;">
        <tr>
            <th align="right" width="140">Скидки для производителей</th>
            <td>
                <table class="admin_table">
			{foreach item=Manufacturer from=$Data.ManufacturersDiscount}
                    <tr><td><label for="man{$Manufacturer.ManufacturerId}">{$Manufacturer.Name}</label></td><td><input id="man{$Manufacturer.ManufacturerId}" name="man[{$Manufacturer.ManufacturerId}]" value={$Manufacturer.Discount}>&nbsp;%		</td></tr>
			{/foreach}
                </table>
            </td>
        </tr>
    </table>
    {/if}
    {if isset($Data.Users.UserId)}
    <input type="hidden" name="UserId" value="{$Data.Users.UserId}" />
    {/if}

    <p align="center">
        <INPUT type="submit" class="button" value="Сохранить" name="handlerBtnUserSave:{$Data.Users.UserId}" title="Сохранить изменения" onclick="return Check();" />&nbsp;&nbsp;&nbsp;
        <!--<INPUT type="submit" class="button" name="" value="Загрузить отчет" title="Загрузить отчет" onclick="Uploader(); return false;"/>&nbsp;&nbsp;&nbsp;-->
        <INPUT type="submit" class="button" name="handlerBtnCancel" value="Отмена" title="Отменить изменения" onclick="return Last();"/>
    </p>
</form>


