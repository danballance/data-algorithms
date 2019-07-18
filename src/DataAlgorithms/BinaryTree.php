<?php

namespace DanBallance\DataAlgorithms;

use InvalidArgumentException;

class BinaryTree implements DataStructureInterface, BinaryTreeNodeInterface
{
    protected $root;
    protected $funTypeCheck;
    protected $funCompare;  // comapare two values from the tree - used by search, delete
    protected $funKeySearch;   // compare a value from the tree against a search key - used by searchByKey, deleteByKey
    protected $throwTypeErrors;
    protected $size = 0;

    public function __construct(
        callable $funTypeCheck = null,
        callable $funCompare = null,
        callable $funKeySearch = null,
        bool $throwTypeErrors = true
    ) {
        $this->funTypeCheck = $funTypeCheck;
        $this->funCompare = $funCompare;
        $this->funKeySearch = $funKeySearch;
        $this->throwTypeErrors = $throwTypeErrors;
    }

    public function hasLeft() : bool
    {
        return false;
    }

    public function hasRight() : bool
    {
        return false;
    }

    public function deleteChild(BinaryTreeNode $item)
    {
        $this->root = null;
    }

    public function swapChild(BinaryTreeNode $item, BinaryTreeNode $child)
    {
        $this->root = $child;
    }

    public function getRoot() : BinaryTreeNode
    {
        return $this->root;
    }

    public function setRoot(BinaryTreeNode $root)
    {
        $this->root = $root;
    }

    public function toArray() : array
    {
        if (!$this->root) {
            return [];
        }
        $recursiveWalk = function(BinaryTreeNode $node, array $accumulator = []) use (&$recursiveWalk) {
            if ($node->hasLeft()) {
                $accumulator = $recursiveWalk($node->getLeft(), $accumulator);
            }
            $accumulator[] = $node->getValue();
            if ($node->hasRight()) {
                $accumulator = $recursiveWalk($node->getRight(), $accumulator);
            }
            return $accumulator;
        };
        return $recursiveWalk($this->root);
    }

    public function __toString()
    {
        if (!$this->root) {
            return "null\n";
        }
        $recursivePrint = function(array $queue, string $accumulator = "") use (&$recursivePrint) {
            $nextQueue = [];
            foreach ($queue as $node) {
                $accumulator .=  " {$node->getValue()->name} ";
                if ($node->hasLeft()) {
                    $nextQueue[] = $node->getLeft();
                }
                if ($node->hasRight()) {
                    $nextQueue[] = $node->getRight();
                }
            }
            $accumulator .= "\n";
            if (empty($nextQueue)) {
                return $accumulator;
            }
            return $recursivePrint($nextQueue, $accumulator);
        };
        return $recursivePrint([$this->root]);
    }

    public function getSize() : int
    {
        return $this->size;
    }

    public function insert(...$values) : BinaryTree
    {
        foreach ($values as $value) {
            $this->insertItem($value);
        }
        return $this;
    }

    public function delete(...$values) : BinaryTree
    {
        foreach ($values as $value) {
            $this->deleteItem($value);
        }
        return $this;
    }

    public function search($item)
    {
        if (!$this->root) {
            return null;
        }
        return $this->root->search($item);
    }

    public function searchByKey($key)
    {
        if (!$this->root) {
            return null;
        }
        return $this->root->searchByKey($key);
    }

    protected function insertItem($item)
    {
        $typeCheck = $this->funTypeCheck;
        if (!$typeCheck($item)) {
            if ($this->throwTypeErrors) {
                $err = "Value is not correct type.";
                throw new InvalidArgumentException($err);
            } else {
                return;
            }
        }
        if (!$this->root) {
            $this->root = new BinaryTreeNode(
                $this,
                $item,
                $this->funCompare,
                $this->funKeySearch
            );
        } else {
            $this->root->insert($item);
        }
        $this->size++;
    }

    protected function deleteItem($item)
    {
        if ($this->root) {
            $this->root->delete($item);
            $this->size--;
        }
    }
}
