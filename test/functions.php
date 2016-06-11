<?php

class FunctionsTestcase extends PHPUnit_Framework_TestCase {

	public function testTimeIt() {
		$res = timeit('usleep(200000)', NULL, 10);

		$this->assertEquals(10, $res[0]);
		$this->assertEquals(0.200, $res[1], '', 0.001);
		$this->assertRegExp('/^200\.\d\dms$/', $res[2]);
	}

	public function testTimeItRepeat() {
		$res = timeit_repeat('', NULL, 10);
		$this->assertEquals(3, count($res));
	}

	public function testTimeItStr() {
		$this->assertRegExp('/10 loops, best of 3: \d\d\.\d\d\S+ per loop$/',
		                    timeit_str('', NULL, 10));
	}
}
