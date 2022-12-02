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

print('Elf with most calories: ' . $elves[0] . "\n");
