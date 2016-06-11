<?php

use TimeIt\Timer;

class TimerTestcase extends PHPUnit_Framework_TestCase {

	public function testTimeItDefaultsTimeProperly() {

		// This is a tricky test. It will accept the default number of
		// rounds (10) and run code that takes 1 second to "run". It
		// should take roughly 10s to run and, at the end, we should
		// also have proper timings.
		$t = new Timer('sleep(1)');

		$start = time();
		$res = $t->timeit();
		$delta = time() - $start;

		// Default is 10 rounds
		$this->assertEquals(10, $res[0]);

		// This definitely must take 10s or more to run
		$this->assertGreaterThanOrEqual(10, $delta);

		// But not too much (unless your computer is very slow)
		$this->assertLessThan(10.5, $delta);

		// Each run should take roughly 1s
		$this->assertEquals(1.00, $res[1], '', 0.001);

		// The timing string should reflect a precise timing
		$this->assertEquals('1.00s', $res[2]);
	}

	public function testTimeItObeyRounds() {

		$runs = 0;

		$func = function() use (&$runs) { $runs++; };

		$t = new Timer($func);

		$res = $t->timeit(1);
		$this->assertEquals(1, $runs);
		$this->assertEquals(1, $res[0]);

		$runs = 0;
		$res = $t->timeit(50);
		$this->assertEquals(50, $runs);
		$this->assertEquals(50, $res[0]);
	}

	public function testTimeItRunsSetupCodeOnce() {

		$runs = 0;
		$setup_func = function() use (&$runs) { $runs++; };

		$t = new Timer('', $setup_func);

		$t->timeit(50);

		$this->assertEquals(1, $runs);
	}

	public function testRepeatDefault() {

		$t = new Timer('');

		$res = $t->repeat(10);

		$this->assertEquals(3, count($res));
	}

	public function testRepeatObeyRepeatParameter() {

		$t = new Timer('');

		$res = $t->repeat(10, 100);

		$this->assertEquals(100, count($res));
	}

	public function testRepeatOutputIsSorted() {

		$t = new Timer(function() { usleep(rand(1000, 10000)); });

		$res = $t->repeat(10, 100);

		$last = 0;
		for ($i = 0; $i < 100; $i++)
		{
			$this->assertGreaterThanOrEqual($last, $res[$i][0]);
			$last = $res[$i][0];
		}
	}
}
