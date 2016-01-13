<?php
/**
 * 运行生成文档
 *
 * 手动创建模板：
 * 模板放在doc/templates目录下面，template.xml文件里面的路径要和模板文件夹名称相同
 * 主意twig的模板会换成到Local\Temp(phpdoc-twig-cache)目录下（尼玛坑爹的我去！！）
 *
 * phpdoc.xml是配置文件
 */
$command = "..\\vendor\\bin\\phpdoc -c phpdoc.xml";
system($command);