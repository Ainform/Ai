<div id="registration_div" style="width:980px;margin:0 auto;color:#fff;font-style: italic;">
    <p><a href="/polzovateli/?search=1">Поиск пользователей</a>
    {if count($Data.Orgs)==0}
        <p>C введёнными данными ни одного пользователя не найдено.</p>
    {else}
        {foreach item=Org from=$Data.Orgs key=name}
        <div style="clear:both"></div>
        <h2>{$name}</h2>
        <div style="clear:both"></div>
        {foreach item=User from=$Org}
        <div class="user_list_item">
        <img src="/ImageHandler.php?src={$User.Photo}&width=200&height=300&crop=1" alt="" />
        <p><a href="/polzovateli/?userId={$User.UserId}">{$User.firstname} {$User.secondname}</a>
        <p style="color:#ccc">{$User.Position}
        </div>
        {/foreach}
        {/foreach}
    {/if}

    <div style="clear:both"></div>
    {$Data.Pager}
    <div style="clear:both"></div>
    {if count($Data.UsersBirdth)>0}
    <p style="text-align: left">Сегодня день рождения:</p>
    {foreach item=Usertemp from=$Data.UsersBirdth}
    <div class="user_list_item">
        <img src="/ImageHandler.php?src={$Usertemp.Photo}&width=200&height=300&crop=1" alt="" />
        <p><a href="/polzovateli/?userId={$Usertemp.UserId}">{$Usertemp.firstname} {$Usertemp.secondname}</a>
        <p style="color:#ccc">{$Usertemp.OrgName}
    </div>
    {/foreach}
    {/if}
    <div style="clear:both"></div>
</div>