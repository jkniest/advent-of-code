<?php

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$totallyContained = 0;
foreach ($input as $pair) {
    [$a, $b] = explode(',', $pair);

    [$aStart, $aEnd] = explode('-', $a);
    [$bStart, $bEnd] = explode('-', $b);

    if (($bStart >= $aStart && $bEnd <= $aEnd) || ($aStart >= $bStart && $aEnd <= $bEnd)) {
        $totallyContained++;
    }
}

print('Redundant pairs: ' . $totallyContained . "\n");
