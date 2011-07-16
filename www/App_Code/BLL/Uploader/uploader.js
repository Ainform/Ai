var viewReq = getXmlHttpRequestObject();
var imageReq = getXmlHttpRequestObject();

var up = 0;
var down = 0;

// Возвращает объект XmlHttpviewReq браузера
function getXmlHttpRequestObject()
{
	if (window.XMLHttpRequest)
	{
		return new XMLHttpRequest();
	}
	else if(window.ActiveXObject)
	{
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	else
	{
		alert('Невозможно создать объект XmlHttpviewReq! Установите последнюю версию браузера.');
	}
}

// Получает сообщения с сайта
function getImagesList()
{
	if (viewReq.readyState == 4 || viewReq.readyState == 0)
	{
		var fileName = document.getElementById('directory').value + 'getList.php';

		viewReq.open("GET", fileName, true);
		viewReq.onreadystatechange = handleViewReq; 
		viewReq.send(null);
	}
}

// Управляет текстом чата
function handleViewReq()
{
	if (viewReq.readyState == 4)
	{
		var table = '';
		var xmlDocument = viewReq.responseXML;

		if (xmlDocument.getElementsByTagName("image").length == 0)
			return;

		var imagesNodes = xmlDocument.getElementsByTagName("image"); 
		var directoryNode = xmlDocument.getElementsByTagName("directory"); 
		var imagesCount = imagesNodes.length

		var directory = directoryNode[0].firstChild.nodeValue;

		if (0 < imagesCount)
			table =  '<br /><table class="admin_table" width="540"><tr><th>Изображение</th><th>Название</th><th>Удалить</th></tr>';
			//table =  '<br /><table class="admin_table" width="540"><tr><th>Изображение</th><th>Название</th><th>Поднять</th><th>Опустить</th><th>Удалить</th></tr>';

		for (i = 0; i < imagesCount; i++)
		{
			var fileUrlNode  = imagesNodes[i].getElementsByTagName("fileUrl");
			var fileSizeNode = imagesNodes[i].getElementsByTagName("fileSize");
			var fileTitleNode = imagesNodes[i].getElementsByTagName("fileTitle");
			var fileName 	 = fileUrlNode[0].firstChild.nodeValue;
			var fileSize	 = fileSizeNode[0].firstChild.nodeValue;
			var fileTitle	 = fileTitleNode[0].firstChild.nodeValue;

			table += '<tr id="imRow' + i +'">';
			table += '<td align="center"><img src="/ImageHandler.php?id=' + directory + fileName + '&upload=1&width=50" width="50" /><br />(Размер: ' + fileSize + ' Кb)</td>';
			table += '<td>' + fileTitle + '</td>';
			//table += '<td width="100" align="center"><input type="image" src="/img/op_up.gif" height="16" width="16" onclick="return UpPicture(\'' + fileName + '\', '+ i +');" /></td>';
			//table += '<td width="100" align="center"><input type="image" src="/img/op_down.gif" height="16" width="16" onclick="return DownPicture(\'' + fileName + '\', '+ i +');" /></td>';
			table += '<td width="100" align="center"><input type="image" src="/img/op_delete.gif" height="16" width="16" onclick="return DeletePicture(\'' + fileName + '\');" /></td>';
			table += '</tr>';
		}

		table += '</table>';

		var resultDiv = document.getElementById('uploadResult').innerHTML = table;
	}
}


function uploadFile(obj)
{
	validformFile = /(.jpg|.JPG|.jpeg|.JPEG)$/;

	var uploader = document.getElementById('uploader');
	
    if(!validformFile.test(uploader.value))
    {
      alert("Для изображений поддерживается только формат Jpeg!");

      uploader.focus();
      uploader.select();
      
      return false;
  	}

  	uploader.enable = false;

	document.getElementById('uploadProcess').innerHTML = 'Загрузка файла. Пожалуйста дождитесь окончания загрузки...';
	document.getElementById('uploadForm').submit();
}

function UpdatePictures()
{
	document.getElementById('uploader').style.display = 'block';
	document.getElementById('uploadProcess').innerHTML = '';
	getImagesList();
}

function UpPicture(pictureName, row)
{
	if (imageReq.readyState == 4 || imageReq.readyState == 0)
	{
		up = row;
		
		document.getElementById('uploader').style.display = 'none';
		document.getElementById('uploadProcess').innerHTML = 'Изменение позиции файла. Пожалуйста подождите...';

		var fileName = document.getElementById('directory').value + 'up.php';

		document.getElementById('uploader').value = '';

		imageReq.open("GET", fileName + '?fileName=' + pictureName, true);
		imageReq.onreadystatechange = handleImageUp; 
		imageReq.send(null);
	}
	else
		alert('Ошибка. Сервер не отвечает.');

	return false;
}

// поднимает картинку
handleImageUp()
{
	if (imageReq.readyState == 4)
		if (up != 0)
		{
			down = up - 1;
			
			upRow = document.getElementById('imRow' + up);
			downRow = document.getElementById('imRow' + down);

			alert('Upped!');

			temp = downRow.innerHTML;
			downRow.innerHTML = upRow.innerHTML;
			upRow.innerHTML = temp;
		}
}


function DownPicture(pictureName)
{
	if (imageReq.readyState == 4 || imageReq.readyState == 0)
	{
		document.getElementById('uploader').style.display = 'none';
		document.getElementById('uploadProcess').innerHTML = 'Изменение позиции файла. Пожалуйста подождите...';
		document.getElementById('uploadResult').innerHTML = '';

		var fileName = document.getElementById('directory').value + 'down.php';

		document.getElementById('uploader').value = '';
		
		imageReq.open("GET", fileName + '?fileName=' + pictureName, true);
		imageReq.onreadystatechange = handleImageReq; 
		imageReq.send(null);
	}
	else
		alert('Ошибка. Сервер не отвечает.');

	return false;
}


function DeletePicture(pictureName)
{
	if (!confirm('Удалить изображение?'))
		return false;

	if (imageReq.readyState == 4 || imageReq.readyState == 0)
	{
		document.getElementById('uploader').style.display = 'none';
		document.getElementById('uploadProcess').innerHTML = 'Удаление файла. Пожалуйста подождите...';
		document.getElementById('uploadResult').innerHTML = '';

		var fileName = document.getElementById('directory').value + 'delete.php';
		
		imageReq.open("GET", fileName + '?fileName=' + pictureName, true);
		imageReq.onreadystatechange = handleImageReq; 
		imageReq.send(null);
	}
	else
		alert('Ошибка. Сервер не отвечает.');
	
	return false;
}



function handleImageReq()
{
	if (imageReq.readyState == 4)
	{
		UpdatePictures();
	}
}