<?php

const TOTAL = [
    'red' => 12,
    'green' => 13,
    'blue' => 14
];

$games = explode("\n", file_get_contents('input.txt'));

$idSum = 0;
foreach ($games as $game) {
    if(empty($game)) {
        continue;
    }

    [$idLabel, $rounds] = explode(':', $game);

    $idMatches = [];
    preg_match('/Game (\d+)/', $idLabel, $idMatches);
    $id = (int) $idMatches[1];

    $min = [
        'red' => 0,
        'green' => 0,
        'blue' => 0
    ];

    $roundsSplit = explode(';', $rounds);
    foreach ($roundsSplit as $round) {
        $roundMatches = [];
        preg_match_all('/((\d+) ([a-z]+),?)+/', trim($round), $roundMatches);

        $amounts = $roundMatches[2];
        $colors = $roundMatches[3];

        $count = count($amounts);

        for ($i = 0; $i < $count; $i++) {
            $amount = (int) $amounts[$i];
            $color = $colors[$i];

            if($amount > $min[$color]) {
                $min[$color] = $amount;
            }
        }
    }

    $idSum += ($min['red'] * $min['green'] * $min['blue']);
}

echo "\n\nTotal game IDs summed: {$idSum}\n\n";
echo "Done.\n\n";
