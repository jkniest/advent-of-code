<?php

$lines = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

readonly class Seed
{
    public function __construct(
        public int $start,
        public int $length
    )
    {

    }
}

readonly class Map
{
    public function __construct(
        public int $destRangeStart,
        public int $sourceRangeStart,
        public int $length
    )
    {
    }

    public function contains(Seed|int $number): bool
    {
        if ($number instanceof Seed) {
            return $number->start >= $this->sourceRangeStart && $number->start + $number->length < $this->sourceRangeStart + $this->length;
        }

        return $number >= $this->sourceRangeStart && $number < $this->sourceRangeStart + $this->length;
    }

    public function convert(Seed|int $number): int
    {
        if ($number instanceof Seed) {
            var_dump($number->start, $number->length, $this->sourceRangeStart);
            $number = max($this->sourceRangeStart, $number->start);
            var_dump($number);
            var_dump('---');
        }

        return $this->destRangeStart + ($number - $this->sourceRangeStart);
    }
}

/** @var array<array<Map>> $ranges */
$ranges = [];
$seeds = [];

$rangeIdx = 0;
foreach ($lines as $line) {
    if (str_starts_with($line, 'seeds: ')) continue;

    if (str_ends_with($line, ':')) {
        $rangeIdx++;
        continue;
    }

    [$destRangeStart, $sourceRangeStart, $length] = explode(' ', $line);

    $ranges[$rangeIdx] ??= [];
    $ranges[$rangeIdx][] = new Map((int)$destRangeStart, (int)$sourceRangeStart, (int)$length);
}

$lowestLocation = PHP_INT_MAX;

$s = explode(' ', substr($lines[0], strlen('Seeds: ')));

$i = (int)$argv[1];

echo "Seed started...\n";
$step = 0;
for ($j = (int)$s[$i]; $j < (int)$s[$i] + (int)$s[$i + 1]; $j++) {
    $seed = $j;

    $lastVal = $seed;
    foreach ($ranges as $range) {
        foreach ($range as $r) {
            if (!$r->contains($lastVal)) {
                continue;
            }

            $lastVal = $r->convert($lastVal);
            break;
        }
    }

    if ($lastVal < $lowestLocation) {
        $lowestLocation = $lastVal;
    }

    if (++$step % 50000 === 0) {
        echo ".";
    }
}
echo "\n\nSeed finished...\n";
echo "Current lowest: {$lowestLocation}\n\n";
echo "Done.\n\n";
