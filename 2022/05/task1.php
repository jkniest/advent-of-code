<?php

$input = explode("\n", file_get_contents(__DIR__.'/input.txt'));

$stacks = [];

for ($i = 0, $count = count($input); $i < $count; $i++) {
    if (empty($input[$i])) break;

    // Check for each three characters, ignorning the empty space between
    $stack = 1;
    for ($lI = 0, $lIMax = strlen($input[$i]); $lI < $lIMax; $lI += 4, $stack++) {
        $box = substr($input[$i], $lI, 3);
        if (empty(trim($box)) || !str_starts_with($box, '[')) {
            continue;
        }

        $stacks[$stack] ??= [];
        array_unshift($stacks[$stack], str_replace(['[', ']'], '', $box));
    }
}

for ($i = $i + 1, $iMax = count($input); $i < $iMax; $i++) {
    preg_match_all('/\d+/', $input[$i], $matches);
    [$amount, $from, $to] = $matches[0];

    for ($aI = 0; $aI < (int)$amount; $aI++) {
        $item = array_pop($stacks[(int)$from]);
        $stacks[(int)$to][] = $item;
    }
}

ksort($stacks);

$tops = array_map(
    fn(array $items) => $items[count($items) - 1],
    $stacks
);

print('Top stacks: ' . implode('', $tops) . "\n");
