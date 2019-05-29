<?php 

namespace DanBallance\DataAlgorithms;

use SplFixedArray;

class IndexedList extends DataStructure
{
    public function __construct(...$data)
    {
        $this->data = array_values($data);
    }

    public function insertAt(int $index, $value)
    {
        $this->data = array_merge(
            array_slice($this->data, 0, $index, true),
            [$index => $value],
            array_slice($this->data, $index, null, true)
        );
    }

    public function deleteAt(int $index)
    {
        unset($this->data[$index]);
        $this->data = array_values($this->data);
    }

    public function readAt(int $index)
    {
        return $this->data[$index];
    }
}
