<?php
/**
 * 执行外部脚本（不需要路由）
 */
define('APPLICATION_PATH', dirname(dirname(__FILE__)));

require APPLICATION_PATH . '/vendor/autoload.php';

$application = new Yaf\Application(APPLICATION_PATH . "/conf/application.ini");


/**
 * 能自动加载所需要的Model或者类库
 * 执行外部脚本（不需要路由）
 */
class Crontab{

	function runSomeJob(){
		// $m = new UserModel;
		// var_dump($m);
	}
}
$application->bootstrap()->execute(array(new Crontab, 'runSomeJob'));