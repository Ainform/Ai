<div style="width:980px;margin:0 auto; color:#ccc;" class="user_page_view">
    <table>
        <tr><td colspan="2"><h3>{$Data.Users.OrgName}</h3></td></tr>
        <tr><td style="vertical-align: top">
               <img src="/ImageHandler.php/?src={$Data.Users.Photo}&width=200&prop=1" alt="Фотография" />
               <p>{$Data.Users.firstname } {$Data.Users.patronymic } {$Data.Users.secondname }
               <p style="color:#aaa">{$Data.Users.Position }
            </td>
            <td>
                <table>
                    <tr>
                        <td class="pos_name">Дата рождения</td>
                        <td>
			{$Data.Users.dateofbirdth }
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Страна</td>
                        <td>
			{$Data.Users.country }
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Город</td>
                        <td>
			{$Data.Users.city}
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">E-mail</td>
                        <td>
			{$Data.Users.Email }			
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Рабочий телефон</td>
                        <td>
			{$Data.Users.Phone }
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Факс</td>
                        <td>
			{$Data.Users.fax }
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Мобильный телефон</td>
                        <td>
			{$Data.Users.cellphone }
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Icq</td>
                        <td>
			{$Data.Users.icq }
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Skype</td>
                        <td>
			{$Data.Users.skype }
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="pos_name">Адрес</td>
                        <td>
			{$Data.Users.Address }			
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Индекс</td>
                        <td>
			{$Data.Users.zipcode }
                        </td>
                    </tr>
<tr>
                        <td class="pos_name">Специализация</td>
                        <td>
			{$Data.Users.Specialization }
                        </td>
                    </tr>
                    <tr>
                        <td class="pos_name">Сфера деятельности</td>
                        <td>
			{$Data.Users.sphereofactivity}			
                        </td>
                    </tr>
                </table>
            </td></tr>
    </table>
</div>

