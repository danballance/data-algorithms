<?php

namespace DanBallance\DataAlgorithms;

class OrderedSet extends DataStructure
{
    public function insert(...$values)
    {
        $this->data = array_merge(
            $this->data,
            $values
        );
        $this->data = array_values(array_unique($this->data));
    }

    public function delete(...$values)
    {
        $this->data = array_filter(
            $this->data,
            function ($value) use ($values) {
                return !in_array($value, $values);
            }
        );
        $this->data = array_values($this->data);
    }
}
