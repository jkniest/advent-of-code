<?php

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$overlapping = 0;
foreach ($input as $pair) {
    [$a, $b] = explode(',', $pair);

    [$aStart, $aEnd] = explode('-', $a);
    [$bStart, $bEnd] = explode('-', $b);

    $aRange = range($aStart, $aEnd);
    $bRange = range($bStart, $bEnd);

    foreach ($aRange as $aN) {
        if (in_array($aN, $bRange, true)) {
            $overlapping++;
            continue 2;
        }
    }
}

print('Overlapping pairs: ' . $overlapping . "\n");
