<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\OrderedSet;
use InvalidArgumentException;

class OrderedSetTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $orderedSet = new OrderedSet();
        $orderedSet->insert(5);
        $this->assertEquals(
            [5],
            $orderedSet->toArray()
        );
        $orderedSet->insert(0);
        $this->assertEquals(
            [5, 0],
            $orderedSet->toArray()
        );
        $orderedSet->insert(11);
        $this->assertEquals(
            [5, 0, 11],
            $orderedSet->toArray()
        );
        $orderedSet->insert(5);
        $this->assertEquals(
            [5, 0, 11],
            $orderedSet->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $orderedSet = new OrderedSet();
        $orderedSet->insert(2, 4, 6, 8, 10);
        $this->assertEquals(
            [2, 4, 6, 8, 10],
            $orderedSet->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $orderedSet = new OrderedSet();
        $orderedSet->insert(2, 4, 6, 8, 10);
        $orderedSet->delete(8);
        $orderedSet->delete(7);
        $this->assertEquals(
            [2, 4, 6, 10],
            $orderedSet->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $orderedSet = new OrderedSet();
        $orderedSet->insert(2, 4, 6, 8, 10);
        $orderedSet->delete(8, 10);
        $this->assertEquals(
            [2, 4, 6],
            $orderedSet->toArray()
        );
    }

    public function testInsertDuplicates()
    {
        $orderedSet = new OrderedSet();
        $orderedSet->insert(2, 4, 6, 8, 10);
        $orderedSet->insert(8, 10);
        $this->assertEquals(
            [2, 4, 6, 8, 10],
            $orderedSet->toArray()
        );
    }
}
