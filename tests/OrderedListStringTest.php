<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\OrderedListString;
use InvalidArgumentException;

class OrderedListStringTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $ol = new OrderedListString();
        $ol->insert('dog');
        $this->assertEquals(
            ['dog'],
            $ol->toArray()
        );
        $ol->insert('cat');
        $this->assertEquals(
            ['cat', 'dog'],
            $ol->toArray()
        );
        $ol->insert('fox');
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $ol = new OrderedListString();
        $ol->insert('dog', 'cat', 'fox');
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValuesReversed()
    {
        $ol = new OrderedListString($reversed = true);
        $ol->insert('dog', 'cat', 'fox');
        $this->assertEquals(
            ['fox', 'dog', 'cat'],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValuesReversedAndReverted()
    {
        $ol = new OrderedListString($reversed = true);
        $ol->insert('dog', 'cat', 'fox');
        $this->assertEquals(
            ['fox', 'dog', 'cat'],
            $ol->toArray()
        );
        $ol->reverse(false);
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $ol->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $ol = new OrderedListString();
        $ol->insert('dog', 'cat', 'fox');
        $ol->delete('cat');
        $this->assertEquals(
            ['dog', 'fox'],
            $ol->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $ol = new OrderedListString();
        $ol->insert('dog', 'cat', 'fox');
        $ol->delete('dog', 'fox');
        $this->assertEquals(
            ['cat'],
            $ol->toArray()
        );
    }

    public function testDeleteAtIndex()
    {
        $ol = new OrderedListString();
        $ol->insert('dog', 'cat', 'fox');
        $ol->deleteAt(1);
        $this->assertEquals(
            ['cat', 'fox'],
            $ol->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $ol = new OrderedListString();
        $ol->insert('dog', 'cat', 'fox', 123);
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $ol = new OrderedListString($reversed = false, $throwTypeErrors = false);
        $ol->insert('dog', 'cat', 'fox', 123);
        $this->assertEquals(
            ['cat', 'dog', 'fox'],
            $ol->toArray()
        );
    }
}