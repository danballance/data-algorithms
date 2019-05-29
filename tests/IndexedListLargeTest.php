<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\IndexedListLarge;

class IndexedListLargeTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructionFromMultipleArguments()
    {
        $il = new IndexedListLarge(1, 3, 4, 7, 10);
        $this->assertEquals(
            [1, 3, 4, 7, 10],
            $il->toArray()
        );
        $this->assertEquals(5, $il->getSize());
    }

    public function testInsertAt()
    {
        $il = new IndexedListLarge(1, 3, 4, 7, 10);
        $il->insertAt(1, 5);
        $this->assertEquals(
            [1, 5, 3, 4, 7, 10],
            $il->toArray()
        );
    }

    public function testDeleteAt()
    {
        $il = new IndexedListLarge(1, 3, 4, 7, 10);
        $il->deleteAt(1);
        $this->assertEquals(
            [1, 4, 7, 10],
            $il->toArray()
        );
    }

    public function testReadAt()
    {
        $il = new IndexedListLarge(1, 3, 4, 7, 10);
        $this->assertEquals(
            7,
            $il->readAt(3)
        );
    }
}