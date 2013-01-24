<?php //netteCache[01]000377a:2:{s:4:"time";s:21:"0.41048800 1358981338";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:55:"/var/www/EventCalendar/demo/app/templates/@layout.latte";i:2;i:1358981299;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"94abcaa released on 2012-02-29";}}}?><?php

// source file: /var/www/EventCalendar/demo/app/templates/@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'v7xvrx3ccg')
;
// prolog Nette\Latte\Macros\UIMacros

// snippets support
if (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?>
<!DOCTYPE html>
<html lang="cs-CZ">
    <head>
        <meta charset="UTF-8" />
        <title>EventCalendar</title>

        <link href="css/eventCalendar.css" type="text/css" rel="stylesheet" />
        <link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>

        <script language="javascript" src="js/jquery.js"></script>
        <script language="javascript" src="js/jquery-ui.js"></script>
        <script language="javascript" src="js/nette-ajax.js"></script>
        <script language="javascript">      
        $(function() {
                $( "#tabs" ).tabs();
        });
        </script>
    </body>
</html>