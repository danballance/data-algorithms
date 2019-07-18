<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\IndexedList;

class IndexedListTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertAt()
    {
        $il = new IndexedList(1, 3, 4, 7, 10);
        $il->insertAt(1, 5);
        $this->assertEquals(
            [1, 5, 3, 4, 7, 10],
            $il->toArray()
        );
    }

    public function testDeleteAt()
    {
        $il = new IndexedList(1, 3, 4, 7, 10);
        $il->deleteAt(1);
        $this->assertEquals(
            [1, 4, 7, 10],
            $il->toArray()
        );
    }

    public function testReadAt()
    {
        $il = new IndexedList(1, 3, 4, 7, 10);
        $this->assertEquals(
            7,
            $il->readAt(3)
        );
    }
}