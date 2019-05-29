<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\OrderedList;
use InvalidArgumentException;

class OrderedListCustomTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $ol = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $ol->insert($objDog);
        $this->assertEquals(
            [$objDog],
            $ol->toArray()
        );
        $objCat = (object)['name' => 'cat'];
        $ol->insert($objCat);
        $this->assertEquals(
            [$objCat, $objDog],
            $ol->toArray()
        );
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objFox);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $ol = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValuesReversed()
    {
        $ol = $this->getCustomList($reversed = true);
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox);
        $this->assertEquals(
            [$objFox, $objDog, $objCat],
            $ol->toArray()
        );
    }

    public function testInsertMultipleValuesReversedAndReverted()
    {
        $ol = $this->getCustomList($reversed = true);
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox);
        $this->assertEquals(
            [$objFox, $objDog, $objCat],
            $ol->toArray()
        );
        $ol->reverse(false);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $ol->toArray()
        );
    }

    public function testDeleteSingleValue()
    {
        $ol = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox);
        $ol->delete($objDog);
        $this->assertEquals(
            [$objCat, $objFox],
            $ol->toArray()
        );
    }

    public function testDeleteMultipleValues()
    {
        $ol = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox);
        $ol->delete($objDog, $objFox);
        $this->assertEquals(
            [$objCat],
            $ol->toArray()
        );
    }

    public function testDeleteAtIndex()
    {
        $ol = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox);
        $ol->deleteAt(1);
        $this->assertEquals(
            [$objCat, $objFox],
            $ol->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $ol = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox, 123);
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $ol = $this->getCustomList($reveresed = false, $throwTypeErrors = false);
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox, 123);
        $this->assertEquals(
            [$objCat, $objDog, $objFox],
            $ol->toArray()
        );
    }

    protected function getCustomList($reversed = false, $throwTypeErrors = true)
    {
        return new OrderedList(
            $reversed,
            $throwTypeErrors,
            $cbTypeCheck = function($value) {
                return is_object($value);
            },
            $cbSort = function($a, $b) {
                return strcmp($a->name, $b->name);
            },
            $cbSortRev = function($a, $b) {
                return strcmp($a->name, $b->name) * -1;
            }
        );
    }

    public function testInsertDuplicates()
    {
        $ol = $this->getCustomList();
        $objDog = (object)['name' => 'dog'];
        $objCat = (object)['name' => 'cat'];
        $objFox = (object)['name' => 'fox'];
        $ol->insert($objDog, $objCat, $objFox);
        $ol->insert($objDog, $objFox);
        $this->assertEquals(
            [$objCat, $objDog, $objDog, $objFox, $objFox],
            $ol->toArray()
        );
    }
}
