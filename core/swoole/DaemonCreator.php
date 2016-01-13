<?php
namespace Core\swoole;

/**
 * 使当前cli进程变成守护进程。（cgi下无法运行）
 * base on swoole
 * @package Core\swoole
 * @example:
 * DameonCreator::run(function(){
 *
 *		while (true) {
 *			echo 1;
 *			sleep(1);
 *		}
 *	});
 *
 * @author zhoushen
 * @since 1.0
 * 
 */

class DameonCreator{

	/**
	 * [run description]
	 * @param  Closure $logic   执行的业务逻辑
	 * @param  boolean $nochdir 为true表示不修改当前目录。默认false表示将当前目录切换到“/”
	 * @param  boolean $noclose 默认false表示将标准输入和输出重定向到/dev/null
	 * @return [type]           [description]
	 */
	public static function run(Closure $logic, $nochdir = false, $noclose = false){
		swoole_process::daemon($nochdir, $noclose);
		return call_user_func($logic);
	}
}
