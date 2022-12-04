<?php

$input = explode("\n", file_get_contents(__DIR__.'/input.txt'));

$priorities = 0;
$count = count($input);

for($i = 0; $i < $count; $i += 3) {
    $character = array_values(
        array_intersect(str_split($input[$i]), str_split($input[$i + 1]), str_split($input[$i + 2]))
    )[0];

    $priorities += ctype_lower($character)
        ? ord($character) - 96
        : ord($character) - 38;
}

print('Total sum of priorities: ' . $priorities . "\n");
