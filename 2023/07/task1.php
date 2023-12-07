<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

const LOOKUP = [
    'A' => 13,
    'K' => 12,
    'Q' => 11,
    'J' => 10,
    'T' => 9,
    '9' => 8,
    '8' => 7,
    '7' => 6,
    '6' => 5,
    '5' => 4,
    '4' => 3,
    '3' => 2,
    '2' => 1,
];

function evaluateRules(string $hand): int
{
    // Rule 1 - Five of a kind
    if (str_repeat($hand[0], 5) === $hand) {
        return 60000;
    }

    // Rule 2 - Four of a kind
    $count = getCharCount($hand);
    if (in_array(4, $count)) {
        return 50000;
    }

    // Rule 3 - Full house
    if (in_array(2, $count) && in_array(3, $count)) {
        return 40000;
    }

    // Rule 4 - Three of a kind
    if (in_array(3, $count)) {
        return 30000;
    }

    // Rule 5 - Two pair
    if (count(array_filter($count, fn($c) => $c === 2)) === 2) {
        return 20000;
    }

    // Rule 6 - One pair
    if (in_array(2, $count)) {
        return 10000;
    }

    return 0;
}

function getCharCount(string $input): array
{
    $characters = [];

    foreach (str_split($input) as $char) {
        $characters[$char] ??= 0;
        $characters[$char]++;
    }

    return $characters;
}

readonly class Card
{
    public function __construct(
        public string $hand,
        public int    $bid,
        public int    $score
    )
    {
    }
}

$cards = [];

foreach ($lines as $line) {
    [$hand, $bid] = explode(' ', $line);

    $cards[] = new Card(
        $hand,
        (int)$bid,
        evaluateRules($hand)
    );
}

usort($cards, function(Card $a, Card $b) {
    if($a->score === $b->score) {
        for ($i = 0; $i < 5; $i++) {
            if($a->hand[$i] === $b->hand[$i]) {
                continue;
            }

            return LOOKUP[$a->hand[$i]] <=> LOOKUP[$b->hand[$i]];
        }
    }

    return $a->score <=> $b->score;
});

$total = 0;

foreach ($cards as $idx => $card)
{
    $total += ($card->bid * ($idx + 1));
}

echo "\n\nTotal Winnings: {$total}\n\n";
echo "Done.\n\n";
