<?php
/**
 * User: zhouwenlong
 * Date: 2016/1/11
 */

class Util_ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testGetConfig()
    {
        $config = Util_Config::getConfig('admin');
        $this->assertEquals([
            'administrator' => [
                'name' => 'admin',
                'pwd'  => 'admin',
            ]
        ] ,$config->toArray());

        $this->assertEquals('admin', $config->administrator->name);
    }
}
