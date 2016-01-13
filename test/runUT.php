<?php
/**
 * 手动跑单元测试（推荐直接修改phpunit.xml然后用ide运行）
 * only support run with cli.
 */
define('TEST_PATH', '../test/');
$command = "..\\vendor\\bin\\phpunit --verbose " . TEST_PATH;
system($command);