<?php
/*
 * Copyright (c) 2015-2016 Flávio Veloso
 * License: Apache License V2 <http://www.apache.org/licenses/LICENSE-2.0.html>
 */
namespace TimeIt;

class Timer {

	protected $code;
	protected $setup;

	public function __construct($code, $setup = NULL) {
		$this->code = (is_callable($code) ?
					   $code : function() { eval("$code;"); });
		$this->setup = ($code === NULL || is_callable($code) ?
						$setup : function() { eval("$setup;"); });
	}

	public function timeit($rounds = NULL)
	{
		$true_rounds = $rounds ? $rounds : 10;

		$delta = 0;

		if ($this->setup) {
			$this->setup();
		}

		while (TRUE)
		{
			$start = microtime(TRUE);
			for ($i = 0; $i < $true_rounds; $i++)
				$code();
			$end = microtime(TRUE);
			$delta += $end - $start;
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
