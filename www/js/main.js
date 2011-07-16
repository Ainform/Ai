function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    alert(out);

    // or, if you wanted to avoid alerts...

    //var pre = document.createElement('pre');
    //pre.innerHTML = out;
    //document.body.appendChild(pre)
}

/**
	AJAX Update панелька
 */
var AjaxPanel = function(id, moduleId)
{
    this.id = id;

    var obj = $("ajax"+id);
    obj.moduleId = moduleId;
    obj.id = "ajax"+id;

    if (!obj)
        return;

    // определяем потенциальных субмиттеров
    obj.imageSubmits = obj.find('[type="image"]');
    obj.inputSubmits =obj.find('[type="submit"]');
    obj.checkboxSubmits = obj.find('[type="checkbox"]');

    // сканим данные формы
    obj.inputs = obj.find('input[type!="image"][type!="submit"][type!="checkbox"]');
    obj.selects = obj.find('select');

    if (obj.imageSubmits != null && obj.imageSubmits != undefined)
        obj.imageSubmits.each(
            function(item)
            {
                item.ajaxPanel = obj;
                item.onAjaxClick = item.onclick;
                item.onclick = null;
                item.click(clicker);
            }
            );

    if (obj.checkboxSubmits != null && obj.checkboxSubmits != undefined)
        obj.checkboxSubmits.each(
            function(item)
            {
                item.ajaxPanel = obj;
                item.onAjaxClick = item.onclick;
                item.onclick = null;
                item.click(clicker);
            }
            );

    if (obj.inputSubmits != null && obj.inputSubmits != undefined)
        obj.inputSubmits.each(
            function(item)
            {
                item.ajaxPanel = obj;
                item.onAjaxClick = item.onclick;
                item.click(clicker);
            }
            );
}

// обработчик нажатия на субмиттер
function clicker(event)
{
    //alert("test");
    // останавливаем дальнейшую обработку событий для избежания submit'а всей страницы
    Event.stop(event);

    // определяем, какой элемент вызвал сабмит
    var elem = Event.element(event);

    // если у сабмита был onclick, то вызываем вначале его
    if (elem.onAjaxClick != null)
    {
        // отмена действия
        if (elem.onAjaxClick() == false)
            return;
    }

    var handler = elem.name;
    var obj = elem;
    var panel = obj.ajaxPanel;

    var post = handler+'='+handler+'&ajaxHandler=1&ajaxId='+panel.id+'&moduleId='+panel.moduleId;

    // сериализация данных формы
    if (panel.selects)
        panel.selects.each(
            function(item)
            {
                post += "&"+Form.Element.serialize(item);
            }
            )

    // сериализация данных формы
    if (panel.inputs)
        panel.inputs.each(
            function(item)
            {
                //alert(item);
                post += "&"+Form.Element.serialize(item);
            }
            )

    // AJAX POST запрос
    new Ajax.Request(document.location.href,
    {
        postBody: post,
        onSuccess: function(transport, json)
        {
            obj.ajaxPanel.innerHTML = transport.responseText;
            new AjaxPanel(obj.ajaxPanel.id.substring(4), obj.ajaxPanel.moduleId)
        }
    });
}
var AjaxTimeout = null;
function showAjaxWait()
{
    var AjaxWaiter = document.getElementById("wait");
    AjaxWaiter.style.display = "block";
    AjaxWaiter.style.top = Math.round((document.body.offsetHeight/2)-(AjaxWaiter.offsetHeight/2)+document.body.scrollTop)+'px';
    AjaxWaiter.style.left = Math.round((document.body.offsetWidth/2)-(AjaxWaiter.offsetWidth/2))+"px";
}
function hideAjaxWait()
{
    document.getElementById("wait").style.display = "none";
}

/*Ajax.Responders.register({
    onCreate: function()
    {
        showAjaxWait();
    },
    onComplete: function()
    {
        hideAjaxWait();
    }
})*/

/**
	Валидация данных
 */
// список валидаторов
var validators = new Array();

var Validator = function(id, rules)
{
    this.id = id;
    this.field = $(id);
    this.rules = rules.split(" ");

    // устанавливаем свои обработчики событий
    Event.observe(this.field, "blur", onValidate);
    //	Event.observe(this.field, "keydown", onValidate);
    //	Event.observe(this.field, "keyup", onValidate);

    // сохраняем валидатор в список валидаторов
    validators[id] = this;
}

// валидация
Validator.prototype.DoValidate = function()
{
    var obj = this;
    var validated = true;
    this.rules.each
    (
        function(item)
        {
            checkFunc = "obj.Rule" + item + "()";
            validated &= eval(checkFunc);
        }
        )

    var errorBlock = $(obj.id + "validator");

    if (!validated)
        errorBlock.style.display = "block";
    else
        errorBlock.style.display = "none";

    return validated;
}

/**
	Правила валидатора
 */
