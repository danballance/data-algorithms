<?php

require __DIR__ . '/../vendor/autoload.php';

use DanBallance\DataAlgorithms\IndexedList;
use DanBallance\DataAlgorithms\IndexedListLarge;
use DanBallance\DataAlgorithms\Benchmark;

function benchmark($iterations, $valueMax, $instance) {
    $className = get_class($instance);
    $testName = "{$className} with {$iterations} iterations";
    $benchmark = new Benchmark($testName);
    $benchmark->start();
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
    $benchmark->stop();
    $benchmark->report($toConsole = true);
    unset($instance);
}

$iterations = 10000;
$valueMax = 10000;

$instance = new IndexedListLarge(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
benchmark($iterations, $valueMax, $instance);

$instance = new IndexedList(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
benchmark($iterations, $valueMax, $instance);
