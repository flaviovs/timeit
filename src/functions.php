<?php
/*
 * Copyright (c) 2015-2016 Flávio Veloso
 * License: Apache License V2 <http://www.apache.org/licenses/LICENSE-2.0.html>
 */

function timeit($code, $setup = null, $rounds = null)
{
    $timer = new \TimeIt\Timer($code, $setup);
    return $timer->timeit($rounds);
}

function timeit_repeat($code, $setup = null, $rounds = null, $repeat = 3)
{
    $timer = new \TimeIt\Timer($code, $setup);
    return $timer->repeat($rounds, $repeat);
}

function timeit_str($code, $setup = null, $rounds = null, $repeat = 3)
{
    $results = timeit_repeat($code, $setup, $rounds, $repeat);

    // Get the actual number of repetitions.
    $repeat = count($results);

    $best = $results[0];

    return "$best[0] loops, best of $repeat: $best[2] per loop";
}
