<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

const LOOKUP = [
    'A' => 13,
    'K' => 12,
    'Q' => 11,
    'T' => 10,
    '9' => 9,
    '8' => 8,
    '7' => 7,
    '6' => 6,
    '5' => 5,
    '4' => 4,
    '3' => 3,
    '2' => 2,
    'J' => 1,
];

function ruleFiveOfAKind(string $hand, array $counts): bool
{
    return count($counts) === 1
        || (count($counts) === 2 && in_array('J', str_split($hand)));
}

function ruleFourOfAKind(string $hand, array $counts): bool
{
    if(in_array(4, $counts)) {
        return true;
    }

    if(!array_key_exists('J', $counts)) {
        return false;
    }

    foreach ($counts as $char => $count)
    {
        if($char === 'J') continue;

        if($counts['J'] === 4 - $count) return true;
    }

    return false;
}

function ruleFullHouse(string $hand, array $counts): bool
{
    return (in_array(2, $counts) && in_array(3, $counts))
        || (count(array_filter($counts, fn($c) => $c === 2)) === 2 && in_array('J', str_split($hand)));
}

function ruleThreeOfAKind(string $hand, array $counts): bool
{
    return in_array(3, $counts)
        || (in_array(2, $counts) && in_array('J', str_split($hand)));
}

function ruleTwoPairs(string $hand, array $counts): bool
{
    return count(array_filter($counts, fn($c) => $c === 2)) === 2;
}

function ruleOnePair(string $hand, array $counts): bool
{
    return in_array(2, $counts)
        || in_array('J', str_split($hand));
}

function evaluateRules(string $hand): int
{
    $count = getCharCount($hand);

    // Rule 1 - Five of a kind
    if (ruleFiveOfAKind($hand, $count)) {
        return 60000;
    }

    // Rule 2 - Four of a kind
    if (ruleFourOfAKind($hand, $count)) {
        return 50000;
    }

    // Rule 3 - Full house
    if (ruleFullHouse($hand, $count)) {
        return 40000;
    }

    // Rule 4 - Three of a kind
    if (ruleThreeOfAKind($hand, $count)) {
        return 30000;
    }

    // Rule 5 - Two pair
    if (ruleTwoPairs($hand, $count)) {
        return 20000;
    }

    // Rule 6 - One pair
    if (ruleOnePair($hand, $count)) {
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

usort($cards, function (Card $a, Card $b) {
    if ($a->score === $b->score) {
        for ($i = 0; $i < 5; $i++) {
            if ($a->hand[$i] === $b->hand[$i]) {
                continue;
            }

            return LOOKUP[$a->hand[$i]] <=> LOOKUP[$b->hand[$i]];
        }
    }

    return $a->score <=> $b->score;
});

$total = 0;

foreach ($cards as $idx => $card) {
    $total += ($card->bid * ($idx + 1));
}

echo "\n\nTotal Winnings: {$total}\n\n";
echo "Done.\n\n";
