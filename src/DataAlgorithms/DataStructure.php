<?php

namespace DanBallance\DataAlgorithms;

class DataStructure
{
    protected $data = [];

    public function toArray() : array
    {
        return $this->data;
    }

    public function getSize() : int
    {
        return count($this->data);
    }
}
