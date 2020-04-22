<?php
header('Content-type: text/html;charset="utf-8;"'); //отправка заголовка, сообщающего браузеру какую кодировку использовать utf-8

define('AMO_DOMAIN', 'amocrm.ru'); //домен CRM
define('AMO_PROTOCOL', 'https'); //протокол CRM
define('AUTO_BUILD', TRUE);
$start = microtime(TRUE);
require_once __DIR__.'/widget_lib.phar';


\Helpers\Debug::vars('Done<br/>Execution time: '.round(microtime(TRUE)-$start,2).'sec<br/>Memory peak usage: '.round(memory_get_peak_usage(TRUE)/1024/1204,2).'Mb','Widget initialization');
