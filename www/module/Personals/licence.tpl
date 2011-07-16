<div class="text">
	{if count($Data.Images) > 0}
    <div class="imageContainer">
        {foreach item=Image from=$Data.Images name="images" key=i}

        <a class="imageContainerLink" style="text-decoration:none" href="{$Image.Path}" rel="lightbox[cat]">
            <img id="imageContainerImg" src="{$Image.Path}&width=220&height=192" width="220" border="0">
        </a>

        {/foreach}
    </div>
	{/if}
	{$Data.Personals.Text}
</div>