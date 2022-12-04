<?php

$input = explode("\n", file_get_contents(__DIR__.'/input.txt'));

const CHOOSING = [
    'A' => [
        'X' => 3 + 0, // Lose
        'Y' => 1 + 3, // Draw
        'Z' => 2 + 6 // Win
    ],
    'B' => [
        'X' => 1 + 0, // Lose
        'Y' => 2 + 3, // Draw
        'Z' => 3 + 6 // Win
    ],
    'C' => [
        'X' => 2 + 0, // Lose
        'Y' => 3 + 3, // Draw
        'Z' => 1 + 6 // Win
    ]
];

$score = 0;

foreach ($input as $line) {
    [$opponent, $me] = explode(' ', $line);
    $score += CHOOSING[$opponent][$me];
}

print('Your total score: ' . $score . "\n");
