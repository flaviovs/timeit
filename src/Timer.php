<?php
/*
 * Copyright (c) 2015-2016 Flávio Veloso
 * License: Apache License V2 <http://www.apache.org/licenses/LICENSE-2.0.html>
 */
namespace TimeIt;

class Timer {

	const OVERHEAD_ITERATIONS = 1e5;

	static protected $loopOverhead;

	protected $code;
	protected $setup;

	static protected function initLoopOverhead() {
		$code = '';
		static::$loopOverhead = static::timeExec(function() use ($code) {
				eval("$code;");
			}, static::OVERHEAD_ITERATIONS) / static::OVERHEAD_ITERATIONS;
	}

	static protected function timeExec(callable $code, $rounds) {
		$gc_enabled = gc_enabled();
		if ($gc_enabled)
			gc_disable();
		$start = microtime(TRUE);
		for ($i = 0; $i < $rounds; $i++)
			$code();
		$end = microtime(TRUE);
		if ($gc_enabled)
			gc_enable();
		return $end - $start;
	}

	public function __construct($code, $setup = NULL) {
		$this->code = (is_callable($code) ?
		               $code : function() use ($code) { eval("$code;"); });
		$this->setup = ($setup === NULL || is_callable($setup) ?
		                $setup : function()  use ($setup) { eval("$setup;"); });

		if (static::$loopOverhead === NULL) {
			static::initLoopOverhead();
		}
	}

	public function timeit($rounds = NULL)
	{
		$true_rounds = $rounds ? $rounds : 10;

		$delta = 0;

		if ($this->setup) {
			$code = $this->setup;
			$code();
		}

		$code = $this->code;

		while (TRUE)
		{
			$delta += static::timeExec($code, $true_rounds)
				- ($true_rounds * static::$loopOverhead);
			if ($rounds || $delta > 0.5)
				break;
			$true_rounds *= 10;
		}

		$time = $delta / $true_rounds;

		if ($time > 1)
			$delta_str = sprintf("%.2fs", $time);
		else if ($time > 1e-3)
			$delta_str = sprintf("%.2fms", $time * 1e3);
		else
			$delta_str = sprintf("%.2fus", $time * 1e6);

		return [$true_rounds, $time, $delta_str];
	}

	public function repeat($rounds = NULL, $repeat = 3) {
		$results = [];

		while ($repeat--) {
			$results[] = $this->timeit($rounds);
			if (!$rounds) {
				// Use the same number of rounds in the remaining
				// repetitions.
				$rounds = $results[0][0];
			}
		}

		usort($results,
			  function($a, $b) {
				  return ($a[1] == $b[1] ?
						  0 :
						  ($a[1] < $b[1] ? -1 : 1));
			  });

		return $results;
	}
}
