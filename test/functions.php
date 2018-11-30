<?php
/*
 * Copyright (c) 2015-2016 Flávio Veloso
 * License: Apache License V2 <http://www.apache.org/licenses/LICENSE-2.0.html>
 */

class FunctionsTestcase extends PHPUnit_Framework_TestCase
{

    public function testTimeIt()
    {
        $res = timeit('usleep(200000)', null, 10);

        $this->assertEquals(10, $res[0]);
        $this->assertEquals(0.200, $res[1], '', 0.001);
        $this->assertRegExp('/^200\.\d\dms$/', $res[2]);
    }

    public function testTimeItRepeat()
    {
        $res = timeit_repeat('', null, 10);
        $this->assertEquals(3, count($res));
    }

    public function testTimeItStr()
    {
        $this->assertRegExp(
            '/10 loops, best of 3: \d+\.\d\d\S+ per loop$/',
            timeit_str('', null, 10)
        );
    }
}
