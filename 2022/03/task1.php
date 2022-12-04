<?php

$input = explode("\n", file_get_contents(__DIR__.'/input.txt'));

$priorities = 0;
foreach ($input as $line) {
    [$first, $second] = str_split($line, strlen($line) / 2);
    $character = strpbrk($first, $second)[0];

    $priorities += ctype_lower($character)
        ? ord($character) - 96
        : ord($character) - 38;
}

print('Total sum of priorities: ' . $priorities . "\n");
