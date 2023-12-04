<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

$sum = 0;

foreach ($lines as $line)
{
    [$_, $relevant] = explode(':', $line);
    [$winning, $real] = explode('|', $relevant);

    $winningNumbers = array_filter(explode(' ', $winning));
    $realNumbers = array_filter(explode(' ', $real));

    $intersect = array_intersect($winningNumbers, $realNumbers);
    $count = count($intersect);

    if($count === 0) {
        continue;
    }

    if($count === 1) {
        $sum++;
        continue;
    }

    $sum += pow(2, count($intersect) - 1);
}

echo "\n\nTotal summed: {$sum}\n\n";
echo "Done.\n\n";