Validator.prototype.RuleNotNull = function()
{
    var value = $F(this.field);
    return !(/^\s*$/.test(value));
}

Validator.prototype.RuleInt = function()
{
    var value = $F(this.field);
    return /^[0-9]*$/.test(value);
}

Validator.prototype.RuleFloat = function()
{
    var value = $F(this.field);
    return /^[0-9]+((\.|,)[0-9]+)?$/.test(value);
}

Validator.prototype.RuleLatin = function()
{
    var value = $F(this.field);
    return /^[0-9A-z_\-]+$/.test(value);
}

function onValidate(event)
{
    var element = Event.element(event);

    var result = validators[element.id].DoValidate();
}

// инициализация валидатора
function initValidator(id, rules)
{
//var validator = new Validator(id, rules);
}

/**
	Upload файлов
 */
var oldtarget = "";
var oldaction = "";
var oldenctype = "";
// начанает аплоад
function uploadFile(element, id, top) {
	if ($('#uplGoods_file').val() != "") {
		var form = element.form;
		$("#" + id + "_btn").attr("value", "Загрузка...");
		$("#" + id + "_btn").attr("disabled", "disabled");
		//$(id+"_btn").style.display = "none";
		oldtarget = form.target;
		oldaction = form.action;
		oldenctype = form.enctype;
		form.action = "/upload.php?id=" + id;
		form.target = id + "_upload";
		form.method = "post";
		form.enctype = "multipart/form-data";
		$("#" + id + "_submit").click();
		form.target = oldtarget;
		form.enctype = oldenctype;
		form.action = oldaction;
	}
}
// событие при завершении аплоада файла
function onFileUploadFinished(id, filepath, fileId, title, filesize, manname)
{
    var form = $("#"+id+"_submit").form;

    $("#"+id+"_progress").hide();
    $("#"+id+"_btn").show();
    var table = $("#"+id+"_table");
    var tblBody = $("#"+id+"_tbody");

    var row = document.createElement("tr");
    row.id = 'upload_file'+fileId;

    var DelBtnCell = document.createElement("td");
    DelBtnCell.innerHTML = "<input type=\"image\" name=\"handlerBtnDel:" + fileId+'" src="/img/admin/close_16.png" alt="Удалить файл" height="16" width="16" onclick="delUplFile(\''+fileId+'\'); return false;" />';
    DelBtnCell.style.width = "16px";
    row.appendChild(DelBtnCell);

    var ImgCell = document.createElement("td");
    ImgCell.innerHTML = '<img style="vertical-align:middle" src="/img/admin/document_blank.png" align=left alt="' + title+'" title="'+title+'" ><span>'+title+'<span>';
    row.appendChild(ImgCell);

    if(manname){
        var ManCell = document.createElement("td");
        ManCell.innerHTML = '<span>Производитель: '+manname+'<span>';
        row.appendChild(ManCell);
    }

    var BtnUpCell = document.createElement("td");
    BtnUpCell.innerHTML = '<input type="image" name="handlerBtnUp:'+fileId+'" src="/img/admin/arrow_up_16.png" alt="Поднять файл" height="16" width="16" onclick="upUplFile(\''+id+'\', \''+fileId+'\'); return false;" />';
    BtnUpCell.style.width = "16px";
    row.appendChild(BtnUpCell);

    var BtnDownCell = document.createElement("td");
    BtnDownCell.innerHTML = '<input type="image" name="handlerBtnDown:'+fileId+'" src="/img/admin/arrow_down_16.png" alt="Опустить файл" height="16" width="16"  onclick="downUplFile(\''+id+'\', \''+fileId+'\'); return false;" />';
    BtnDownCell.style.width = "16px";
    row.appendChild(BtnDownCell);

    var DescrCell = document.createElement("td");
    DescrCell.innerHTML = title+'('+filesize+' кб)';
    row.appendChild(DescrCell);

    tblBody.append(row);
    table.append(tblBody);

    $("#"+id+"_file").value = "";
    $("#"+id+"_file").outerHTML = "<input type=\"file\" width=\"100%\" id=\"" + id+'_file" name="'+id+'_file" size="60" />';
    $("#"+id+"_title").value = "";
}
// событие при завершении аплоада картинки
function onUploadFinished(id, imgPath, imgId, title, width, height, fileSize, thumbWidth, thumbHeight,one)
{

    var form = $("#"+id+"_submit").form;

    // $(id+"_progress").style.display = "none";
    $("#"+id+"_btn").attr("value","Загрузить на сайт");
    $("#"+id+"_btn").removeAttr("disabled");
    var table = $("#"+id+"_table");
    var tblBody = $("#"+id+"_tbody");

    //если изображение одно, удаляем все предыдущие перед вставкой
    if(one==1){
        $('tr',tblBody).remove();
    }

    var row = document.createElement("tr");
    row.id = 'upload_img'+imgId;

    var DelBtnCell = document.createElement("td");
    DelBtnCell.innerHTML = '<input type="image" name="handlerBtnDel:'+imgId+'" src="/img/admin/close_16.png" alt="Удаление изображение" onclick="delUplImg(\''+imgId+'\'); return false;" />';
    DelBtnCell.style.width = "16px";
    row.appendChild(DelBtnCell);

    if (thumbHeight != "")
        thumbHeight = "&height="+thumbHeight;

    var ImgCell = document.createElement("td");
    ImgCell.innerHTML = '<img src="'+imgPath+'&width='+thumbWidth+thumbHeight+'" width="'+thumbWidth+'" alt="'+title+'" title="'+title+'" >';
    ImgCell.style.width = thumbWidth+"px";
    row.appendChild(ImgCell);


    if(one==0){
        var BtnUpCell = document.createElement("td");
        BtnUpCell.innerHTML = '<input type="image" name="handlerBtnUp:'+imgId+'" src="/img/admin/arrow_up_16.png" alt="Поднять изображение" height="16" width="16" onclick="upUplImg(\''+id+'\', \''+imgId+'\'); return false;" />';
        BtnUpCell.style.width = "16px";
        row.appendChild(BtnUpCell);

        var BtnDownCell = document.createElement("td");
        BtnDownCell.innerHTML = '<input type="image" name="handlerBtnDown:'+imgId+'" src="/img/admin/arrow_down_16.png" alt="Опустить изображение" height="16" width="16"  onclick="downUplImg(\''+id+'\', \''+imgId+'\'); return false;" />';
        BtnDownCell.style.width = "16px";
        row.appendChild(BtnDownCell);
    }
    var DescrCell = document.createElement("td");
    DescrCell.innerHTML = title+' ('+width+'x'+height+' - '+fileSize+' кб)';
    row.appendChild(DescrCell);

    tblBody.append(row);
    table.append(tblBody);

    $("#"+id+"_file").attr("value","");
    $("#"+id+"_file").outerHTML = '<input type="file" width="100%" id="'+id+'_file" name="'+id+'_file" size="60" />';
/* $(id+"_title").value = "";*/
}
function delUplImg(id)
{
    if (!confirm("Вы уверены, что хотите удалить изображение?"))
        return false;
    $.ajax({
        type: "POST",
        url: "/upload.php",
        data: "deleteImg="+id,
        success: function(msg){
            $("#upload_img"+id).remove();
        }
    });
    return false;
}
function delUplFile(id)
{
    if (!confirm("Вы уверены, что хотите удалить файл?"))
        return false;

    $.ajax({
        type: "POST",
        url: "/upload.php",
        data: "deleteFile="+id,
        success: function(msg){
            $("#upload_file"+id).remove();
        }
    });
    return false;
}
function upUplImg(id, imgId)
{
    var obj = $("#upload_img"+imgId);
    var table = obj.parent();
    var prevObj = obj.prev("tr");

    $.ajax({
        type: "POST",
        url: "/upload.php",
        data: 'upImg='+imgId,
        success: function(msg){
            if (prevObj.length==0)
            {
                temp=obj;
                obj.remove();
                table.append(temp);
            }
            else
            {
                obj.remove();
                prevObj.before(obj);
            }
        }
    });

    return false;
}

