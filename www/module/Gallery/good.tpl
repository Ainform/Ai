<script language="JavaScript" type="text/javascript">
  var oldSender = null;

  /**
------------------------------------ Смена изображений у товаров -------------------------------------
	*/
  function chgImg(id, title)
  {
	 var imgLink = $("imageContainerLink");
	 var img = $("imageContainerImg");
	
	 if (!title)
		title = "";
		
	 imgLink.href = imgLink.href.replace(/id=[0-9]+/, "id=" + id);
	
	 var sender = $("chgImgLink" + id);
	 sender.className = "selected";
	
	 if (oldSender != null)
		oldSender.className = "";
		
	 oldSender = sender;
	
	 img.alt = title;
	 img.title = title;
	 img.style.opacity = "0.3";
	 img.style.filter = "Alpha(Opacity=30)";
	
	 var newImgSrc = img.src.replace(/id=[0-9]+/, "id=" + id);
	 var newImg = new Image();
	 newImg.onload = function()
	 {
		img.src = newImg.src;
		img.style.opacity = "1";
		img.style.filter = "Alpha(Opacity=100)";
	 }
	
	 newImg.src = newImgSrc;
  }
</script>

<table width="100%" cellpadding="0" cellspacing="0" class="goods_list text">
  <tr>
	 <td width="10" style="padding: 5px;" valign="top">
	 {if isset($Data.Good.Images)}
	 {foreach item=Image from=$Data.Good.Images}
		<table cellspacing="0" cellpadding="0" border="0" style="background: url(/img/krop200x200.gif) no-repeat;width:200px;height:200px;margin-bottom:10px;">
		  <tbody><tr>
				<td valign="middle" align="center" style="padding:2px;">
				  {if isset($Image)}
				  <a href="{$Image.Path}" rel="shadowbox[goodimages];options={animate: false}" title='{$Image.Title}'>
					 <img src="{$Image.Path}&width=196&height=196&prop=1"  alt="{$Image.Title}" style="border:none;"/>
				  </a>
				  {else}  
					 <img src="/img/noimage198.gif"  alt="" style="border:none;"/> 
				  {/if}
				</td>
			 </tr>
		  </tbody></table>
		  {/foreach}  {else}
			<table cellspacing="0" cellpadding="0" border="0" style="background: url(/img/krop200x200.gif) no-repeat;margin-bottom:10px;width:200px;height:200px;">
<tr>
				<td valign="middle" align="center" style="padding:2px;">
					 <img src="/img/noimage198.gif"  alt="" style="border:none;"/> 
				</td>
			 </tr>
</table>
		  {/if}
	 </td>
	 <td class="good_right">
		<p>Производитель: <span style="padding-left: 0px;">{$Data.Good.ManufacturerName}</span>
		<p>Артикул: <span style="padding-left: 0px;" class="good_code">{$Data.Good.Code}</span>
		{if $Data.Good.TrueCode}<p>Код: <span style="padding-left: 0px;" class="good_code">{$Data.Good.TrueCode}</span>{/if}
		<p>Розничная цена, <u>{$Data.Good.CurrencyName}</u>: <span style="padding-left: 0px;" class="good_price">{$Data.Good.Price}</span>
		<p>Описание:</p>
		<p>{$Data.Good.Abstract}</p>
		{if $Data.Good.Properties!=''}
		<p style="color:#fff!important;text-transform: uppercase;">Технические характеристики:</p>
		<table cellspacing="2" style="border:none!important;margin-bottom:15px;">
		{ foreach item=Property from=$Data.Good.Properties name=prop}
		<tr>
		  <td class="property{if $smarty.foreach.prop.index % 2 == 0}odd{else}even{/if}" style="background:#304151;color:#adadad;text-align:right;padding:4px;padding-right:10px;padding-left:10px;font-weight:bold;">{$Property[0]}</td>
		  {if isset($Property[1])}<td class="property{if $smarty.foreach.prop.index % 2 == 0}odd{else}even{/if}" style="font-weight:bold;padding:4px;padding-right:10px;padding-left:10px;background:#304151;color:#adadad;">{$Property[1]}</td>  {/if}
		  </tr>
		{/foreach}
		</table>
		{/if}
		{if isset($Data.AddedToCart)}
		{$Data.AddedToCart}
		{else}
<input type="submit" name="handlerBtnAdd" class="button" value="Добавить в корзину" style="cursor:pointer;background:url(/img/cart.gif) no-repeat;padding-left:25px;color:#67afff;border:none;"/>
		{/if}
	 </td></tr>
</table>

{include file="../media.tpl"}