<?php
header("Content-type: text/html; charset=utf-8");

define('APPLICATION_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', APPLICATION_PATH . '/application');
/**
 * 开启调试
 * 开启调试下会加载DebugPlugin插件.
 */
define('APP_DEBUG', true);
/**
 * 性能分析
 * 开启下会加载XhprofPlugin插件.
 */
define('APP_ANALYZE', false);
//define('XHPROF_HOST', 'http://zsyafxhprof.dev:8080'); //指定一个虚拟域名映射到xhprof目录
define('APP_MICROTIME_START', microtime(true));
define('APP_MEMORY_START', memory_get_usage());


$application = new Yaf\Application( APPLICATION_PATH . "/conf/application.ini");
//TODO: composer spl_autoload是prepend的方式，所以会在yaf自动加载机制前触发。可以另外写一个自动加载器，提高性能。
require APPLICATION_PATH . '/vendor/autoload.php';
$application->bootstrap()->run();
