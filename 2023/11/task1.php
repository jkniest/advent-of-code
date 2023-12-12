<?php

$lines = array_values(array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l)));
$lineCount = count($lines);
$lineLength = strlen($lines[0]);

class Node
{
    /** @var array<Node> */
    public array $checked = [];

    public int $nY;
    public int $nX;

    public function __construct(
        public int $x,
        public int $y
    )
    {
        $this->nY = $y;
        $this->nX = $x;
    }
}

/** @var Node[] $nodes */
$nodes = [];

for ($y = 0; $y < $lineCount; $y++) {
    $line = $lines[$y];
    $lineLength = strlen($line);

    for ($x = 0; $x < $lineLength; $x++) {
        if ($line[$x] === '#') {
            $nodes[] = new Node($x, $y);
        }
    }
}

for ($y = 0; $y < $lineCount; $y++) {
    $line = $lines[$y];
    if(str_contains($line, '#')) continue;

    foreach ($nodes as $node) {
        if($node->y < $y) continue;

        $node->nY++;
    }
}

for ($x = 0; $x < $lineLength; $x++) {
    for ($y = 0; $y < $lineCount; $y++) {
        if($lines[$y][$x] === '#') {
            continue 2;
        }
    }

    foreach ($nodes as $node) {
        if($node->x < $x) continue;

        $node->nX++;
    }
}

$sum = 0;

foreach ($nodes as $node1) {
    foreach ($nodes as $node2) {
        if (in_array($node2, $node1->checked, true)) continue;

        $sum += (abs($node1->nX - $node2->nX) + abs($node1->nY - $node2->nY));
        $node1->checked[] = $node2;
        $node2->checked[] = $node1;
    }
}

echo "\n\nSum of shortest routes: {$sum}\n\n";
echo "Done.\n\n";
