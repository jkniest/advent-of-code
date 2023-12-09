<?php

$lines = array_values(array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l)));
$sum = 0;

foreach ($lines as $line) {
    $numbers = array_map(fn(string $s) => (int)$s, explode(' ', $line));
    $sequences = [$numbers];

    do {
        $sequences[] = subtract($sequences[count($sequences) - 1]);
    } while (!reachedZero($sequences));

    for ($i = count($sequences) - 1; $i >= 0; $i--) {
        $numberToAdd = 0;
        if ($i < count($sequences) - 1) {
            $numberToAdd = $sequences[$i][count($sequences[$i]) - 1] + $sequences[$i + 1][count($sequences[$i + 1]) - 1];
        }

        $sequences[$i][] = $numberToAdd;
    }

    $sum += $sequences[0][count($sequences[0]) - 1];
}

function subtract(array $input): array
{
    $count = count($input);
    $result = [];

    for ($i = 0; $i < $count - 1; $i++) {
        $result[] = $input[$i + 1] - $input[$i];
    }

    return $result;
}

function reachedZero(array $sequences): bool
{
    foreach ($sequences[count($sequences) - 1] as $number) {
        if ($number !== 0) return false;
    }

    return true;
}


echo "\n\nSummed predictions: {$sum}\n\n";
echo "Done.\n\n";