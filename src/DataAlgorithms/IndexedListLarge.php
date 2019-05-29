<?php

namespace DanBallance\DataAlgorithms;

use SplFixedArray;

class IndexedListLarge
{
    protected $data;

    public function __construct(...$data)
    {
        $this->data = SplFixedArray::fromArray($data);
    }

    public function toArray() : array
    {
        return $this->data->toArray();
    }

    public function getSize() : int
    {
        return $this->data->getSize();
    }

    public function insertAt(int $index, $value)
    {
        $size = $this->getSize();
        $this->data->setSize($size + 1);
        for ($i = $size; $i > $index; $i--) {
            $this->data[$i] = $this->data[$i - 1];
        }
        $this->data[$index] = $value;
    }

    public function deleteAt(int $index)
    {
        $size = $this->getSize();
        for ($i = $index; $i < ($size - 1); $i++) {
            $this->data[$i] = $this->data[$i + 1];
        }
        $this->data->setSize($size - 1);
    }

    public function readAt(int $index)
    {
        return $this->data[$index];
    }
}