function downUplImg(id, imgId)
{
    var obj = $("#upload_img"+imgId);
    var table = obj.parent();
    var nextObj = obj.next("tr");

    $.ajax({
        type: "POST",
        url: "/upload.php",
        data: 'downImg='+imgId,
        success: function(msg){
            if (nextObj.length==0)
            {
                temp=obj;
                obj.remove();
                table.prepend(temp);
            }
            else
            {
                obj.remove();
                nextObj.after(obj);
            }
        }
    });
    return false;
}
function downUplFile(id, fileId)
{
    var obj = $("#upload_file"+fileId);
    var table = obj.parent();
    var nextObj = obj.next("tr");

    $.ajax({
        type: "POST",
        url: "/upload.php",
        data: 'downFile='+fileId,
        success: function(msg){
            if (nextObj.length==0)
            {
                temp=obj;
                obj.remove();
                table.prepend(temp);
            }
            else
            {
                obj.remove();
                nextObj.after(obj);
            }
        }
    });
    return false;

}
function upUplFile(id, fileId)
{
    var obj = $("#upload_file"+fileId);
    var table = obj.parent();
    var prevObj = obj.prev("tr");

    $.ajax({
        type: "POST",
        url: "/upload.php",
        data: 'upFile='+fileId,
	    success: function() {
		    var temp;
	        if (prevObj.length == 0) {
		        temp = obj;
		        obj.remove();
		        table.append(temp);
	        }
	        else {
		        obj.remove();
		        prevObj.before(obj);
	        }
        }
    });

    return false;
}
