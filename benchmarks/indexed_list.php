<?php

require __DIR__ . '/../vendor/autoload.php';

use DanBallance\DataAlgorithms\IndexedList;
use DanBallance\DataAlgorithms\IndexedListLarge;

function benchmark($iterations, $valueMax, $instance) {
    $className = get_class($instance);
    echo "Running {$className} with {$iterations} iterations...\n";
    $starttime = microtime(true);
    $startMemory = memory_get_usage(); 
    for ($i = 0; $i < $iterations; $i++) {
        // insert, read, delete, read, insert, read
        $insertIndex = rand(1, $instance->getSize()) - 1;
        $value = rand(1, $valueMax);
        $instance->insertAt($insertIndex, $value);
        $readIndex = rand(1, $instance->getSize()) - 1;
        $instance->readAt($readIndex);
        $deleteIndex = rand(1, $instance->getSize()) - 1;
        $instance->deleteAt($deleteIndex);
        $readIndex = rand(1, $instance->getSize()) - 1;
        $instance->readAt($readIndex);
        $insertIndex = rand(1, $instance->getSize()) - 1;
        $value = rand(1, $valueMax);
        $instance->insertAt($insertIndex, $value);
        $readIndex = rand(1, $instance->getSize()) - 1;
        $instance->readAt($readIndex);
    }
    $endMemory = memory_get_usage(); 
    echo "...time to run: " . (microtime(true) - $starttime) * 1000, PHP_EOL;
    $memoryConsumed = ($endMemory - $startMemory) / (1024*1024); 
    $memoryConsumed = ceil($memoryConsumed); 
    echo "...memory used: {$memoryConsumed} MB\n\n";
    unset($instance);
}

$iterations = 75000;
$valueMax = 100000;

$instance = new IndexedListLarge(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
benchmark($iterations, $valueMax, $instance);

$instance = new IndexedList(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
benchmark($iterations, $valueMax, $instance);
