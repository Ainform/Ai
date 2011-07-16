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
<div style="width:980px;margin:0 auto; color:#ccc;">
    <form action="" method="post">
        <table><tr><td style="vertical-align: top">
                    <img src="/ImageHandler.php/?src={$Data.Users.Photo}&width=200&prop=1" alt="Фотография" />
                </td><td>
        <table class="admin_table">
            <tr>
                <td align="right" width="140">Имя</td>
                <td>
			{TextBox id="txtfirstname" value=$Data.Users.firstname class="textBox"}
			
                </td>
                <td>{Validator for="txtfirstname" rule="NotNull" message="Введите имя"}</td>
            </tr>
            <tr>
                <td align="right" width="140">Фамилия</td>
                <td>
			{TextBox id="txtsecondname" value=$Data.Users.secondname class="textBox"}
			
                </td>
                <td>{Validator for="txtsecondname" rule="NotNull" message="Введите фамилию"}</td>
            </tr>
            <tr>
                <td align="right" width="140">Отчество</td>
                <td>
			{TextBox id="txtpatronymic" value=$Data.Users.patronymic class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Дата рождения</td>
                <td>
			{TextBox id="txtdateofbirdth" value=$Data.Users.dateofbirdth class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">E-mail</td>
                <td>
			{TextBox id="txtEmail" value=$Data.Users.Email class="textBox"}
			
                </td>
                <td>{Validator for="txtEmail" rule="NotNull" message="Введите адрес электронной почты"}</td>
            </tr>
            <tr>
                <td align="right" width="140">Страна</td>
                <td>
			{TextBox id="txtcountry" value=$Data.Users.country class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Город</td>
                <td>
			{TextBox id="txtcity" value=$Data.Users.city class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Секретный вопрос</td>
                <td>
			{TextBox id="txtKeyQuestion" value=$Data.Users.KeyQuestion class="textBox"}
			
                </td>
                <td>{Validator for="txtKeyQuestion" rule="NotNull" message="Введите ключевой вопрос"}</td>
            </tr>
            <tr>
                <td align="right" width="140">Ответ</td>
                <td>
			{TextBox id="txtKeyAnswer" value=$Data.Users.KeyAnswer class="textBox"}
			
                </td>
                <td>{Validator for="txtKeyAnswer" rule="NotNull" message="Введите ответ"}</td>
            </tr>
            <tr>
                <td align="right" width="140">Название организации</td>
                <td>
			{TextBox id="txtOrgName" value=$Data.Users.OrgName class="textBox"}			
                </td>
            </tr>
            <tr>
                <td align="right" width="140">Специализация</td>
                <td>
			{TextBox id="txtSpecialization" value=$Data.Users.Specialization class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Сфера деятельности</td>
                <td>
			{TextBox id="txtsphereofactivity" value=$Data.Users.sphereofactivity class="textBox"}
                </td>
            </tr>
            <tr>
                <td align="right" width="140">Адрес</td>
                <td>
			{TextBox id="txtAddress" value=$Data.Users.Address class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Почтовый индекс</td>
                <td>
			{TextBox id="txtzipcode" value=$Data.Users.zipcode class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Должность</td>
                <td>
			{TextBox id="txtPosition" value=$Data.Users.Position class="textBox"}
			
                </td>
                <td>{Validator for="txtPosition" rule="NotNull" message="Введите ответ"}</td>
            </tr>
            <tr>
                <td align="right" width="140">Рабочий телефон</td>
                <td>
			{TextBox id="txtJobPhone" value=$Data.Users.Phone class="textBox"}
			
                </td>
                <td>{Validator for="txtJobPhone" rule="NotNull" message="Введите ответ"}</td>
            </tr>
            <tr>
                <td align="right" width="140">Мобильный телефон</td>
                <td>
			{TextBox id="txtcellphone" value=$Data.Users.cellphone class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Факс</td>
                <td>
			{TextBox id="txtfax" value=$Data.Users.fax class="textBox"}
                </td>
            </tr>
            <tr>
                <td align="right" width="140">Skype</td>
                <td>
			{TextBox id="txtskype" value=$Data.Users.skype class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Icq</td>
                <td>
			{TextBox id="txticq" value=$Data.Users.icq class="textBox"}
                </td>
                <td></td>
            </tr>
            <tr>
                <td align="right" width="140">Фотография</td>
                <td style="color:#ccc;"><input type="file" name="photo" style="color:#ccc;"/></td>
            </tr>
            {if isset($Data.Users.UserId)}
            <tr>
                <th colspan="2">Если не хотите менять пароль, оставьте поля пустыми</th>
            </tr>
            {/if}
            <tr>
                <td align="right" width="140">Пароль</td>
                <td>
                    <input type="password" name="pass" id="pass" class="textBox" value="">
                </td>
            </tr>

            <tr>
                <td align="right" width="140">Повторите пароль</td>
                <td>
                    <input type="password" name="passRetry" id="passRetry" class="textBox" onkeyup="passer();">
                </td>
            </tr>


	{if $Data.ErrorMessage != ""}
            <tr>
                <td colspan="2" align="center">
				{$Data.ErrorMessage}
                    </td>
            </tr>
	{/if}

        </table>
        {if isset($Data.Users.UserId)}
        <input type="hidden" name="UserId" value="{$Data.Users.UserId}" />
        {/if}

        <p align="center">
            <INPUT type="submit" class="button" value="Сохранить" name="handlerBtnUserSave:{$Data.Users.UserId}" title="Сохранить изменения" onclick="return Check();" />&nbsp;&nbsp;&nbsp;
        </p>
        </td></tr></table>
    </form>
</div>

