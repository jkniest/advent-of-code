<?php

$input = file_get_contents(__DIR__.'/input.txt');
$count = strlen($input);

for($i = 3; $i < $count; $i++) {
    $buffer = [];
    for ($n = $i; $n >= $i - 3; $n--) {
        if (in_array($input[$n], $buffer)) continue 2;
        $buffer[] = $input[$n];
    }
    break;
}

print('First marker at character: ' . ($i + 1) . "\n");