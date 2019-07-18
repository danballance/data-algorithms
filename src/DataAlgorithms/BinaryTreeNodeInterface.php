<?php

namespace DanBallance\DataAlgorithms;

interface BinaryTreeNodeInterface
{
    public function hasLeft() : bool;
    public function hasRight() : bool;
    public function deleteChild(BinaryTreeNode $item);
    public function swapChild(
        BinaryTreeNode $item,
        BinaryTreeNode $child
    );
}
