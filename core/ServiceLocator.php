<?php
namespace Core;

use InvalidArgumentException;

/**
 * 服务定位器
 * 实现简单的服务定位器，没有提供依赖注入（影响性能，感觉没必要）
 *
 * @package Di
 * @author zhoushen <445484792@qq.com>
 * @since 1.0
 */
class ServiceLocator{

    protected $_components; //保存组件实例
    protected $_definitions; //保存组件定义
    public static $instance;

    public static function getInstance(){
        if(self::$instance){
            return self::$instance;
        }

        return self::$instance = new self;
    }

    /**
     * 静态方式获取服务
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments){
        return self::getInstance()->get($name);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @param $definition
     */
    public function set($name, $definition)
    {
        if(is_string($definition) || is_object($definition) || is_callable($definition, true)) {
            $this->_definitions[$name] = $definition;
        }else{
            throw new InvalidArgumentException('Invalid definition.');
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        if(isset($this->_components[$name])){
            return $this->_components[$name];
        }

        if(!isset($this->_definitions[$name])){
           return false;
        }

        if(is_string($this->_definitions[$name]) && class_exists($this->_definitions[$name])){
            return $this->_components[$name] = new $this->_definitions[$name];
        }

        if(is_callable($this->_definitions[$name], true)){
            return $this->_components[$name] = call_user_func_array($this->_definitions[$name], []);
        }
	    
	if(is_object($this->_definitions[$name])){
	    return $this->_components[$name] = $this->_definitions[$name];
	}

        throw new InvalidArgumentException("Undefined service $name");
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->_definitions[$name]);
    }

    /**
     * @param $name
     */
    public function close($name)
    {
        unset($this->_components[$name], $this->_definitions[$name]);
    }

    /**
     * @return mixed
     */
    public function getComponents()
    {
        return $this->_components;
    }

    /**
     * @return mixed
     */
    public function getDefintions()
    {
        return $this->_definitions;
    }
}
