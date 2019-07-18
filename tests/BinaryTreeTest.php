<?php

namespace DanBallance\DataAlgorithms\Tests;

use DanBallance\DataAlgorithms\BinaryTree;
use DanBallance\DataAlgorithms\BinaryTreeNode;
use InvalidArgumentException;

class BinaryTreeTest extends \PHPUnit\Framework\TestCase
{
    public function testInsertSingleValue()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $tree->insert($objD);
        $this->assertEquals(
            [$objD],
            $tree->toArray()
        );
        $objC = (object)['name' => 'ccc'];
        $tree->insert($objC);
        $this->assertEquals(
            [$objC, $objD],
            $tree->toArray()
        );
        $objF = (object)['name' => 'fff'];
        $tree->insert($objF);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $tree->toArray()
        );
    }

    public function testInsertMultipleValues()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $tree->insert($objD, $objC, $objF);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $tree->toArray()
        );
    }

    public function testGetSize()
    {
        $tree = $this->getTree();
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
        $obj11 = (object)['name' => 'eee', 'val' => 17];
        $obj12 = (object)['name' => 'jjj', 'val' => 5];
        $tree->insert(
            $obj1, $obj2, $obj3, $obj4, $obj5, $obj6,
            $obj7, $obj8, $obj9, $obj10, $obj11, $obj12
        );
        $this->assertEquals(
            12,
            $tree->getSize()
        );
        $tree->delete($obj1);
        $tree->delete($obj2);
        $tree->delete($obj3);
        $this->assertEquals(
            9,
            $tree->getSize()
        );

    }

    public function testSetLeftAndRight()
    {
        $tree = $this->getTree();
        $objA = (object)['name' => 'aaa'];
        $objB = (object)['name' => 'bbb'];
        $objC = (object)['name' => 'ccc'];
        $root = new BinaryTreeNode(
            $tree,
            $objB,
            $this->getFunCompare(),
            $this->getFunKeySearch()
        );
        $root->setLeft(
            new BinaryTreeNode(
                $root,
                $objA,
                $this->getFunCompare(),
                $this->getFunKeySearch()
            )
        );
        $root->setRight(
            new BinaryTreeNode(
                $root,
                $objC,
                $this->getFunCompare(),
                $this->getFunKeySearch()
            )
        );
        $tree->setRoot($root);
        $this->assertEquals(
            [$objA, $objB, $objC],
            $tree->toArray()
        );
    }

    public function testRoot()
    {
        $tree = $this->getTree();
        $objA = (object)['name' => 'aaa'];
        $objB = (object)['name' => 'bbb'];
        $tree->insert($objA);
        $this->assertEquals(
            $objA->name,
            $tree->getRoot()->getValue()->name
        );
        $this->assertFalse($tree->hasLeft());
        $this->assertFalse($tree->hasRight());
    }

    public function testDeleteSingleValueRoot()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $tree->insert($objD);
        $tree->delete($objD);
        $this->assertEquals(
            [],
            $tree->toArray()
        );
    }

    public function testDeleteSingleValueNoChildren()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $objB = (object)['name' => 'bbb'];
        $objF = (object)['name' => 'fff'];
        $objA = (object)['name' => 'aaa'];
        $objC = (object)['name' => 'ccc'];
        $objE = (object)['name' => 'eee'];
        $objG = (object)['name' => 'ggg'];
        $tree->insert($objD, $objB, $objF, $objA, $objC, $objE, $objG);
        $tree->delete($objA);  // no children
        $this->assertEquals(
            [$objB, $objC, $objD, $objE, $objF, $objG],
            $tree->toArray()
        );
    }

    public function testDeleteSingleValueOneChildItemLeftOfParent()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $objB = (object)['name' => 'bbb'];
        $objF = (object)['name' => 'fff'];
        $objA = (object)['name' => 'aaa'];
        $objE = (object)['name' => 'eee'];
        $objG = (object)['name' => 'ggg'];
        $tree->insert($objD, $objB, $objF, $objA, $objE, $objG);
        $tree->delete($objB);  // has one child - objA
        $this->assertEquals(
            [$objA, $objD, $objE, $objF, $objG],
            $tree->toArray()
        );
    }

    public function testDeleteSingleValueOneChildItemRightOfParent()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $objB = (object)['name' => 'bbb'];
        $objF = (object)['name' => 'fff'];
        $objA = (object)['name' => 'aaa'];
        $objE = (object)['name' => 'eee'];
        $tree->insert($objD, $objB, $objF, $objA, $objE);
        $tree->delete($objF);  // has one child - objE
        $this->assertEquals(
            [$objA, $objB, $objD, $objE],
            $tree->toArray()
        );
    }

    public function testDeleteSingleValueTwoChildren()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $objB = (object)['name' => 'bbb'];
        $objF = (object)['name' => 'fff'];
        $objA = (object)['name' => 'aaa'];
        $objC = (object)['name' => 'ccc'];
        $objE = (object)['name' => 'eee'];
        $objG = (object)['name' => 'ggg'];
        $tree->insert($objD, $objB, $objF, $objA, $objC, $objE, $objG);
        $tree->delete($objB);  // no children
        $this->assertEquals(
            [$objA, $objC, $objD, $objE, $objF, $objG],
            $tree->toArray()
        );
    }

    public function testInsertingInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $tree->insert($objD, $objC, $objF, 123);
    }

    public function testInsertingInvalidTypesErrorsSurpressed()
    {
        $tree = $this->getTree($throwTypeErrors = false);
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $tree->insert($objD, $objC, $objF, 123);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $tree->toArray()
        );
    }

    public function testInsertDuplicates()
    {
        $tree = $this->getTree();
        $objD = (object)['name' => 'ddd'];
        $objC = (object)['name' => 'ccc'];
        $objF = (object)['name' => 'fff'];
        $tree->insert($objD, $objC, $objF);
        $tree->insert($objD, $objF);
        $this->assertEquals(
            [$objC, $objD, $objF],
            $tree->toArray()
        );
    }

    public function testSearchByKey()
    {
        $tree = $this->getTree();
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
        $obj11 = (object)['name' => 'eee', 'val' => 17];
        $obj12 = (object)['name' => 'jjj', 'val' => 5];
        $this->assertEquals(
            null,
            $tree->searchByKey('lll')  // search on emptry tree returns null
        );
        $tree->insert(
            $obj1, $obj2, $obj3, $obj4, $obj5, $obj6,
            $obj7, $obj8, $obj9, $obj10, $obj11, $obj12
        );
        $this->assertEquals(
            108,
            $tree->searchByKey('lll')->val
        );
        $this->assertEquals(
            12,
            $tree->searchByKey('kkk')->val
        );
        $this->assertEquals(
            5,
            $tree->searchByKey('jjj')->val
        );
    }

    public function testSearch()
    {
        $tree = $this->getTree();
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
        $obj11 = (object)['name' => 'eee', 'val' => 17];
        $obj12 = (object)['name' => 'jjj', 'val' => 5];
        $this->assertEquals(
            null,
            $tree->search($obj1)  // search on emptry tree returns null
        );
        $tree->insert(
            $obj1, $obj2, $obj3, $obj4, $obj5, $obj6,
            $obj7, $obj8, $obj9, $obj10, $obj11, $obj12
        );
        $this->assertEquals(
            108,
            $tree->search($obj6)->val
        );
        $this->assertEquals(
            12,
            $tree->search($obj1)->val
        );
        $this->assertEquals(
            5,
            $tree->search($obj12)->val
        );
    }

    protected function getTree($throwTypeErrors = true)
    {
        $funTypeCheck = function ($value) {
            return is_object($value);
        };
        $funCompare = $this->getFunCompare();
        $funKeySearch = $this->getFunKeySearch();
        return new BinaryTree(
            $funTypeCheck,
            $funCompare,
            $funKeySearch,
            $throwTypeErrors
        );
    }

    protected function getFunCompare()
    {
        return function(Object $a, Object $b) {
            return strcmp($a->name, $b->name);
        };
    }

    protected function getFunKeySearch()
    {
        return function(string $key, Object $b) {
            return strcmp($key, $b->name);
        };
    }
}
