<?php

$input = explode("\n", file_get_contents('input.txt'));

const LOOKUP = [
    'one' => '1',
    'two' => '2',
    'three' => '3',
    'four' => '4',
    'five' => '5',
    'six' => '6',
    'seven' => '7',
    'eight' => '8',
    'nine' => '9'
];

$total = 0;
foreach($input as $line) {
    $numbers = [];
    for($offset = 0; $offset < strlen($line); $offset++) {
        if(is_numeric($line[$offset])) {
            $numbers[] = $line[$offset];
            continue;
        }

        foreach(LOOKUP as $key => $replace) {
            if(substr($line, $offset, strlen($key)) == $key) {
                $numbers[] = $replace;
                break;
            }
        }
    }

    $total += (int)(current($numbers).end($numbers));
}

echo "\n\nTotal number totalmed: {$total}\n\n";
echo "Done.\n\n";