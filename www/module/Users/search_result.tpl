<div id="registration_div" style="width:980px;margin:0 auto;color:#fff;font-style: italic;">
    {if count($Data.Users)==0}
    <p>C введёнными данными ни одного пользователя не найдено.</p>
    {else}
    {foreach item=User from=$Data.Users}
    <div class="user_list_item">
        <img src="/ImageHandler.php?src={$User.Photo}&width=200$height=350&prop=1" alt="" />
        <p><a href="/polzovateli/?userId={$User.UserId}">{$User.firstname} {$User.secondname}</a>
        <p style="color:#ccc">{$User.OrgName}
    </div>
    {/foreach}
    {/if}
</div>