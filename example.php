<?php

require 'timeit.php';

const TEST_STRING = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaX';

function f1()
{
    strpos(TEST_STRING, 'X');
}

function f2()
{
    $len = strlen(TEST_STRING);
    $str = TEST_STRING;
    for ($i = 0; $i < $len; $i++)
        if ($str[$i] == 'X')
            break;
}

print_timeit('f1()');
print_timeit('f2()');
