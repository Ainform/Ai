<?php /* Smarty version 2.6.12, created on 2011-04-30 12:49:54
         compiled from /home/u184419/dev2.ainform.com/www/module/Karamba/module.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'newsLink', '/home/u184419/dev2.ainform.com/www/module/Karamba/module.tpl', 220, false),)), $this); ?>
<script>
  function windowWidth() {
    var de = document.documentElement;

    return self.offsetWidth || ( de && de.offsetWidth ) || document.body.offsetWidth;
  }
  function windowHeight() {
    var de = document.documentElement;

    return self.innerHeight || ( de && de.clientHeight ) || document.body.clientHeight;
  }
  function getViewportHeight() {
    return ((document.compatMode || isIE) && !isOpera) ? (document.compatMode == 'CSS1Compat') ? document.documentElement.clientHeight : document.body.clientHeight : (document.parentWindow || document.defaultView).innerHeight;
  }

  function getDocumentHeight() {
    return Math.max(document.compatMode != 'CSS1Compat' ? document.body.scrollHeight : document.documentElement.scrollHeight, getViewportHeight());
  }


  $(document).ready(function(){
    $("#all_stuff").css("width",1017*3).css("margin-left",-(1017*3-windowWidth())/2);
    $("#all_stuff_outer").css("width",windowWidth()).css("overflow","hidden");
    $("#all_stuff_left").css("width",(windowWidth()-1017)/2).css("height","1140px");
    $("#all_stuff_right").css("width",(windowWidth()-1017)/2).css("height","1140px");
    $(".zone").live("hover",function(){
      $(this).toggleClass("active");
      $("div.anons_text_ear",this).toggleClass("active");
      $("div.news_text_ear",this).toggleClass("active");
      $("div.report_text_ear",this).toggleClass("active");
    })
    
    $(".uhodi_left_arrow").live("click",function(){
      $("#all_stuff").animate({"margin-left":(windowWidth()-1017)/2},function(){
        //$(".learning_arrow_right").css("visibility","visible");

        temp3=$("div.uhodi:eq(2)");
        $("div.uhodi:eq(2)").remove();
        $("div.uhodi:eq(0)").before(temp3);
        $(".uhodi_left_arrow").css("visibility","hidden");
        $(".uhodi_left_arrow","div.uhodi:eq(1)").css("visibility","visible");
        $(".uhodi_right_arrow").css("visibility","hidden");
        $(".uhodi_right_arrow","div.uhodi:eq(1)").css("visibility","visible");

        $("#all_stuff").css("margin-left",(-(1017*3-windowWidth())/2));
      });      
    })

    $(".uhodi_right_arrow").live("click",function(){
      $("#all_stuff").animate({"margin-left":-(((1017*3-windowWidth())/2)+1017)},function(){
        
        temp3=$("div.uhodi:eq(0)");
        $("div.uhodi:eq(0)").remove();
        $("div.uhodi:eq(1)").after(temp3);

        $(".uhodi_left_arrow").css("visibility","hidden");
        $(".uhodi_left_arrow","div.uhodi:eq(1)").css("visibility","visible");
        $(".uhodi_right_arrow").css("visibility","hidden");
        $(".uhodi_right_arrow","div.uhodi:eq(1)").css("visibility","visible");

        $("#all_stuff").css("margin-left",(-(1017*3-windowWidth())/2));
      })

    })
    $("#lifestyle_gallery_right_arrow").live("click",function(){
      temp=$("img:first","#lifestyle_gallery_content div");
      $("img:first","#lifestyle_gallery_content div").remove();
      $("img:last","#lifestyle_gallery_content div").after(temp);
    })
    $("#lifestyle_gallery_left_arrow").live("click",function(){
      temp=$("img:last","#lifestyle_gallery_content div");
      $("img:last","#lifestyle_gallery_content div").remove();
      $("img:first","#lifestyle_gallery_content div").before(temp);
    })
    /*$("#lifestyle_gallery_right_arrow").live("click",function(){
      alert($("div","#lifestyle_gallery_content").css("margin-left"));
      $("#lifestyle_gallery_content div").css("margin-left",($("div","#lifestyle_gallery_content").css("margin-left").replace("px","")-337)+"px");
    })
    $("#lifestyle_gallery_left_arrow").live("click",function(){
      alert($("div","#lifestyle_gallery_content").css("margin-left"));
      $("#lifestyle_gallery_content div").css("margin-left",($("div","#lifestyle_gallery_content").css("margin-left").replace("px","")-337)+"px");
    })*/


    /*$(".trading_arrow").click(function(){
      $("#all_stuff").animate({"margin-left":-(((1017*3-windowWidth())/2)+1017)});
      $(".learning_arrow_left").css("visibility","visible");
    })
    $(".learning_arrow_right").click(function(){
      $("#all_stuff").animate({"margin-left":-(1017*3-windowWidth())/2});
      $(".learning_arrow_right").css("visibility","hidden");
      $(".learning_arrow_left").css("visibility","hidden");
    })
    $(".learning_arrow_left").click(function(){
      $("#all_stuff").animate({"margin-left":-(1017*3-windowWidth())/2});
      $(".learning_arrow_right").css("visibility","hidden");
      $(".learning_arrow_left").css("visibility","hidden");
    })*/

