<?php
/**
 * User: zhouwenlong
 * Date: 2016/1/7
 */

namespace test\core;


class MedooTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Core\Medoo
     */
    static $db;

    public static function setUpBeforeClass()
    {
        self::$db = new \Core\Medoo([
        'database_type' => 'mysql',
        'database_name' => 'test',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => 'phpunit_',
    ]);

    }

    public function testSelect_context()
    {
        //反射保护方法
//        $reflect = new \ReflectionMethod('\Core\Medoo', 'select_context');
//        $reflect->setAccessible(true);
//
//        $selectString = $reflect->invoke(self::$db, 'test','*', []);
//        $this->assertEquals($selectString, 'SELECT * FROM "phpunit_test"');

    }


}
