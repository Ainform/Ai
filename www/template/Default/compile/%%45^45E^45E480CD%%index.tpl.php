<?php /* Smarty version 2.6.12, created on 2011-07-12 13:13:22
         compiled from index.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $this->_tpl_vars['Title']; ?>
</title>
        <?php echo $this->_tpl_vars['MetaTags']; ?>

        <?php $_from = $this->_tpl_vars['CSSFiles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CSSFile']):
?>
        <link href="<?php echo $this->_tpl_vars['CSSFile']; ?>
" type="text/css" rel="stylesheet" />
        <?php endforeach; endif; unset($_from); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['Address']; ?>
/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['Address']; ?>
/js/buket.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['Address']; ?>
/js/swfobject.js"></script>
        <script type="text/javascript">
            var flashvars = {
                name1: "hello",
                name2: "world",
                name3: "foobar"
            };
            var params = {
                menu: "false",
                wmode: "transparent"
            };
            var attributes = {
                id: "myDynamicContent",
                name: "myDynamicContent",
                style:"left:220px;top:-250px;position:absolute;"
            };
            var attributes2 = {
                id: "myDynamicContent",
                name: "myDynamicContent",
                style:"left:660px;top:-250px;position:absolute;"
            };

            swfobject.embedSWF("/css/images/firefly.swf", "flash", "140", "140", "9.0.0","expressInstall.swf", flashvars, params, attributes);
            swfobject.embedSWF("/css/images/firefly.swf", "flash2", "140", "140", "9.0.0","expressInstall.swf", flashvars, params, attributes2);
        </script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="menu_top_slider_outside">
                    <div id="menu_top_slider">
                        <div id="menu_slider_1" class="menu_slider">
                            <?php $_from = $this->_tpl_vars['MenuSubItems1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menuitems'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menuitems']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['MenuItem']):
        $this->_foreach['menuitems']['iteration']++;
?>
                            <a href="/<?php echo $this->_tpl_vars['MenuItem']['parent_alias']; ?>
/<?php echo $this->_tpl_vars['MenuItem']['Alias']; ?>
/" class="menu_sub_item"><?php echo $this->_tpl_vars['MenuItem']['Name']; ?>
</a>
                            <?php endforeach; endif; unset($_from); ?>
                        </div>
                        <!-- <div id="menu_slider_2" class="menu_slider">
                             <?php $_from = $this->_tpl_vars['MenuSubItems2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menuitems'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menuitems']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['MenuItem']):
        $this->_foreach['menuitems']['iteration']++;
?>
                             <a href="/<?php echo $this->_tpl_vars['MenuItem']['parent_alias']; ?>
/<?php echo $this->_tpl_vars['MenuItem']['Alias']; ?>
/" class="menu_sub_item"><?php echo $this->_tpl_vars['MenuItem']['Name']; ?>
</a>
                             <?php endforeach; endif; unset($_from); ?>
                         </div>-->
                        <div id="menu_slider_3" class="menu_slider">
                            <?php $_from = $this->_tpl_vars['MenuSubItems3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menuitems'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menuitems']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['MenuItem']):
        $this->_foreach['menuitems']['iteration']++;
?>
                            <a href="/<?php echo $this->_tpl_vars['MenuItem']['parent_alias']; ?>
/<?php echo $this->_tpl_vars['MenuItem']['Alias']; ?>
/" class="menu_sub_item"><?php echo $this->_tpl_vars['MenuItem']['Name']; ?>
</a>
                            <?php endforeach; endif; unset($_from); ?>
                        </div>
                        <div id="menu_slider_4" class="menu_slider">
                            <?php $_from = $this->_tpl_vars['MenuSubItems4']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menuitems'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menuitems']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['MenuItem']):
        $this->_foreach['menuitems']['iteration']++;
?>
                            <a href="/<?php echo $this->_tpl_vars['MenuItem']['parent_alias']; ?>
/<?php echo $this->_tpl_vars['MenuItem']['Alias']; ?>
/" class="menu_sub_item"><?php echo $this->_tpl_vars['MenuItem']['Name']; ?>
</a>
                            <?php endforeach; endif; unset($_from); ?>
                        </div>
                    </div>
                </div>
                <div id="menu_top_outside">
                    <div id="menu_top">
                        <div id="flash" style="position:absolute;margin-left:200px;margin-top:-200px;"></div>
                        <div id="flash2" style="position:absolute;margin-left:200px;margin-top:-200px;"></div>
                        <span id="menu_1_link" class="menu_link">О компании</span>
                        <span id="menu_2_link" class="menu_link"><a href="/internet-magazin/">Интернет-магазин</a></span>
                        <span id="menu_3_link" class="menu_link">Галерея работ</span>
                        <span id="menu_4_link" class="menu_link">Услуги</span>
<!--
                        <?php $_from = $this->_tpl_vars['MenuItems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menuitems'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menuitems']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['MenuItem']):
        $this->_foreach['menuitems']['iteration']++;
?>
                        <span id="menu_<?php echo ($this->_foreach['menuitems']['iteration']-1)+1; ?>
_link" class="menu_link"><?php echo $this->_tpl_vars['MenuItem']['Name']; ?>
</span>
                        <?php endforeach; endif; unset($_from); ?>

-->
                    </div>
                </div>
            </div>
            <div id="content">
                <div id="about"></div>
                <a href="/internet-magazin/" id="gallery"></a>
                <div id="proposal"></div>
                <div id="contacts"></div>
                <div style="clear:both"></div>
                <div id="main_left_menu">
                    <p><a href="/uslugi/svadebnaja_floristika/">Свадебная флористика</a></p>
                    <p><a href="/uslugi/korporativnym_klientam/">Корпоративным клиентам</a></p>
                    <p><a href="/uslugi/oformlenie_interjerov/">Оформление интерьеров</a></p>
                    <p><a href="/uslugi/master-klassy/">Мастер-классы</a></p>
                    <p><a href="/uslugi/ekskljuzivnye_napravlenija/">Эксклюзивные направления</a></p>
                </div>
                <div id="center_place">
                    <?php if (count ( $this->_tpl_vars['Modules'] ) != 0): ?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../modules.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <?php else: ?>
                    <h2>Мы дарим вам Букет хорошего настроения!</h2>
                    <?php endif; ?>
                </div>
                <div id="mashinka"><p><a href="/uslugi/dostavka_tsvetov/">Доставка букетов</a></p></div>
                <div style="clear:both"></div>
            </div>
            <div id="clear"></div>
            <div id="rasporka"></div>
        </div>
        <div id="footer">
            <div id="footer_inside">
                <div id="copyright">&copy; Букет 2010</div>
                <div id="counters">
                    <!-- Yandex.Metrika counter -->
<div style="display:none;"><script type="text/javascript">
(function(w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter5960620 = new Ya.Metrika(5960620);
             yaCounter5960620.clickmap(true);
             yaCounter5960620.trackLinks(true);

        } catch(e) { }
    });
})(window, 'yandex_metrika_callbacks');
</script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/5960620" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
                </div>
                <div id="address">Мустая Карима, 41. тел. 273-55-66.</div>
                <div id="welcome">Мы дарим вам Букет хорошего настроения!</div>
                <div id="ai"><a href="http://ainform.com">Разработка сайта</a></div>
            </div>
        </div>

    </body>
</html>