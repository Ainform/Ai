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

    <table cellpadding="5" cellspacing="0" border="0" class="user_table text" style="widthauto">
<tr><td colspan="3">Поиск пользователей</td></tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtFIO">Имя</label><span style="color: #FFF;">*</span></td>
            <td width="250">{TextBox id="txtfirstname" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;"></td>
        </tr>
        <tr>
            <td class="registration_compulsory" align="right" width="220"><label for="txtFIO">Фамилия</label><span style="color: #FFF;">*</span></td>
            <td width="250">{TextBox id="txtsecondname" maxlength="100" class="text_box_registration" value=""}</td>
            <td style="color: #FFF;"></td>
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
            <td style="color: #FFF;"></td>
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
            <td align="right">&nbsp;</td>
            <td style="padding-top:15px;">
                <input type="hidden" name="search_result" />
                <input type="submit" name="handlerSearch" value="Найти" class="user_search_button" /></td>
            <td style="color: #FFF;">&nbsp;</td>
        </tr>
    </table>
    {/if}
</div>