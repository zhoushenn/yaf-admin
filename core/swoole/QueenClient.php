<?php
namespace Core\swoole;

use Exception;
use swoole_client;

/**
 * 异步任务客户端
 *
 * base on swoole
 * @package Core\swoole
 * @example
 * (new ZsQueenClient([ ['host'=>'127.0.0.1', 'port'=>'9501', 'timeout' => 3] ]))->send('client request data');
 *
 *
 * @author zhoushen
 * @since 1.0
 */
class QueenClient{

	public function __construct(array $serverConfig, array $config = []){

		$this->client = new swoole_client(SWOOLE_SOCK_TCP);
		$this->client->set($config);
		$config = $serverConfig[mt_rand(0, count($serverConfig) -1)];
		if (!$this->client->connect($config['host'], $config['port'], $config['timeout'])){
			throw new Exception('connected failed!');
		}
	}

	/**
	 * 发送请求
	 * @param  [type] $data 请求数据
	 * @return [type]       [description]
	 */
	public function send($data){
		$result = $this->client->send($data);
		$this->client->close();
		return $result;
	}

}

