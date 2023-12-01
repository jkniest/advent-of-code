<?php

$input = explode("\n", file_get_contents('input.txt'));

$total = array_reduce($input, static function (int $sum, string $line): int {
    $numbers = preg_grep('/[0-9]/', str_split($line));
    return $sum + (int)(current($numbers).end($numbers));
}, 0);

echo "\n\nTotal number summed: {$total}\n\n";
echo "Done.\n\n";