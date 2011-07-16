<ul id="main-nav">
    {foreach item=MenuItem from=$Menu}
    {if $MenuItem->IsSeparator()}
    <br />
    {else}
    {if $MenuItem->Visible!='' && $MenuItem->Level==1}
    <li>
        <a href="{$MenuItem->Url}" class="level{$MenuItem->Level}
           {if $MenuItem->thisIsModule}thisismodule {/if}
           {if $MenuItem->Selected}current{/if}
           {if $MenuItem->HasSelectedChild} current{/if}
           nav-top-item nav {if $MenuItem->HasChild()}childed{else}no-submenu{/if}">
            {$MenuItem->Title}</a>
        {if $MenuItem->HasChild()}
        <ul class=" {if $MenuItem->HasSelectedChild} current{/if}">
            {foreach item=SubItem from=$MenuItem->Childs}
            {if $SubItem->Visible != false && $SubItem->Title != "" && $SubItem->HideInMenu == 0}
            <li>
                <a href="{$SubItem->Url}" title='{if $SubItem->thisIsModule}Модуль {else}Подраздел {/if}"{$SubItem->Title}"' class="level{$SubItem->Level}
                   nav
                   {if $SubItem->thisIsModule}thisismodule {/if}
                   {if $SubItem->Selected}current{/if}
                   {if $SubItem->HasSelectedChild} current{/if}
                   {if $SubItem->HasChild()}childed subchilded {else}no-submenu{/if}" title="{$SubItem->Title}">{$SubItem->Title}</a>
                {if $SubItem->HasChild()}
                <ul  class=" {if $SubItem->HasSelectedChild} current{/if}">
                    {foreach item=SubSubItem from=$SubItem->Childs}
                    {if $SubSubItem->Visible != false && $SubSubItem->Title != "" && $SubSubItem->HideInMenu == 0}
                    <li>
                        <a href="{$SubSubItem->Url}" title='{if $SubSubItem->thisIsModule}Модуль {else}Подраздел {/if}"{$SubSubItem->Title}"' class="level{$SubSubItem->Level} nav
                           {if $SubSubItem->thisIsModule}thisismodule {/if}
                           {if $SubSubItem->Selected}current{/if}
                           {if $SubSubItem->HasSelectedChild} current{/if}
                           {if $SubSubItem->HasChild()}childed subsubchilded{else}no-submenu{/if}" title="{$SubSubItem->Title}">{$SubSubItem->Title}</a>

                            {if $SubSubItem->HasChild()}
                <ul  class=" {if $SubSubItem->HasSelectedChild} current{/if}">
                    {foreach item=SubSubSubItem from=$SubSubItem->Childs}
                    {if $SubSubSubItem->Visible != false && $SubSubSubItem->Title != "" && $SubSubSubItem->HideInMenu == 0}
                    <li>
                        <a href="{$SubSubSubItem->Url}" title='{if $SubSubSubItem->thisIsModule}Модуль {else}Подраздел {/if}"{$SubSubSubItem->Title}"' class="level{$SubSubSubItem->Level} nav
                           {if $SubSubSubItem->thisIsModule}thisismodule {/if}
                           {if $SubSubSubItem->Selected}current{/if}
                           {if $SubSubSubItem->HasSelectedChild} current{/if}
                           {if $SubSubSubItem->HasChild()}childed{else}no-submenu{/if}" title="{$SubSubSubItem->Title}">{$SubSubSubItem->Title}</a>
                    </li>
                    {/if}
                    {/foreach}
                </ul>
                {/if}

                    </li>
                    {/if}
                    {/foreach}
                </ul>
                {/if}

            </li>
            {/if}
            {/foreach}
        </ul>
        {/if}
    </li>
    {/if}
    {/if}
    {/foreach}
</ul>