<?php

$lines = array_values(array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l)));
$lineCount = count($lines);

const LEFT = 0b1000;
const RIGHT = 0b0100;
const TOP = 0b0010;
const BOTTOM = 0b0001;

const LOOKUP = [
    '|' => TOP | BOTTOM,
    '-' => LEFT | RIGHT,
    'L' => TOP | RIGHT,
    'J' => TOP | LEFT,
    '7' => LEFT | BOTTOM,
    'F' => RIGHT | BOTTOM,
    '.' => 0b0000
];

class Tile
{
    public ?int $score = null;

    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public int          $directions
    )
    {
    }

    public function left(): bool
    {
        return $this->directions & LEFT;
    }

    public function right(): bool
    {
        return $this->directions & RIGHT;
    }

    public function top(): bool
    {
        return $this->directions & TOP;
    }

    public function bottom(): bool
    {
        return $this->directions & BOTTOM;
    }
}

/** @var Tile[] $tiles */
$tiles = [];
$startX = $startY = 0;
$lineLength = 0;

// Build whole map
for ($y = 0; $y < $lineCount; $y++) {
    $line = $lines[$y];
    $lineLength = strlen($line);
    for ($x = 0; $x < $lineLength; $x++) {
        $direction = LOOKUP[$line[$x]] ?? 0b0000;
        if ($line[$x] === 'S') {
            $startX = $x;
            $startY = $y;
        }

        $tiles[$y * $lineLength + $x] = new Tile($x, $y, LOOKUP[$line[$x]] ?? 0b0000);
    }
}

// Evaluate starting point directions
$startDirections = 0b0000;

if ($startY > 0 && $tiles[($startY - 1) * $lineLength + $startX]->bottom()) $startDirections |= TOP;
if ($startY < $lineCount - 1 && $tiles[($startY + 1) * $lineLength + $startX]->top()) $startDirections |= BOTTOM;
if ($startX > 0 && $tiles[$startY * $lineLength + ($startX - 1)]->right()) $startDirections |= LEFT;
if ($startX < $lineLength - 1 && $tiles[$startY * $lineLength + ($startX + 1)]->left()) $startDirections |= RIGHT;

$startTile = $tiles[$startY * $lineLength + $startX];

$startTile->directions = $startDirections;
$startTile->score = 0;

$safeGuard = 100000000; // To prevent endless loops

class Step
{
    public function __construct(
        public Tile $tile,
        public int  $startDirection
    )
    {
    }
}

$steps = [];

// Add first start steps
if ($startTile->left()) $steps[] = new Step($startTile, LEFT);
if ($startTile->top()) $steps[] = new Step($startTile, TOP);
if ($startTile->right()) $steps[] = new Step($startTile, RIGHT);
if ($startTile->bottom()) $steps[] = new Step($startTile, BOTTOM);

for ($i = 0; $i < $safeGuard; $i++) {
    // Basically for each step go into the correct direction and update the tile accordingly
    foreach ($steps as $step) {
        $newX = $step->tile->x;
        $newY = $step->tile->y;
        $startInvert = 0b0000;

        switch ($step->startDirection) {
            case LEFT:
                $newX--;
                $startInvert = RIGHT;
                break;

            case RIGHT:
                $newX++;
                $startInvert = LEFT;
                break;

            case TOP:
                $newY--;
                $startInvert = BOTTOM;
                break;

            case BOTTOM:
                $newY++;
                $startInvert = TOP;
                break;
        }


        $newTile = $tiles[$newY * $lineLength + $newX];
        if($newTile->score !== null) break 2;

        $newDirection = $startInvert ^ $newTile->directions;
        $newTile->score = $i + 1;

        $step->tile = $newTile;
        $step->startDirection = $newDirection;
    }
}

$scores = array_filter(array_map(
    fn(Tile $tile) => $tile->score,
    $tiles
));

sort($scores);

$longest = end($scores);

echo "\n\nLongest route: {$longest}\n\n";
echo "Done.\n\n";
