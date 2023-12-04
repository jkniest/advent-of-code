<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));
$lineCount = count($lines);

$cardCounts = [];

for($i = 0; $i < $lineCount; $i++) {
    $cardCounts[$i] ??= 0;
    $cardCounts[$i]++;

    $line = $lines[$i];

    [$_, $relevant] = explode(':', $line);
    [$winning, $real] = explode('|', $relevant);

    $winningNumbers = array_filter(explode(' ', $winning));
    $realNumbers = array_filter(explode(' ', $real));

    $intersect = array_intersect($winningNumbers, $realNumbers);
    $count = count($intersect);

    for ($j = $i + 1; $j < $i + $count + 1; $j++) {
        if($j > $lineCount) break;

        $cardCounts[$j] ??= 0;
        $cardCounts[$j] += $cardCounts[$i];
    }
}

$sum = array_sum($cardCounts);

echo "\n\nTotal summed: {$sum}\n\n";
echo "Done.\n\n";
