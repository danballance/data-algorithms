<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\OrderedListNumeric;
use InvalidArgumentException;

class OrderedListNumericTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $ol = new OrderedListNumeric();
        $ol->insert(5);
        $this->assertEquals(
            [5],
            $ol->toArray()
        );
        $ol->insert(0);
        $this->assertEquals(
            [0, 5],
            $ol->toArray()
        );
        $ol->insert(11);
        $this->assertEquals(
            [0, 5, 11],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $ol = new OrderedListNumeric();
        $ol->insert(1, 3, 4, 7, 10);
        $ol->insert(5, 0, 11);
        $this->assertEquals(
            [0, 1, 3, 4, 5, 7, 10, 11],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValuesReversed()
    {
        $ol = new OrderedListNumeric($reversed = true);
        $ol->insert(5, 0, 11);
        $this->assertEquals(
            [11, 5, 0],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValuesReversedAndReverted()
    {
        $ol = new OrderedListNumeric($reversed = true);
        $ol->insert(1, 3, 4, 7, 10);
        $ol->insert(5, 0, 11);
        $this->assertEquals(
            [11, 10, 7, 5, 4, 3, 1, 0],
            $ol->toArray()
        );
        $ol->reverse(false);
        $this->assertEquals(
            [0, 1, 3, 4, 5, 7, 10, 11],
            $ol->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $ol = new OrderedListNumeric();
        $ol->insert(1, 3, 4, 7, 10);
        $ol->delete(7);
        $this->assertEquals(
            [1, 3, 4, 10],
            $ol->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $ol = new OrderedListNumeric();
        $ol->insert(1, 3, 4, 7, 10);
        $ol->delete(7, 1);
        $this->assertEquals(
            [3, 4, 10],
            $ol->toArray()
        );
    }

    public function testDeleteAtIndex()
    {
        $ol = new OrderedListNumeric();
        $ol->insert(1, 3, 4, 7, 10);
        $ol->deleteAt(1);
        $this->assertEquals(
            [1, 4, 7, 10],
            $ol->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $ol = new OrderedListNumeric();
        $ol->insert(1, 3, 4, 7, 10, 'oh noes');
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $ol = new OrderedListNumeric($reversed = false, $throwTypeErrors = false);
        $ol->insert(1, 3, 4, 7, 10, 'oh noes');
        $this->assertEquals(
            [1, 3, 4, 7, 10],
            $ol->toArray()
        );
    }
}