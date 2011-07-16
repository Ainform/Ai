<?php /* Smarty version 2.6.12, created on 2011-07-12 13:55:38
         compiled from index-inside.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $this->_tpl_vars['Title']; ?>
</title>
        <?php echo $this->_tpl_vars['MetaTags']; ?>

        <?php $_from = $this->_tpl_vars['CSSFiles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CSSFile']):
?>
        <link href="<?php echo $this->_tpl_vars['CSSFile']; ?>
" type="text/css" rel="stylesheet" />
        <?php endforeach; endif; unset($_from); ?>
        <link href="/css/style_inside.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="/css/shadowbox.css">
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['Address']; ?>
/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['Address']; ?>
/js/buket.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['Address']; ?>
/js/shadowbox.js"></script>
        <script>
            /*$(document).ready(function(){
                $(".menu_left_link").toggle(function(){
                    $(".id"+$(this).attr("id")).show();
                },
                function(){
                    $(".id"+$(this).attr("id")).hide();
                });
            });*/
        </script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="logo_outside">
                    <div id="logo">
                        <div onclick="window.location='/'"></div>
                    </div>
                </div>
                <div id="top_cart_outside">
                    <div id="top_cart">
                        <p class="top_cart_link"><a href="/cart/">Ваша корзина</a></p>
                        <p class="top_cart_count">В корзине <?php echo $this->_tpl_vars['Cart_count']; ?>
</p>
                    </div>
                </div>
                <div id="menu_top_slider_outside">
                    <div id="menu_top_slider" class="inside">
                        <div id="menu_slider_2" class="menu_slider">
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
                        <div id="menu_slider_4" class="menu_slider">
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
                        <div id="menu_slider_5" class="menu_slider">
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
                    <div id="menu_top" class="inside">
                        <span id="menu_1_link" class="menu_link"><a href="/">Главная</a></span>
                        <span id="menu_2_link" class="menu_link">О компании</span>
                        <span id="menu_3_link" class="menu_link"><a href="/internet-magazin/">Интернет-магазин</a></span>
                        <span id="menu_4_link" class="menu_link">Галерея работ</span>
                        <span id="menu_5_link" class="menu_link">Услуги</span>
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
                <div id="menu_left_inside">
                    <?php if (isset ( $this->_tpl_vars['ParentTitle'] )): ?>
                    <h1><?php echo $this->_tpl_vars['ParentTitle']; ?>
