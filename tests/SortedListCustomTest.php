<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\SortedList;
use InvalidArgumentException;

class SortedListCustomTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $sortedList = $this->getCustomList();
        $objD = (object)['name' => 'ddd'];
        $sortedList->insert($objD);
        $this->assertEquals(
            [$objD],
            $sortedList->toArray()
        );
        $objC = (object)['name' => 'ccc'];
        $sortedList->insert($objC);
        $this->assertEquals(
            [$objC, $objD],
            $sortedList->toArray()
        );
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objF);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $sortedList = $this->getCustomList();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversed()
    {
        $sortedList = $this->getCustomList($reversed = true);
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF);
        $this->assertEquals(
            [$objF, $objD, $objC],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversedAndReverted()
    {
        $sortedList = $this->getCustomList($reversed = true);
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF);
        $this->assertEquals(
            [$objF, $objD, $objC],
            $sortedList->toArray()
        );
        $sortedList->reverse(false);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $sortedList->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $sortedList = $this->getCustomList();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF);
        $sortedList->delete($objD);
        $this->assertEquals(
            [$objC, $objF],
            $sortedList->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $sortedList = $this->getCustomList();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF);
        $sortedList->delete($objD, $objF);
        $this->assertEquals(
            [$objC],
            $sortedList->toArray()
        );
    }

    public function testDeleteAtIndex()
    {
        $sortedList = $this->getCustomList();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF);
        $sortedList->deleteAt(1);
        $this->assertEquals(
            [$objC, $objF],
            $sortedList->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $sortedList = $this->getCustomList();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF, 123);
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $sortedList = $this->getCustomList($reveresed = false, $throwTypeErrors = false);
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF, 123);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $sortedList->toArray()
        );
    }

    public function testInsertDuplicates()
    {
        $sortedList = $this->getCustomList();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $sortedList->insert($objD, $objC, $objF);
        $sortedList->insert($objD, $objF);
        $this->assertEquals(
            [$objC, $objD, $objD, $objF, $objF],
            $sortedList->toArray()
        );
    }

    public function testSearch()
    {
        $sortedList = $this->getCustomList();
        $obj1 = (object)['name' => 'kkk', 'val' => 12];
        $obj2 = (object)['name' => 'aaa', 'val' => 18];
        $obj3 = (object)['name' => 'iii', 'val' => 4];
        $obj4 = (object)['name' => 'ccc', 'val' => 11];
        $obj5 = (object)['name' => 'ddd', 'val' => 180];
        $obj6 = (object)['name' => 'lll', 'val' => 108];
        $obj7 = (object)['name' => 'hhh', 'val' => 123];
        $obj8 = (object)['name' => 'bbb', 'val' => 7];
        $obj9 = (object)['name' => 'fff', 'val' => 21];
        $obj10 = (object)['name' => 'ggg', 'val' => 42];
        $obj11 = (object)['name' => 'eae', 'val' => 17];
        $obj12 = (object)['name' => 'jjj', 'val' => 5];
        $sortedList->insert(
            $obj1, $obj2, $obj3, $obj4, $obj5, $obj6,
            $obj7, $obj8, $obj9, $obj10, $obj11, $obj12
        );
        $this->assertEquals(
            108,
            $sortedList->search('lll')->val
        );
        $this->assertEquals(
            12,
            $sortedList->search('kkk')->val
        );
        $this->assertEquals(
            5,
            $sortedList->search('jjj')->val
        );
    }

    protected function getCustomList($reversed = false, $throwTypeErrors = true)
    {
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
        return new SortedList(
            $reversed,
            $throwTypeErrors,
            $cbTypeCheck,
            $cbCompare,
            $cbSort,
            $cbSortRev
        );
    }
}
