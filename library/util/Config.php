<?php

/**
 * 配置加载辅助类
 * @package Library
 */
class Util_Config
{

    /**
     * 获取ini配置
     * @param $configName 配置文件名称
     * @param $section 节点名称
     * @param $configPath 配置文件路径
     */
    public static function getConfig($configName, $section = null, $configPath = null)
    {
        if(!$configPath) $configPath = APPLICATION_PATH . '/conf';
        if(!$section) $section = YAF\ENVIRON;

        if( Yaf\Registry::has($configName . $section) ){
            return Yaf\Registry::get($configName . $section);
        }

        $config = new Yaf\Config\Ini($configPath . '/' . $configName . '.ini', $section);
        Yaf\Registry::set($configName . $section, $config);
        return $config;
    }

}