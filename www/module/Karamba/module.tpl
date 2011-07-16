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

{if isset($Data.lifestyle)}
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
    {/if}
    {if isset($Data.trading)}
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
    {/if}
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
      {foreach item=NewsList from=$Data.List name=list}
      {if $smarty.foreach.list.index==3}
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
      {/if}
      {if $smarty.foreach.list.index==6}

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
      {/if}
      <div class="table_news col{$smarty.foreach.list.index}">
        {foreach item=News from=$NewsList name=news}
        <div class="zone">
          {if $smarty.foreach.list.index==0}
          <div class="anons_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==1}
          <div class="news_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==2}
          <div class="report_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==3}
          <div class="anons_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==4}
          <div class="news_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==5}
          <div class="report_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==6}
          <div class="anons_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==7}
          <div class="news_text_ear"></div>
          {/if}
          {if $smarty.foreach.list.index==8}
          <div class="report_text_ear"></div>
          {/if}
          <div class="news_image">
            {if isset($News.Image)}<a href="{newsLink id=$News.moduleid}">{$News.Image}</a>{/if}
          </div>
          <div class="news_text">
            <h3><a href="{newsLink id=$News.moduleid}">{$News.title}</a></h3>
            <p>{$News.anons}</p>
          </div>
        </div>
        {/foreach}
      </div>
      {/foreach}
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
{*$Data.Pager*}
