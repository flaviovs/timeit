Library and tools to measure execution speed of PHP code
========================================================

Introduction
------------

This package contain a library and tools to measure execution speed of
PHP code. You can use it to measure alternative code when profiling
and improving performance of your application.


Requirements
------------

This package requires PHP 5.4 or above.


How to use
----------

You should require `src/Timer.php` in your PHP script to use the
object-oriented interface. A procedural interface is also available --
to use it, require `src/functions.php`.


Basic usage
-----------

To get timings of code runs, use the code below:

    $timer = new \TimeIt\Timer('pow(2, 5)');
    $res = $timer->timeit();

The returned `$res` variable contains a three-elements array with the
following information:

* `$res[0]`: The number of rounds (iterations) used to calculate the
  timing. By default, the object figures out the number of iterations
  needed to give the most accurate results possible.

* `$res[1]`: The time spent on *each* run of your code, in seconds. In
  other words, this is the approximate time that it takes to run your
  code once.

* `$res[2]`: A human-readable, time-scaled string representation of
  the time needed to run your code (i.e., the value in `$res[1]`). For
  example, for a code that takes 2 seconds to run the function would
  return *2.00s*. The string returned may represent time in seconds
  (*s*), milliseconds (*ms*), or microseconds (*us*).

As said above, the object will calculate the number of rounds to
return appropriate values, depending on the speed of your code
(starting at 10 iterations). However, you can force it to use any
number of rounds. To do this, pass the desired number to the
`timeit()` method:

     $res = $timer->timeit(500);

You can make the object run the code several times, and return an
array of results. To do this, call the `repeat()` method:

     $multi_res = $timer->repeat(500, 5);

This will call `timeit(500)` five times. In the example above, the
returned value will be a multi-dimensional five-elements array
containing the result of each run. The array of results will be sorted
by execution speed in ascending order (i.e., highest execution speed
first). The number of repetitions is optional. The default is 3.


Reference
---------

* `$timer->__construct($code, $setup = NULL)` - construct a
  `TimeIt\Timer` object for measuring execution speed of `$code`. Code
  can be a string containing PHP code, or any other callable (for
  example, anonymous functions, class/objects methods specified using
  arrays, etc.).

  `$setup` may contain setup code that should be run only once, before
  each speed evaluation of `$code`. It may be used to setup the
  environment needed by the code to run properly. If `NULL`, then no
  setup code will be run.

* `$timer->timeit($rounds = NULL)` - measure code execution speed, and
  return an array with results. See *Basic usage* above for details
  about the returned array structure. You can specify a number of
  rounds in the `$rounds` parameter, or let the object determine an
  appropriate number.

* `$timer->repeat($rounds = NULL, $repeat = 3)` - run the `timeit()`
  method `$repeat` times to get separate samples of execution
  speed. The return value is an array of array structures as returned
  by `timeit()`, ordered by execution speed (fastest first).


Procedural interface
--------------------

The library also provide a convenient procedural interface:

* `timeit($code, $setup = NULL, $rounds = NULL)` - Measure the speed
  of code in `$code`. See the *Reference* section above for
  information about the other parameters.

* `timeit_repeat($code, $setup = NULL, $rounds = NULL, $repeat = 3)` -
  Take `$repeat` speed measurements of the code in `$code`. See the
  *Reference* section above for information about the other
  parameters.

* `timeit_str($code, $setup = NULL, $rounds = NULL, $repeat = 3)` -
  run `timeit_repeat()` using the provided parameters, and return a
  human-readable string with information about the *best* measurement
  found.


Command line script
-------------------

The `bin/timeit` script provides a convenient interface to check
execution speed of PHP code from the command line. Run the script
passing PHP code as parameters. You can can pass many parameters in
the same run, which make the script a very convenient way to help
select which code perform best.

Example:

    $ bin/timeit 'pow(5, 5)'
    pow(5, 5): 100000 loops, best of 3: 9.21us per loop

    $ bin/timeit 'pow(2, 5)' '5 * 5 * 5 * 5 * 5'
    pow(2, 5): 100000 loops, best of 3: 8.51us per loop
    5 * 5 * 5 * 5 * 5: 1000000 loops, best of 3: 4.10us per loop

**Warning**: you must be extra careful when passing PHP code in the
command line to the `timeit` script. That's because single/double
quotes, that are often used in PHP code, may clash with the ones used
by your shell. The script expect that each separate parameter contains
a single piece of code, but if you do not escape your quotes properly,
the shell may divide the parameter in unexpected ways. You probably
need to escape dollar signs as well, to prevent them to be interpreted
by the shell.

Examples:

**Wrong**

    $ bin/timeit '$a = 'foobar'; substr($a, 0, 1)'
    PHP Notice:  Use of undefined constant foobar - assumed 'foobar' in /home/flaviovs/github/timeit/src/Timer.php(39) : eval()'d code on line 1
    PHP Stack trace:
	(...several errors...)

**Right**

    $ bin/timeit '$a = "foobar"; substr($a, 0, 1)'

or

    $ bin/timeit "\$a = 'foobar'; substr(\$a, 0, 1)" # We must escape the "$"


Caveat
------

This library measure the *wall clock* time needed to run code. This
mean that different computers, different CPUs, or even *different
execution environments* may affect measurements.

For instance, you should not expect to get the same numbers when
measuring on two different computers, nor if you do measurements on
the same computer at different times. For example, measuring the same
code on a server under heavy I/O and in quiet times will probably not
give you the same results.


Legal
-----

Copyright 2015-2016 Flávio Veloso

This package is licensed under the Apache License version 2.0. See
the file LICENSE for more details.

If you find bugs in this software, please open an issue on GitHub at
https://github.com/flaviovs/timeit, or send it to me at flaviovs at
magnux dot com.
