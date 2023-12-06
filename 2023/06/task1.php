<?php

[$time, $distance] = array_filter(explode("\n", file_get_contents('input.txt')), fn($l) => !empty($l));

$times = array_values(array_filter(explode(' ', trim(str_replace('Time: ', '', $time))), fn($t) => !empty($t)));
$distances = array_values(array_filter(explode(' ', trim(str_replace('Distance: ', '', $distance))), fn($t) => !empty($t)));

$count = count($times);
$total = 1;

for ($i = 0; $i < $count; $i++) {
    $working = 0;

    for ($t = 0; $t <= $times[$i]; $t++) {
        $distance = ($times[$i] - $t) * $t;
        if($distance > $distances[$i]) $working++;
    }

    $total *= $working;
}

echo "\n\nMultiplied total combinations: {$total}\n\n";
echo "Done.\n\n";