</h1>
                    <?php endif; ?>

                    <?php if (! isset ( $this->_tpl_vars['shop'] )): ?>

                    <?php $_from = $this->_tpl_vars['MenuLeftItems1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['MenuLeftItem']):
?>
                    <?php if (isset ( $this->_tpl_vars['MenuLeftItem']['Selected'] ) && $this->_tpl_vars['MenuLeftItem']['Selected'] == true): ?>
                    <a href="/<?php echo $this->_tpl_vars['MenuLeftItem']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem']['SectionId']; ?>
" class="menu_left_link selected id<?php echo $this->_tpl_vars['MenuLeftItem']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem']['depth']*10; ?>
px;"><?php echo $this->_tpl_vars['MenuLeftItem']['Name']; ?>
</a>
                    <?php else: ?>
                    <a href="/<?php echo $this->_tpl_vars['MenuLeftItem']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem']['SectionId']; ?>
" class="menu_left_link id<?php echo $this->_tpl_vars['MenuLeftItem']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem']['depth']*10; ?>
px;<?php if ($this->_tpl_vars['MenuLeftItem']['depth'] > 1): ?>display:none;<?php endif; ?>"><?php echo $this->_tpl_vars['MenuLeftItem']['Name']; ?>
</a>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?>

                    <?php else: ?>
                    <div class="catalog_search">
 <form action="/search/" method="get">
    <input type="text" name="q" value="<?php if (isset ( $_GET['q'] )):  echo $_GET['q'];  endif; ?>"><input type="submit" value="Найти" />
    </form>
                        </div>
                    <?php $_from = $this->_tpl_vars['MenuLeftItems1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['MenuLeftItem']):
?>

                    <?php if ($this->_tpl_vars['MenuLeftItem']['ParentId'] == -1): ?>

                    <?php if (isset ( $this->_tpl_vars['MenuLeftItem']['hasChild'] ) && $this->_tpl_vars['MenuLeftItem']['hasChild'] == true): ?><div class="plus" id="menu<?php echo $this->_tpl_vars['MenuLeftItem']['SectionId']; ?>
">+</div><?php endif; ?>
                    <?php if (isset ( $this->_tpl_vars['MenuLeftItem']['Selected'] ) && $this->_tpl_vars['MenuLeftItem']['Selected'] == true): ?>
                    <a href="/<?php echo $this->_tpl_vars['MenuLeftItem']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem']['SectionId']; ?>
" class="menu_left_link selected id<?php echo $this->_tpl_vars['MenuLeftItem']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem']['depth']*10; ?>
px;"><?php echo $this->_tpl_vars['MenuLeftItem']['Name']; ?>
</a>
                    <?php else: ?>
                    <a href="/<?php echo $this->_tpl_vars['MenuLeftItem']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem']['SectionId']; ?>
" class="menu_left_link id<?php echo $this->_tpl_vars['MenuLeftItem']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem']['depth']*10; ?>
px;<?php if (false && $this->_tpl_vars['MenuLeftItem']['depth'] > 1): ?>display:none;<?php endif; ?>"><?php echo $this->_tpl_vars['MenuLeftItem']['Name']; ?>
</a>
                    <?php endif; ?>
                    <div class="sub<?php echo $this->_tpl_vars['MenuLeftItem']['SectionId']; ?>
 submenu <?php if (isset ( $this->_tpl_vars['MenuLeftItem']['Selected'] ) && $this->_tpl_vars['MenuLeftItem']['Selected'] == true): ?>active<?php endif; ?>">
                        <?php $_from = $this->_tpl_vars['MenuLeftItems1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['MenuLeftItem1']):
?>
                        <?php if ($this->_tpl_vars['MenuLeftItem1']['ParentId'] == $this->_tpl_vars['MenuLeftItem']['SectionId']): ?>

                        <?php if (isset ( $this->_tpl_vars['MenuLeftItem1']['hasChild'] ) && $this->_tpl_vars['MenuLeftItem1']['hasChild'] == true): ?><div class="plus" id="menu<?php echo $this->_tpl_vars['MenuLeftItem1']['SectionId']; ?>
">+</div><?php endif; ?>
                        <?php if (isset ( $this->_tpl_vars['MenuLeftItem1']['Selected'] ) && $this->_tpl_vars['MenuLeftItem1']['Selected'] == true): ?>
                        <a href="/<?php echo $this->_tpl_vars['MenuLeftItem1']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem1']['SectionId']; ?>
" class="menu_left_link selected id<?php echo $this->_tpl_vars['MenuLeftItem1']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem1']['depth']*10; ?>
px;"><?php echo $this->_tpl_vars['MenuLeftItem1']['Name']; ?>
</a>
                        <?php else: ?>
                        <a href="/<?php echo $this->_tpl_vars['MenuLeftItem1']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem1']['SectionId']; ?>
" class="menu_left_link id<?php echo $this->_tpl_vars['MenuLeftItem1']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem1']['depth']*10; ?>
px;<?php if (false && $this->_tpl_vars['MenuLeftItem1']['depth'] > 1): ?>display:none;<?php endif; ?>"><?php echo $this->_tpl_vars['MenuLeftItem1']['Name']; ?>
</a>
                        <?php endif; ?>
                        <div class="sub<?php echo $this->_tpl_vars['MenuLeftItem1']['SectionId']; ?>
 submenu <?php if (isset ( $this->_tpl_vars['MenuLeftItem1']['Selected'] ) && $this->_tpl_vars['MenuLeftItem1']['Selected'] == true): ?>active<?php endif; ?>">
                            <?php $_from = $this->_tpl_vars['MenuLeftItems1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['MenuLeftItem2']):
?>
                            <?php if ($this->_tpl_vars['MenuLeftItem2']['ParentId'] == $this->_tpl_vars['MenuLeftItem1']['SectionId']): ?>

                            <?php if (isset ( $this->_tpl_vars['MenuLeftItem2']['hasChild'] ) && $this->_tpl_vars['MenuLeftItem2']['hasChild'] == true): ?><div class="plus" id="menu<?php echo $this->_tpl_vars['MenuLeftItem2']['SectionId']; ?>
">+</div><?php endif; ?>
                            <?php if (isset ( $this->_tpl_vars['MenuLeftItem2']['Selected'] ) && $this->_tpl_vars['MenuLeftItem2']['Selected'] == true): ?>
                            <a href="/<?php echo $this->_tpl_vars['MenuLeftItem2']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem2']['SectionId']; ?>
" class="menu_left_link selected id<?php echo $this->_tpl_vars['MenuLeftItem2']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem2']['depth']*10; ?>
px;"><?php echo $this->_tpl_vars['MenuLeftItem2']['Name']; ?>
</a>
                            <?php else: ?>
                            <a href="/<?php echo $this->_tpl_vars['MenuLeftItem2']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem2']['SectionId']; ?>
" class="menu_left_link id<?php echo $this->_tpl_vars['MenuLeftItem2']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem2']['depth']*10; ?>
px;<?php if (false && $this->_tpl_vars['MenuLeftItem2']['depth'] > 1): ?>display:none;<?php endif; ?>"><?php echo $this->_tpl_vars['MenuLeftItem2']['Name']; ?>
</a>
                            <?php endif; ?>
                            <div class="sub<?php echo $this->_tpl_vars['MenuLeftItem2']['SectionId']; ?>
 submenu <?php if (isset ( $this->_tpl_vars['MenuLeftItem2']['Selected'] ) && $this->_tpl_vars['MenuLeftItem2']['Selected'] == true): ?>active<?php endif; ?>">
                                <?php $_from = $this->_tpl_vars['MenuLeftItems1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['MenuLeftItem3']):
?>
                                <?php if ($this->_tpl_vars['MenuLeftItem3']['ParentId'] == $this->_tpl_vars['MenuLeftItem2']['SectionId']): ?>

                                <?php if (isset ( $this->_tpl_vars['MenuLeftItem3']['hasChild'] ) && $this->_tpl_vars['MenuLeftItem3']['hasChild'] == true): ?><div class="plus" id="menu<?php echo $this->_tpl_vars['MenuLeftItem3']['SectionId']; ?>
">+</div><?php endif; ?>
                                <?php if (isset ( $this->_tpl_vars['MenuLeftItem3']['Selected'] ) && $this->_tpl_vars['MenuLeftItem3']['Selected'] == true): ?>
                                <a href="/<?php echo $this->_tpl_vars['MenuLeftItem3']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem3']['SectionId']; ?>
" class="menu_left_link selected id<?php echo $this->_tpl_vars['MenuLeftItem3']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem3']['depth']*10; ?>
px;"><?php echo $this->_tpl_vars['MenuLeftItem3']['Name']; ?>
</a>
                                <?php else: ?>
                                <a href="/<?php echo $this->_tpl_vars['MenuLeftItem3']['Url']; ?>
" id="<?php echo $this->_tpl_vars['MenuLeftItem3']['SectionId']; ?>
" class="menu_left_link id<?php echo $this->_tpl_vars['MenuLeftItem3']['ParentId']; ?>
" style="padding-left:<?php echo $this->_tpl_vars['MenuLeftItem3']['depth']*10; ?>
px;<?php if (false && $this->_tpl_vars['MenuLeftItem3']['depth'] > 1): ?>display:none;<?php endif; ?>"><?php echo $this->_tpl_vars['MenuLeftItem3']['Name']; ?>
</a>
                                <?php endif; ?>
                                <div class="sub<?php echo $this->_tpl_vars['MenuLeftItem3']['SectionId']; ?>
 submenu <?php if (isset ( $this->_tpl_vars['MenuLeftItem3']['Selected'] ) && $this->_tpl_vars['MenuLeftItem3']['Selected'] == true): ?>active<?php endif; ?>">
                                </div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?>

                            </div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>

                        </div>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?>
                    </div>

                    <?php endif; ?>

                    <?php endforeach; endif; unset($_from); ?>
<a class="oplata_i_dostavka menu_left_link <?php if (isset ( $this->_tpl_vars['oplata_i_dostavka'] )): ?>selected<?php endif; ?>" href="/internet-magazin/oplata_i_dostavka/">Оплата и доставка</a>
                    <?php endif; ?>
                </div>
                <div id="inside_content">
                    <?php if (count ( $this->_tpl_vars['Modules'] ) != 0): ?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../modules.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <?php else: ?>
                    <h2>Раздел в разработке</h2>
                    <?php endif; ?>
                </div>
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
        <script type="text/javascript">
            Shadowbox.init({
                overlayColor:"#552e1c"
            });
        </script>
    </body>
</html>