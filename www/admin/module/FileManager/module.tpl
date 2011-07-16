
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" ></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/smoothness/jquery-ui.css" />

<script src="/util/elfinder-2.0-beta/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="/util/elfinder-2.0-beta/css/elfinder.min.css" type="text/css" media="screen" charset="utf-8" />
<script src="/util/elfinder-2.0-beta/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    $().ready(function(){
        var fm = new elFinder(document.getElementById('my-div'), { url : '/util/elfinder-2.0-beta/php/connector.php',lang : 'ru'});
    })
</script>
<div id="my-div"></div>