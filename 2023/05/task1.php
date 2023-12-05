<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

readonly class Map
{
    public function __construct(
        public int $destRangeStart,
        public int $sourceRangeStart,
        public int $length
    )
    {
    }

    public function contains(int $number): bool
    {
        return $number >= $this->sourceRangeStart && $number < $this->sourceRangeStart + $this->length;
    }

    public function convert(int $number): int
    {
        return $this->destRangeStart + ($number - $this->sourceRangeStart);
    }
}

/** @var array<array<Map>> $ranges */
$ranges = [];
$seeds = [];

$rangeIdx = 0;
foreach ($lines as $line) {
    if (str_starts_with($line, 'seeds:')) {
        $seeds = explode(' ', substr($line, strlen('Seeds: ')));
        continue;
    }

    if (str_ends_with($line, ':')) {
        $rangeIdx++;
        continue;
    }

    [$destRangeStart, $sourceRangeStart, $length] = explode(' ', $line);

    $ranges[$rangeIdx] ??= [];
    $ranges[$rangeIdx][] = new Map((int)$destRangeStart, (int)$sourceRangeStart, (int)$length);
}

$lowestLocation = PHP_INT_MAX;

foreach ($seeds as $seed) {
    $lastVal = $seed;
    foreach ($ranges as $range) {
        foreach ($range as $r) {
            if(!$r->contains($lastVal)) {
                continue;
            }

            $lastVal = $r->convert($lastVal);
            break;
        }
    }

    if($lastVal < $lowestLocation) {
        $lowestLocation = $lastVal;
    }
}

echo "\n\nSmallest possible location: {$lowestLocation}\n\n";
echo "Done.\n\n";
