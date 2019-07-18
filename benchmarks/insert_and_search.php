<?php

require __DIR__ . '/../vendor/autoload.php';

use DanBallance\DataAlgorithms\SortedList;
use DanBallance\DataAlgorithms\BinaryTree;
use DanBallance\DataAlgorithms\Benchmark;

// set up SortedList instance
$cbTypeCheck = function ($value) {
    return is_object($value);
};
$cbCompare = function(string $a, Object $b) {
    return strcmp($a, $b->name);
};
$cbSort = function (object $a, object $b) {
    return strcmp($a->name, $b->name);
};
$cbSortRev = function (object $a, object $b) {
    return strcmp($a->name, $b->name) * -1;
};
$sortedList = new SortedList(
    $reversed = false,
    $throwTypeErrors = true,
    $cbTypeCheck,
    $cbCompare,
    $cbSort,
    $cbSortRev
);

// set up BinaryTree instance
$funTypeCheck = function ($value) {
    return is_object($value);
};
$funCompare = function(Object $a, Object $b) {
    return strcmp($a->name, $b->name);
};
$funKeySearch = function(string $key, Object $b) {
    return strcmp($key, $b->name);
};
$binaryTree = new BinaryTree(
    $funTypeCheck,
    $funCompare,
    $funKeySearch
);

/**
 * function for quickly creating an object to insert into the data structures
 */
function makeObj(int $i) {
    return (object)[
        'name' => sha1($i),
        'val' => $i
    ];
}

// benchmark parameters
$arraySize = 50000;
$numSearches = 1000;

// base data
$data = [];
for ($i = 0; $i < $arraySize; $i++) {
    $data[] = makeObj($i);
}
shuffle($data);

// Array test
$testName = "PHP Array - {$numSearches} searches on shuffled array of size {$arraySize}";
$benchmark = new Benchmark($testName);
$benchmark->start();
$array = $data;
for ($i = 0; $i < $numSearches; $i++) {
    $lookup = rand(0, $arraySize);
    $result = array_search(sha1($lookup), array_column($array, 'name'));
    assert($result->val === $lookup);
}
$benchmark->stop();
$benchmark->report($toConsole = true);
unset($array);

// SortedList test
$testName = "SortedList - {$numSearches} searches on shuffled array of size {$arraySize}";
$benchmark = new Benchmark($testName);
$benchmark->start();
$sortedList->insert(...$data);
for ($i = 0; $i < $numSearches; $i++) {
    $lookup = rand(0, $arraySize);
    $result = $sortedList->search(sha1($lookup));
    assert($result->val === $lookup);
}
$benchmark->stop();
$benchmark->report($toConsole = true);
unset($sortedList);

// BinaryTree test
$testName = "BinaryTree - {$numSearches} searches on shuffled (i.e. balanced) array of size {$arraySize}";
$benchmark = new Benchmark($testName);
$benchmark->start();
$binaryTree->insert(...$data);
for ($i = 0; $i < $numSearches; $i++) {
    $lookup = rand(0, $arraySize);
    $result = $binaryTree->searchByKey(sha1($lookup));
    assert($result->val === $lookup);
}
$benchmark->stop();
$benchmark->report($toConsole = true);
unset($binaryTree);