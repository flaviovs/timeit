<?php
/*
 * A Python-like timeit function for PHP.
 * Copyright (c) 2015 Flávio Veloso
 * License: Apache License V2 <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 * Call it with code to be eval'ed in the first parameter, and
 * optionally the number of times to execute the code. If the number
 * of runs is note specified, the function will try to find a suitable
 * number of rounds starting from 10, increasing it using powers of 10
 * until the code take at least 0.5 seconds to run.
 *
 * Return an array with 3 elements:
 * - Actual number of times the code was executed.
 * - Average time for running the code (in microseconds).
 * - A textual, magnitude-order adjusted, representation of the
 *   average run time of the code.
 *
 * Examples:
 *
 *  print_r(timeit('sleep(1);'));
 *
 *  print_r(timeit('$str = "12345"; $res = substr($str, 0, 1) == "X";'));
 *  print_r(timeit('$str = "12345"; $res = $str[0] == "X";'));
 *
 * You can also use print_timeit() as a convenient way to print the
 * results:
 *
 *  print_timeit('strpos("abc", "a")');
 */
function timeit($code, $rounds = NULL)
{
     // Add final semi-colon just in case.
     $code .= ';';

     $true_rounds = $rounds ? $rounds : 10;

     $delta = 0;
     while (TRUE)
     {
          $start = microtime(TRUE);
          for ($i = 0; $i < $true_rounds; $i++)
               eval($code);
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

     return array($true_rounds, $time, $delta_str);
}

function print_timeit($code, $rounds = NULL, $label = NULL)
{
    $result = timeit($code, $rounds);
    if (!$label)
        $label = $code;
    print "$label: $result[0] loops: $result[2] per loop\n";
}
