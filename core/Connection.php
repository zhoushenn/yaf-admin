<?php
namespace Core;

use PDO;
/**
 * Mysql数据库连接类，实现读写分离
 *
 * @package Core
 * @author zhoushen
 * @since 1.0
 */
class Connection{

    /**
     * @var array 数据库配置
     */
    protected $dbConfig;
    /**
     * @var array 实例化的数据库对象
     */
    protected $dbPools;
    /**
     * @var array 当前使用的配置
     */
    protected $dbInfo;
    /**
     * @var string 主或从 master|slaves
     */
    protected $dbType;

    const MASTER_NAME = 'master';
    const SLAVE_NAME  = 'slave';

    /**
     * @param array $dbConfig 数据库配置
     */
    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    /**
     * 调用pdo对象方法
     *
     * @param $name pdo方法
     * @param array $arguments
     * @return mixed
     */
    function __call($name, $arguments=[])
    {
        //medoo这些方法用主库
        if( in_array($name, ['exec', 'insert', 'update', 'delete', 'replace', 'action']) ){
            $this->dbType = self::MASTER_NAME;
        }else{
            $this->dbType = self::SLAVE_NAME;
        }

        $db = $this->getPDO($this->dbType);

        return call_user_func_array(array($db, $name), $arguments);
    }

    /**
     * 获取指定类型的数据据库对象
     *
     * @param string $type master|slaves
     * @return PDO
     */
    public function getPDO($type){

        if( ! in_array ($type, array(self::MASTER_NAME, self::SLAVE_NAME) ) ){
            throw new Exception("Invalid db type");
        }

        if($type == self::SLAVE_NAME && !isset($this->dbConfig[$type])){
            $type = self::MASTER_NAME;
        }

        if( isset($this->dbPools[$type]) ){
            return $this->dbPools[$type];
        }

        $config = $this->dbConfig[$type];
        $config = $config[mt_rand(0, count($config) - 1)];
        $config = array_merge($config, $this->dbConfig['share']);

        $db = new Medoo($config);

        $this->dbInfo['dbType'] = $type;
        $this->dbInfo['dbConfig'] = $config;

        return $this->dbPools[$type] = $db;
    }

    /**
     * 关闭数据库
     *
     * @param string $type master|slaves
     */
    public function closeDb($type = null){
        if($type){
            unset($this->dbPools[$type]);
        }else{
            unset($this->dbPools);
        }
    }

}