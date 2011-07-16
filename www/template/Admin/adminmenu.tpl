{foreach item=AdminMenuItem from=$AdminMenu}
<li><a class="shortcut-button" href="{$AdminMenuItem.url}"><span>
                                <img src="{$AdminMenuItem.icon}" alt="{$AdminMenuItem.title}"/>
                                <br />{$AdminMenuItem.title}
                            </span>
                        </a>
                    </li>
{/foreach}