<?php

require_once __DIR__.'/vendor/autoload.php';

$class = 'AOC\\Days\\Day'.$argv[1];

$instance = new $class();

if (in_array('--test', $argv)) {
    $instance->run(true);
} else {
    $instance->run();
}
