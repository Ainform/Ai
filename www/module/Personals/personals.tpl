<div class="text">
    <table cellspacing="0" cellpadding="0" width="100%" class="table">
        <tr valign="top"><td width="120">
	{if count($Data.Images) > 0}
                {foreach item=Image from=$Data.Images name="images" key=i}
                <a class="imageContainerLink" style="text-decoration:none" href="{$Image.Path}" rel="shadowbox[Images];options={counterType:'skip',continuous:true,animSequence:'sync',handleOversize:'resize'}">
                    <img id="imageContainerImg" src="{$Image.Path}&width=220" width="220" border="0">
                </a>
                {/foreach}
				{else}
				<img alt="" src="/img/no_photo.jpg" width="135px" />
	{/if}
            </td>
            <td style="padding-left: 10px;">

                <p style="font-weight:bold;">{$Data.Personals.Position}
                <p>{$Data.Personals.Anons}

            </td></tr></table>
</div>