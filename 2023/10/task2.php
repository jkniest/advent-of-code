<?php

$lines = array_values(array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l)));
$lineCount = count($lines);

const NONE = 0b0000;
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
    public bool $explored = false;

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
$startTile->explored = true;

$safeGuard = 10000000; // To prevent endless loops

class Step
{
    public function __construct(
        public Tile $tile,
        public int  $startDirection
    )
    {
    }
}

$step = null;

/** @var Tile[] $polygon */
$polygon = [$startTile];

// Add first start steps
if ($startTile->right()) $step = new Step($startTile, RIGHT);
elseif ($startTile->bottom()) $step = new Step($startTile, BOTTOM);
elseif ($startTile->left()) $step = new Step($startTile, LEFT);
elseif ($startTile->top()) $step = new Step($startTile, TOP);

for ($i = 0; $i < $safeGuard; $i++) {
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
    if ($newTile->explored) break;

    $newDirection = $startInvert ^ $newTile->directions;
    $newTile->explored = true;

    $step->tile = $newTile;
    $step->startDirection = $newDirection;

    $polygon[] = $newTile;
}

class Point
{
    public function __construct(public int $x, public int $y)
    {
    }
}

function angle2d(Point $a, Point $b)
{
    $theta1 = atan2($a->y, $a->x);
    $theta2 = atan2($b->y, $b->x);
    $dtheta = $theta2 - $theta1;
    while ($dtheta > M_PI) $dtheta -= (M_PI * 2);
    while ($dtheta < -M_PI) $dtheta += (M_PI * 2);

    return $dtheta;
}

/** @param Point[] $polygon */
function inside(array $polygon, Point $p): bool
{
    $angle = 0.0;
    $p1 = new Point(0, 0);
    $p2 = new Point(0, 0);
    $n = count($polygon);

    for ($i = 0; $i < $n; $i++) {
        $p1->x = $polygon[$i]->x - $p->x;
        $p1->y = $polygon[$i]->y - $p->y;
        $p2->x = $polygon[($i + 1) % $n]->x - $p->x;
        $p2->y = $polygon[($i + 1) % $n]->y - $p->y;
        $angle += angle2d($p1, $p2);
    }

    return abs($angle) >= M_PI;
}

$vertices = array_map(
    fn(Tile $tile) => new Point($tile->x, $tile->y),
    $polygon
);

$withinLoop = 0;

for ($y = 0; $y < $lineCount; $y++) {
    $line = $lines[$y];
    for ($x = 0; $x < $lineLength; $x++) {
        $tile = $tiles[$y * $lineLength + $x];

        if (in_array($tile, $polygon)) continue;
        if (!inside($vertices, new Point($tile->x, $tile->y))) continue;

        ++$withinLoop;
    }
}


echo "\n\nTiles within loop: {$withinLoop}\n\n";
echo "Done.\n\n";
