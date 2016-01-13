<?php
namespace Core\swoole;

use Exception;
use swoole_server;
/**
 * 异步任务队列
 *
 * base on swoole
 * @package Core\swoole
 * @example
 *
 * 	$server = new ZsQueenServer('127.0.0.1', 9501, [
 *			'task_worker_num' => 1,
 *		]);
 *	$server->run(function(){
 *		echo 'im task logic..';
 *	}, function(){
 *		echo 'task finish';
 *	});
 *
 * @author zhoushen
 * @since 1.0
 *
 */
class QueenServer{

	protected $serv = null; //server instance
	public $logicFunc;
	public $finishFunc;

	public function __construct($host, $port, array $config = []){

		$this->serv = new swoole_server($host, $port);
		$defaultConfig = [
			'daemonize' => 1,
			'task_worker_num' => 4,
		];
		$this->serv->set(array_merge($defaultConfig, $config));

		$this->serv->on('receive', function($serv, $clientId, $workerId, $data){
			//worker投递异步任务到task进程
			$task_id = $serv->task($data);
		});

		$this->serv->on('task', function($serv, $taskId, $workerId, $data){
			if(!is_callable($this->taskLogic)){
				throw new Exception('task logic undefined!');
			}
			//执行task逻辑
			$result = call_user_func($this->taskLogic, $serv, $taskId, $workerId, $data);
			$serv->finish($result);
		});

		$this->serv->on('finish', function ($serv, $taskId, $data) {
			if(!is_callable($this->taskFinish)){
				return null;
			}
			return call_user_func($this->taskFinish, $serv, $taskId, $data);
		});

	}

	/**
	 * 获取server对象
	 * @return [type] [description]
	 */
	public function getServer(){
		return $this->serv;
	}

	/**
	 * 运行服务端
	 * @param  [type] $logicFunc  注册的业务逻辑函数
	 * @param  string $finishFunc 注册的任务完成函数
	 * @return [type]             [description]
	 */
	public function run($logicFunc, $finishFunc = ''){
		$this->taskLogic = $logicFunc;
		$this->taskFinish = $finishFunc;
		$this->serv->start();
	}
}

