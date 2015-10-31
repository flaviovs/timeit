<?php

require 'timeit.php';

const TEST_STRING = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaX';

function f1()
{
    strpos('X', TEST_STRING);
}

function f2()
{
    $len = strlen(TEST_STRING);
    $str = TEST_STRING;
    for ($i = 0; $i < $len; $i++)
        if ($str[$i] == 'X')
            break;
}

print_r(timeit('f1()'));
print_r(timeit('f2()'));