<?php if (isset ( $this->_tpl_vars['Data']['lifestyle'] )): ?>
$("#all_stuff").animate({"margin-left":(windowWidth()-1017)/2},function(){
        //$(".learning_arrow_right").css("visibility","visible");

        temp3=$("div.uhodi:eq(2)");
        $("div.uhodi:eq(2)").remove();
        $("div.uhodi:eq(0)").before(temp3);
        $(".uhodi_left_arrow").css("visibility","hidden");
        $(".uhodi_left_arrow","div.uhodi:eq(1)").css("visibility","visible");
        $(".uhodi_right_arrow").css("visibility","hidden");
        $(".uhodi_right_arrow","div.uhodi:eq(1)").css("visibility","visible");

        $("#all_stuff").css("margin-left",(-(1017*3-windowWidth())/2));
      })
    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['Data']['trading'] )): ?>
     $("#all_stuff").animate({"margin-left":-(((1017*3-windowWidth())/2)+1017)},function(){

        temp3=$("div.uhodi:eq(0)");
        $("div.uhodi:eq(0)").remove();
        $("div.uhodi:eq(1)").after(temp3);

        $(".uhodi_left_arrow").css("visibility","hidden");
        $(".uhodi_left_arrow","div.uhodi:eq(1)").css("visibility","visible");
        $(".uhodi_right_arrow").css("visibility","hidden");
        $(".uhodi_right_arrow","div.uhodi:eq(1)").css("visibility","visible");

        $("#all_stuff").css("margin-left",(-(1017*3-windowWidth())/2));
      })
    <?php endif; ?>
  })

</script>
<div id="all_stuff_left"></div>
<div id="all_stuff_right"></div>
<div id="all_stuff_outer">
  <div id="all_stuff">    
    <div class="uhodi">
      <div class="headers">
        <div class="uhodi_left_arrow trading_arrow_left" style="visibility:hidden"></div>
        <div class="lifestyle_header"></div>
        <div class="uhodi_right_arrow learning_arrow_right" style="visibility:hidden"></div>
      </div>
      <?php $_from = $this->_tpl_vars['Data']['List']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['NewsList']):
        $this->_foreach['list']['iteration']++;
?>
      <?php if (($this->_foreach['list']['iteration']-1) == 3): ?>
      <div style="clear: both"></div>
      <div id="lifestyle_gallery">
        <div id="lifestyle_gallery_left_arrow" ></div>
        <div id="lifestyle_gallery_right_arrow"></div>
        <div id="lifestyle_gallery_content">
          <div><img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=712&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=713&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=714&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=715&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=716&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=717&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=718&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=719&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=720&width=240&height=170&crop=1" alt="" />
            <img class="lifestyle_gallery_item" onclick="window.location='/life_style/fotogalereja/'" src="/ImageHandler.php?id=721&width=240&height=170&crop=1" alt="" />
          </div>
        </div>
      </div>
      <div class="headers">
        <div class="uhodi_left_arrow trading_arrow_left" style="visibility:hidden"></div>
        <div class="lifestyle_header"></div>
        <div class="uhodi_right_arrow learning_arrow_right" style="visibility:hidden"></div>
      </div>
    </div><div class="uhodi">
      <div class="headers">
        <div class="uhodi_left_arrow lifestyle_arrow" ></div>
        <div class="learning_header"></div>
        <div class="uhodi_right_arrow trading_arrow"></div>
      </div>
      <?php endif; ?>
      <?php if (($this->_foreach['list']['iteration']-1) == 6): ?>

      <div class="headers">
        <div class="uhodi_left_arrow lifestyle_arrow"></div>
        <div class="learning_header"></div>
        <div class="uhodi_right_arrow trading_arrow"></div>
      </div>
    </div><div class="uhodi">
      <div class="headers">
        <div class="uhodi_left_arrow learning_arrow_left" style="visibility:hidden"></div>
        <div class="trading_header"></div>
        <div class="uhodi_right_arrow lifestyle_arrow_right" style="visibility:hidden"></div>
      </div>
      <?php endif; ?>
      <div class="table_news col<?php echo ($this->_foreach['list']['iteration']-1); ?>
">
        <?php $_from = $this->_tpl_vars['NewsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['news'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['news']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['News']):
        $this->_foreach['news']['iteration']++;
?>
        <div class="zone">
          <?php if (($this->_foreach['list']['iteration']-1) == 0): ?>
          <div class="anons_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 1): ?>
          <div class="news_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 2): ?>
          <div class="report_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 3): ?>
          <div class="anons_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 4): ?>
          <div class="news_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 5): ?>
          <div class="report_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 6): ?>
          <div class="anons_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 7): ?>
          <div class="news_text_ear"></div>
          <?php endif; ?>
          <?php if (($this->_foreach['list']['iteration']-1) == 8): ?>
          <div class="report_text_ear"></div>
          <?php endif; ?>
          <div class="news_image">
            <?php if (isset ( $this->_tpl_vars['News']['Image'] )): ?><a href="<?php echo $this->_plugins['function']['newsLink'][0][0]->GetLink(array('id' => $this->_tpl_vars['News']['moduleid']), $this);?>
"><?php echo $this->_tpl_vars['News']['Image']; ?>
</a><?php endif; ?>
          </div>
          <div class="news_text">
            <h3><a href="<?php echo $this->_plugins['function']['newsLink'][0][0]->GetLink(array('id' => $this->_tpl_vars['News']['moduleid']), $this);?>
"><?php echo $this->_tpl_vars['News']['title']; ?>
</a></h3>
            <p><?php echo $this->_tpl_vars['News']['anons']; ?>
</p>
          </div>
        </div>
        <?php endforeach; endif; unset($_from); ?>
      </div>
      <?php endforeach; endif; unset($_from); ?>
      <div class="headers">
        <div class="uhodi_left_arrow learning_arrow_left" style="visibility:hidden"></div>
        <div class="trading_header"></div>
        <div class="uhodi_right_arrow lifestyle_arrow_right" style="visibility:hidden"></div>
      </div>
    </div>
  </div>
  <div style="clear:both"></div>
</div>
</div>