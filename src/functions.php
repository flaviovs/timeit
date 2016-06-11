<?php
/*
 * Copyright (c) 2015-2016 Flávio Veloso
 * License: Apache License V2 <http://www.apache.org/licenses/LICENSE-2.0.html>
 */

function timeit($code, $setup = NULL, $rounds = NULL) {
	$timer = new \TimeIt\Timer($code, $setup);
	return $timer->timeit($rounds);
}

function timeit_repeat($code, $setup = NULL, $rounds = NULL, $repeat = 3) {
	$timer = new \TimeIt\Timer($code, $setup);
	return $timer->repeat($rounds, $repeat);
}

function timeit_print($code, $setup = NULL, $rounds = NULL,
                      $repeat = 3, $label = NULL) {
	if (!$label)
		$label = $code;

	$results = timeit_repeat($code, $setup, $rounds, $repeat);

	// Get the actual number of repetitions.
	$repeat = count($results);

	print "$label: $results[0] loops, best of $repeat: $results[2] per loop\n";
}
