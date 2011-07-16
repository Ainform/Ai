<div id="registration_div" style="width:980px;margin:0 auto;color:#fff;font-style: italic;">
    {if isset($Data.request)}
    <div style="width:100%" class="text">{$Data.request}</div>

    {else}
    <style>
        .validateError{
            text-indent:5px!important;
            color:#7D2325;
            font-size:8pt;
        }
    </style>
    <table cellpadding="0" cellspacing="0" border="0" class=""><tr><td style="padding:0;">
                Заполните приведённую ниже регистрационную форму.
                После чего на указанный вами адрес электронной почты будет отправлено сообщение, содержащее ссылку для активации вашей учётной записи.</td></tr></table>
    <h2 class="registration_header">Регистрация</h2>
    <table cellpadding="5" cellspacing="0" border="0" class="user_table text" style="widthauto">

        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtFIO">Имя</label><span style="color: #FFF;">*</span></td>
            <td width="250">{TextBox id="txtfirstname" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">{Validator for="txtfirstname" rule="NotNull" message="Введите имя"}</td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtFIO">Фамилия</label><span style="color: #FFF;">*</span></td>
            <td width="250">{TextBox id="txtsecondname" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">{Validator for="txtsecondname" rule="NotNull" message="Введите фамилию"}</td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtFIO">Отчество</label></td>
            <td width="250">{TextBox id="txtpatronymic" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;"></td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtdateofbirdth">Дата рождения</label></td>
            <td width="250">{TextBox id="txtdateofbirdth" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;"></td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtcountry">Страна</label></td>
            <td width="250">{TextBox id="txtcountry" class="text_box_registration" values=""}</td>
            <td style="color: #FFF;"></td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtcity">Город</label></td>
            <td width="250">{TextBox id="txtcity" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;"></td>
        </tr>
	
        <tr>
            <td align="right" width="220"><label for="txtOrgName">Название организации</label></td>
            <td width="250">{TextBox id="txtOrgName" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtPosition">Должность</label></td>
            <td>{TextBox id="txtPosition" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right"><label for="txtEmail">Email</label><span style="color: #FFF;">*</span></td>
            <td>{TextBox id="txtEmail" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">{Validator for="txtEmail" rule="Email" message="Введите email"}</td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right"><label for="txtPass">Пароль</label><span style="color: #FFF;">*</span></td>
            <td>{TextBox id="txtPass" type="password"  maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">{Validator for="txtPass" rule="NotNull" message="Введите пароль"}</td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right"><label for="txtPass2">Повторите пароль</label><span style="color: #FFF;">*</span></td>
            <td>{TextBox id="txtPass2" type="password"  maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">{Validator for="txtPass2" rule="NotNull" message="Введите пароль"}</td>
        </tr>
        <tr>
            <td align="right"><label for="txtJobPhone">Рабочий телефон</label></td>
            <td>{TextBox id="txtJobPhone" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtfax">Факс</label></td>
            <td>{TextBox id="txtfax" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtcellphone">мобильный телефон</label></td>
            <td>{TextBox id="txtcellphone" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtskype">Skype</label></td>
            <td>{TextBox id="txtskype" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txticq">ICQ</label></td>
            <td>{TextBox id="txticq" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtzipcode">Почтовый индекс</label></td>
            <td>{TextBox id="txtzipcode" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtAddress">Почтовый адрес</label></td>
            <td>{TextBox id="txtAddress" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtSpecialization">Специализация</label></td>
            <td>{DropDownList id="txtSpecialization" values=$Data.Specialization class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="txtsphereofactivity">Сфера деятельности</label></td>
            <td>{DropDownList id="txtsphereofactivity" values=$Data.sphereofactivity class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
        	<tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txthobby">Сфера личных интересов (хобби)</label></td>
            <td width="250">{TextBox id="txthobby" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;"></td>
        </tr>
        <tr><td colspan="3"><h2 class="registration_header">Информация для восстановления пароля</h2></td></tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtKeyQuestion">Ключевой вопрос<span style="color: #FFF;">*</span></label></td>
            <td width="250">{TextBox id="txtKeyQuestion" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">{Validator for="txtKeyQuestion" rule="NotNull" message="Введите ключевой вопрос"}</td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtKeyAnswer">Ключевой ответ<span style="color: #FFF;">*</span></label></td>
            <td width="250">{TextBox id="txtKeyAnswer" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;">{Validator for="txtKeyAnswer" rule="NotNull" message="Введите ключевой ответ"}</td>
        </tr>
        <tr>
            <td align="right">&nbsp;</td>
            <td style="padding-top:15px;"><input type="submit" name="handlerSend" value="Зарегистрироваться »" class="registration_button" /></td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
    </table>


    <!--<table>
        <tr>
            <td id="captcha_overview">Введите текст с картинки<span style="color: #FFF;">*</span></td>
            <td>
            <table width=100% cellpadding=0 cellspacing=0><tr><td width=33% style="padding:0px">
            <img alt="" style="border:1px solid #7D2325" width=160 height=36 id="captcha_image"src="/util/captcha.php?{$Data.session_name}={$Data.session_id}"></td>
		<td width=34% style="padding-top:0;padding-bottom:0;">
            <input style="width:100%;height:38px;font-size:26px" id="captcha_text" type="text" name="keystring"></td>
		<td width=33% style="padding:0px;text-align:right;">
            <input type="submit" id="send_message" style="width:160px;height:38px" name="handlerSend" value="Зарегистрироваться" class="text"></td>
            </tr>
            </table></td>
		<td><span style="color: #FFF;">{Validator for="keystring" rule="Captcha" message="Текст неверный или не введён"}</span></td>
    </tr>

    </table>-->

    <p style="padding:10px;"><span style="color: #FFF;">*</span> Поля обязательные для заполнения.</p>
    {/if}
</div>