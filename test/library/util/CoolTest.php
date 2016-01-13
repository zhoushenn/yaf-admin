<?php
/**
 * User: zhouwenlong
 * Date: 2016/1/8
 */

class CoolTest extends \PHPUnit_Framework_TestCase
{

    public function testUrl()
    {
        $s = Util_Helper::url('index', 'index', 'admin', ['a'=>1,'b'=>2]);
        $this->assertEquals('/admin/index/index/a/1/b/2', $s);
    }
}
