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

// Get all current nodes in one big array
$currentNodes = array_values(array_map(
    fn(string $key) => $network[$key],
    array_filter(array_keys($network), fn(string $key) => str_ends_with($key, 'A'))
));

$nodeCount = count($currentNodes);
$neededSteps = [];

for ($n = 0; $n < $nodeCount; $n++) {
    $steps = 0;
    $instructionIdx = 0;
    $found = false;

    for ($i = 0; $i < 10000000000; $i++) // Fail safe for endless loops
    {
        ++$steps;

        $way = $instructions[$instructionIdx++];
        if ($instructionIdx === strlen($instructions)) {
            $instructionIdx = 0;
        }

        if ($way === 'R') {
            if (str_ends_with($currentNodes[$n]->right, 'Z'))
            {
                break;
            }
            $currentNodes[$n] = $network[$currentNodes[$n]->right];
        } else {
            if (str_ends_with($currentNodes[$n]->left, 'Z'))
            {
                break;
            }
            $currentNodes[$n] = $network[$currentNodes[$n]->left];
        }
    }

    $neededSteps[] = $steps;
}

print_r($neededSteps); // Use LCM calculator :P
// https://www.calculatorsoup.com/calculators/math/lcm.php
