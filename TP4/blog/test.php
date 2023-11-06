<?php

function test()
{
    try {
        throw new Exception('ma seconde exception');
    } catch (Exception $exception) {
        throw new Exception('ma troisième exeption');

        die($exception->getMessage());
    }
    throw new Exception('mon exception depuis une fonction');

}

try {
    test();

    echo "je continue";
} catch (Exception $exception) {
    die($exception->getMessage());
}