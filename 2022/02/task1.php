<?php

$input = explode("\n", file_get_contents(__DIR__.'/input.txt'));

const OPPONENT_TYPES = [
    'A' => 'rock',
    'B' => 'paper',
    'C'=> 'scissors'
];

const ME_TYPES = [
    'X' => 'rock',
    'Y' => 'paper',
    'Z'=> 'scissors'
];

const WIN_LOOKUP = [
    'rock' => 'paper',
    'paper' => 'scissors',
    'scissors' => 'rock'
];

$score = 0;

foreach ($input as $line) {
    [$opponent, $me] = explode(' ', $line);

    $score += match(ME_TYPES[$me]) {
        'rock' => 1,
        'paper' => 2,
        'scissors' => 3
    };

    $score += match (ME_TYPES[$me]) {
        OPPONENT_TYPES[$opponent] => 3,
        WIN_LOOKUP[OPPONENT_TYPES[$opponent]] => 6,
        default => 0
    };
}

print('Your total score: ' . $score . "\n");
