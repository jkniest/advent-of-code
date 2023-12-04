<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

$sum = 0;
$lineCount = count($lines);

for ($i = 0; $i < $lineCount; $i++) {
    $line = $lines[$i];

    $matches = [];
    preg_match_all('/\*+/', $line, $matches, PREG_OFFSET_CAPTURE);

    $lineBefore = $lines[$i - 1] ?? str_repeat(' ', 140);
    $lineAfter = $lines[$i + 1] ?? str_repeat(' ', 140);

    foreach ($matches[0] as $match) {
        [$_, $position] = $match;
        $matchNumbers = checkLine($position, $line);

        if ($i > 0) {
            $matchNumbers = [...$matchNumbers, ...checkLine($position, $lineBefore)];
        }

        if ($i < $lineCount - 1) {
            $matchNumbers = [...$matchNumbers, ...checkLine($position, $lineAfter)];
        }

        if (count($matchNumbers) < 2) {
            continue;
        }

        $mul = $matchNumbers[0];
        for ($q = 1; $q < count($matchNumbers); $q++) {
            $mul *= $matchNumbers[$q];
        }

        $sum += $mul;
    }
}

function checkLine(int $position, string $line): array
{
    // Get all numbers within the given line
    $matches = [];
    preg_match_all('/\d+/', $line, $matches, PREG_OFFSET_CAPTURE);

    // Check if the number is between position -1 and position + 1
    $result = [];
    foreach ($matches[0] as $match) {
        [$number, $nPos] = $match;

        $nLen = strlen($number);
        for ($i = 0; $i < $nLen; $i++) {
            if($nPos + $i >= $position - 1 && $nPos + $i <= $position + 1) {
                $result[] = $number;
                break;
            }
        }
    }

    return $result;
}

echo "\n\nTotal summed: {$sum}\n\n";
echo "Done.\n\n";
