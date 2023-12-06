<?php

[$time, $distance] = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

$times = array_values(array_filter(explode(' ', trim(str_replace('Time: ', '', $time))), fn($t) => !empty($t)));
$distances = array_values(array_filter(explode(' ', trim(str_replace('Distance: ', '', $distance))), fn($t) => !empty($t)));

$totalTime = (int)implode('', $times);
$totalDistance = (int)implode('', $distances);

$working = 0;

for ($t = 0; $t <= $totalTime; $t++) {
    $distance = ($totalTime - $t) * $t;
    if ($distance > $totalDistance) $working++;
}

echo "\n\nWorking ways: {$working}\n\n";
echo "Done.\n\n";

