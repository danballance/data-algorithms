<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\SortedListNumeric;
use InvalidArgumentException;

class SortedListNumericTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $sortedList = new SortedListNumeric();
        $sortedList->insert(5);
        $this->assertEquals(
            [5],
            $sortedList->toArray()
        );
        $sortedList->insert(0);
        $this->assertEquals(
            [0, 5],
            $sortedList->toArray()
        );
        $sortedList->insert(11);
        $this->assertEquals(
            [0, 5, 11],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $sortedList = new SortedListNumeric();
        $sortedList->insert(1, 3, 4, 7, 10);
        $sortedList->insert(5, 0, 11);
        $this->assertEquals(
            [0, 1, 3, 4, 5, 7, 10, 11],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversed()
    {
        $sortedList = new SortedListNumeric($reversed = true);
        $sortedList->insert(5, 0, 11);
        $this->assertEquals(
            [11, 5, 0],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversedAndReverted()
    {
        $sortedList = new SortedListNumeric($reversed = true);
        $sortedList->insert(1, 3, 4, 7, 10);
        $sortedList->insert(5, 0, 11);
        $this->assertEquals(
            [11, 10, 7, 5, 4, 3, 1, 0],
            $sortedList->toArray()
        );
        $sortedList->reverse(false);
        $this->assertEquals(
            [0, 1, 3, 4, 5, 7, 10, 11],
            $sortedList->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $sortedList = new SortedListNumeric();
        $sortedList->insert(1, 3, 4, 7, 10);
        $sortedList->delete(7);
        $this->assertEquals(
            [1, 3, 4, 10],
            $sortedList->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $sortedList = new SortedListNumeric();
        $sortedList->insert(1, 3, 4, 7, 10);
        $sortedList->delete(7, 1);
        $this->assertEquals(
            [3, 4, 10],
            $sortedList->toArray()
        );
    }

    public function testDeleteAtIndex()
    {
        $sortedList = new SortedListNumeric();
        $sortedList->insert(1, 3, 4, 7, 10);
        $sortedList->deleteAt(1);
        $this->assertEquals(
            [1, 4, 7, 10],
            $sortedList->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $sortedList = new SortedListNumeric();
        $sortedList->insert(1, 3, 4, 7, 10, 'oh noes');
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $sortedList = new SortedListNumeric($reversed = false, $throwTypeErrors = false);
        $sortedList->insert(1, 3, 4, 7, 10, 'oh noes');
        $this->assertEquals(
            [1, 3, 4, 7, 10],
            $sortedList->toArray()
        );
    }

    public function testInsertDuplicates()
    {
        $sortedList = new SortedListNumeric();
        $sortedList->insert(1, 3, 4, 7, 7, 10);
        $sortedList->insert(3, 3);
        $this->assertEquals(
            [1, 3, 3, 3, 4, 7, 7, 10],
            $sortedList->toArray()
        );
    }

    public function testFind()
    {
        $sortedList = new SortedListNumeric();
        $sortedList->insert(1, 3, 4, 7, 10, 13, 17, 18, 20);
        $this->assertEquals(
            7,
            $sortedList->search(7)
        );
    }
}