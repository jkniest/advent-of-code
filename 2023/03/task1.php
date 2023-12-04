<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

$sum = 0;
$lineCount = count($lines);

for ($i = 0; $i < $lineCount; $i++) {
    $line = $lines[$i];

    $matches = [];
    preg_match_all('/\d+/', $line, $matches, PREG_OFFSET_CAPTURE);

    $lineBefore = $lines[$i - 1] ?? str_repeat(' ', 140);
    $lineAfter = $lines[$i + 1] ?? str_repeat(' ', 140);

    foreach ($matches[0] as $match) {
        [$number, $position] = $match;

        $indexStart = $position;
        $indexEnd = $indexStart + strlen($number) - 1;

        if(checkLine($indexStart, $indexEnd, $line)) {
            $sum += (int)$number;
            continue;
        }

        if($i > 0 && checkLine($indexStart, $indexEnd, $lines[$i - 1])) {
            $sum += (int)$number;
            continue;
        }

        if($i < $lineCount - 1 && checkLine($indexStart, $indexEnd, $lines[$i + 1])) {
            $sum += (int)$number;
        }
    }
}

function checkLine(int $start, int $end, string $line): bool {
    for ($i = $start - 1; $i <= $end + 1; $i++) {
        if($i < 0 || $i >= strlen($line)) {
            continue;
        }

        if(!is_numeric($line[$i]) && $line[$i] !== '.') {
            return true;
        }
    }

    return false;
}

echo "\n\nTotal summed: {$sum}\n\n";
echo "Done.\n\n";
