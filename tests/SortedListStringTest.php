<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\SortedListString;
use InvalidArgumentException;

class SortedListStringTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $sortedList = new SortedListString();
        $sortedList->insert('dog');
        $this->assertEquals(
            ['dog'],
            $sortedList->toArray()
        );
        $sortedList->insert('cat');
        $this->assertEquals(
            ['cat', 'dog'],
            $sortedList->toArray()
        );
        $sortedList->insert('fox');
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $sortedList = new SortedListString();
        $sortedList->insert('dog', 'cat', 'fox');
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversed()
    {
        $sortedList = new SortedListString($reversed = true);
        $sortedList->insert('dog', 'cat', 'fox');
        $this->assertEquals(
            ['fox', 'dog', 'cat'],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversedAndReverted()
    {
        $sortedList = new SortedListString($reversed = true);
        $sortedList->insert('dog', 'cat', 'fox');
        $this->assertEquals(
            ['fox', 'dog', 'cat'],
            $sortedList->toArray()
        );
        $sortedList->reverse(false);
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $sortedList->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $sortedList = new SortedListString();
        $sortedList->insert('dog', 'cat', 'fox');
        $sortedList->delete('cat');
        $this->assertEquals(
            ['dog', 'fox'],
            $sortedList->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $sortedList = new SortedListString();
        $sortedList->insert('dog', 'cat', 'fox');
        $sortedList->delete('dog', 'fox');
        $this->assertEquals(
            ['cat'],
            $sortedList->toArray()
        );
    }

    public function testDeleteAtIndex()
    {
        $sortedList = new SortedListString();
        $sortedList->insert('dog', 'cat', 'fox');
        $sortedList->deleteAt(1);
        $this->assertEquals(
            ['cat', 'fox'],
            $sortedList->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $sortedList = new SortedListString();
        $sortedList->insert('dog', 'cat', 'fox', 123);
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $sortedList = new SortedListString($reversed = false, $throwTypeErrors = false);
        $sortedList->insert('dog', 'cat', 'fox', 123);
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $sortedList->toArray()
        );
    }

    public function testInsertDuplicates()
    {
        $sortedList = new SortedListString();
        $sortedList->insert('dog', 'cat', 'dog', 'fox');
        $sortedList->insert('fox');
        $this->assertEquals(
            ['cat', 'dog', 'dog', 'fox', 'fox'],
            $sortedList->toArray()
        );
    }
}