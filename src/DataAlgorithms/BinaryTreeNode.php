<?php

namespace DanBallance\DataAlgorithms;

class BinaryTreeNode implements BinaryTreeNodeInterface
{
    protected $parent;
    protected $left;
    protected $right;
    protected $value;
    protected $funCompare;
    protected $funKeySearch;

    public function __construct(
        BinaryTreeNodeInterface $parent,
        $value,
        callable $funCompare,
        callable $funKeySearch
    ) {
        $this->parent = $parent;
        $this->value = $value;
        $this->funCompare = $funCompare;
        $this->funKeySearch = $funKeySearch;
    }

    public function getParent() : BinaryTreeNodeInterface
    {
        return $this->parent;
    }

    public function setParent( BinaryTreeNodeInterface $node)
    {
        $this->parent = $node;
    }

    public function hasRight() : bool
    {
        return !is_null($this->right);
    }

    public function hasleft() : bool
    {
        return !is_null($this->left);
    }

    public function deleteChild(BinaryTreeNode $item)
    {
        $compare = $this->funCompare;
        if ($this->hasLeft() && $this->equal($item, $this->getLeft())) {
            return $this->deleteLeft();
        }
        if ($this->hasRight() && $this->equal($item, $this->getRight())) {
            return $this->deleteRight();
        }  
    }

    public function swapChild(BinaryTreeNode $item, BinaryTreeNode $child)
    {
        $left = false;
        if ($this->hasLeft() && $this->equal($item, $this->getLeft())) {
            $left = true;
        }
        $this->deleteChild($item);
        if ($left) {
            $this->left = $child;
        } else {
            $this->right = $child;
        }
        $child->setParent($this);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function setLeft($item)
    {
        $this->left = $item;
        $item->setParent($this);
    }

    public function setRight($item)
    {
        $this->right = $item;
        $item->setParent($this);
    }

    public function childCount()
    {
        if ($this->hasLeft() && $this->hasRight()) {
            return 2;
        } elseif ($this->hasLeft() || $this->hasRight()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function insert($item)
    {
        $compare = $this->funCompare;
        $result = $compare($item, $this->getValue());
        if ($result < 0 && $this->left) {
            $this->left->insert($item);
        } elseif ($result < 0) {
            $this->left = new BinaryTreeNode(
                $this,
                $item,
                $this->funCompare,
                $this->funKeySearch
            );
        } elseif ($result > 0 && $this->right) {
            $this->right->insert($item);
        } elseif ($result > 0) {
            $this->right = new BinaryTreeNode(
                $this,
                $item,
                $this->funCompare,
                $this->funKeySearch
            );
        }
        // if result=0 then do nothing, we won't insert duplicates
    }

    public function delete($item)
    {
        $item = $this->search($item, $returnNode = true);
        switch ($item->childCount()) {
        case 0:
            $item->getParent()->deleteChild($item); 
            break;
        case 1:
            $item->getParent()->swapChild($item, $item->getOnlyChild());
            break;
        case 2:
            $successor = $item->getSuccessor();
            $leftChild = $item->getLeft();
            if (!$this->equal($successor, $leftChild)) {
                $successor->setLeft($leftChild);
            }            
            $rightChild = $item->getRight();
            if (!$this->equal($successor, $rightChild)) {
                $successor->setRight($rightChild);
            }
            $item->getParent()->swapChild($item, $successor);
            break;
        }
        unset($item);
    }

    public function equal($a, $b)
    {
        $compare = $this->funCompare;
        return $compare($a->getValue(), $b->getValue()) === 0;
    }

    public function getOnlyChild()
    {
        if ($this->hasLeft() && $this->hasRight()) {
            return null;
        } elseif ($this->hasLeft()) {
            return $this->getLeft();
        } elseif ($this->hasRight()) {
            return $this->getRight();
        }
    }

    public function getSuccessor()
    {
        if ($this->hasRight()) {
            return $this->getRight()->leftMost();
        }
    }

    public function leftMost()
    {
        if ($this->hasLeft()) {
            return $this->getLeft()->leftMost();
        }
        return $this;
    }

    public function rightMost()
    {
        if ($this->hasRight()) {
            return $this->getRight()->rightMost();
        }
        return $this;
    }

    public function deleteLeft()
    {
        $this->left = null;
    }

    public function deleteRight()
    {
        $this->right = null;
    }

    public function searchByKey($key, $returnNode = false)
    {
        $search = $this->funKeySearch;
        return $this->find($key, $search, 'searchByKey', $returnNode);
    }

    public function search($item, $returnNode = false)
    {
        $compare = $this->funCompare;
        return $this->find($item, $compare, 'search', $returnNode);
    }

    protected function find($item, callable $findFunc, string $searchFunc, $returnNode = false)
    {
        $result = $findFunc($item, $this->getValue());
        if ($result < 0 && $this->left) {
            return $this->left->$searchFunc($item, $returnNode);
        } elseif ($result < 0) {
            return null;
        } elseif ($result > 0 && $this->right) {
            return $this->right->$searchFunc($item, $returnNode);
        } elseif ($result > 0) {
            return null;
        } else {  // 0 means a match
            return ($returnNode) ? $this : $this->getValue();
        }
    }
}
