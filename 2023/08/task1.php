<?php

$lines = array_values(array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l)));

$instructions = $lines[0];
$count = count($lines);

readonly class Node {
    public function __construct(
        public string $left,
        public string $right
    ) {}
}

$network = [];

for ($i = 1; $i < $count; $i++) {
    [$node, $next] = array_map(fn(string $s) => trim($s), explode('=', $lines[$i]));
    [$left, $right] = array_map(fn(string $s) => trim($s), explode(',', substr($next, 1, strlen($next) - 2)));

    $network[$node] = new Node($left, $right);
}

$steps = 0;
$instructionIdx = 0;
$found = false;
$currentNode = $network['AAA'];

for ($i = 0; $i < 10000000; $i++) // Fail safe for endless loops
{
    ++$steps;
    $way = $instructions[$instructionIdx++];
    if($instructionIdx === strlen($instructions)) {
        $instructionIdx = 0;
    }

    if($way === 'R') {
        if($currentNode->right === 'ZZZ') {
            $found = true;
            break;
        }

        $currentNode = $network[$currentNode->right];
    } else {
        if($currentNode->left === 'ZZZ') {
            $found = true;
            break;
        }

        $currentNode = $network[$currentNode->left];
    }
}

if(!$found) {
    echo "\n\n!!! No way found !!!!\n\n";
    die;
}

echo "\n\nSteps required: {$steps}\n\n";
echo "Done.\n\n";
