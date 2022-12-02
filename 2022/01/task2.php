<?php

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$elves = [0];
foreach ($input as $line) {
    if (empty($line)) {
        $elves[] = 0;
        continue;
    }

    $elves[count($elves) - 1] += (int)$line;
}

rsort($elves);

print('Total top three elves with most calories: ' . ($elves[0] + $elves[1] + $elves[2]) . "\n");
