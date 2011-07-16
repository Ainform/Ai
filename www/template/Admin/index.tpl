{strip}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{$Title}</title>
        <link rel="stylesheet" href="/css/admin/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="/css/admin/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="/css/admin/invalid.css" type="text/css" media="screen" />

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script type="text/javascript" src="/js/simpla.jquery.configuration.js"></script>
        <script type="text/javascript" src="/js/main.js"></script>
    </head>
    <body>
        <div id="body-wrapper">
            <div id="sidebar">
                <div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
                    <h1 id="sidebar-title"><a href="#">Ainform</a></h1>
                    <a href="http://ainform.com"><img id="logo" src="/img/admin/New/logo%20(1).png" alt="Ainform" /></a>
                    <div id="profile-links">Привет, <a href="#" title="Edit your profile">Администратор</a><br />
                        <br />
                        <a href="/" title="Вернуться на сайт">Вернуться на сайт</a> | <a href="/admin/SignOut" title="Выйти">Выйти</a>
                    </div>
                    {include file="menu.tpl"}
                </div>
            </div> <!-- End #sidebar -->
            <div id="main-content">
                <h2>Возможные действия</h2>
                <p id="page-intro"></p>
                <ul class="shortcut-buttons-set">
                    {include file="adminmenu.tpl"}
                </ul>
                <div class="clear"></div>
                <div class="content-box">
                    <div class="content-box-header">
                        <h3 style="cursor: s-resize; ">{$Title}</h3>
                        {$BreadCrumbs}
                        <!-- <ul class="content-box-tabs">
                             <li><a href="#tab1" class="default-tab current">Table</a></li>
                             <li><a href="#tab2">Forms</a></li>
                         </ul>-->
                        <div class="clear"></div>
                    </div>
                    <div class="content-box-content">
                        <div class="tab-content default-tab" id="tab1" style="display: block; ">
                            {include file="../modules.tpl"}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div id="footer">
                    <small>© Copyright 2011 <a href="http://ainform.com">Ainform</a> | Powered by Ai | <a href="#">Top</a>
						<span> | Время генерации: {php}echo sprintf("%.5f", microtime(true) - START_TIME);{/php} сек</span>
					</small>
                </div>
            </div>
        </div>
    </body>
</html>
{/strip}