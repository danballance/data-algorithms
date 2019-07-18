<?php

namespace DanBallance\DataAlgorithms;

interface DataStructureInterface
{
    public function toArray() : array;
    public function getSize() : int;
    public function insert(...$values);
    public function delete(...$values);
    public function search($key);
}
