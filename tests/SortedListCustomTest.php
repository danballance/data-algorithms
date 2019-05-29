<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\SortedList;
use InvalidArgumentException;

class SortedListCustomTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $sortedList = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $sortedList->insert($objDog);
        $this->assertEquals(
            [$objDog],
            $sortedList->toArray()
        );
        $objCat = (object)['name' => 'cat'];
        $sortedList->insert($objCat);
        $this->assertEquals(
            [$objCat, $objDog],
            $sortedList->toArray()
        );
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objFox);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $sortedList = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversed()
    {
        $sortedList = $this->getCustomList($reversed = true);
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox);
        $this->assertEquals(
            [$objFox, $objDog, $objCat],
            $sortedList->toArray()
        );
    }

    public function testInsertMultipleValuesReversedAndReverted()
    {
        $sortedList = $this->getCustomList($reversed = true);
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox);
        $this->assertEquals(
            [$objFox, $objDog, $objCat],
            $sortedList->toArray()
        );
        $sortedList->reverse(false);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $sortedList->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $sortedList = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox);
        $sortedList->delete($objDog);
        $this->assertEquals(
            [$objCat, $objFox],
            $sortedList->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $sortedList = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox);
        $sortedList->delete($objDog, $objFox);
        $this->assertEquals(
            [$objCat],
            $sortedList->toArray()
        );
    }

    public function testDeleteAtIndex()
    {
        $sortedList = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox);
        $sortedList->deleteAt(1);
        $this->assertEquals(
            [$objCat, $objFox],
            $sortedList->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $sortedList = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox, 123);
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $sortedList = $this->getCustomList($reveresed = false, $throwTypeErrors = false);
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox, 123);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $sortedList->toArray()
        );
    }

    public function testInsertDuplicates()
    {
        $sortedList = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $sortedList->insert($objDog, $objCat, $objFox);
        $sortedList->insert($objDog, $objFox);
        $this->assertEquals(
            [$objCat, $objDog, $objDog, $objFox, $objFox],
            $sortedList->toArray()
        );
    }

    protected function getCustomList($reversed = false, $throwTypeErrors = true)
    {
        return new SortedList(
            $reversed,
            $throwTypeErrors,
            $cbTypeCheck = function ($value) {
                return is_object($value);
            },
            $cbSort = function ($a, $b) {
                return strcmp($a->name, $b->name);
            },
            $cbSortRev = function ($a, $b) {
                return strcmp($a->name, $b->name) * -1;
            }
        );
    }
}
