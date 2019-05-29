<?php

namespace DanBallance\DataAlgorithms;

use InvalidArgumentException;

class SortedList extends DataStructure
{
    protected $reversed = false;
    protected $throwTypeErrors = true;
    protected $cbTypeCheck = null;
    protected $cbSort = null;
    protected $cbSortRev = null;

    public function __construct(
        bool $reversed = false,
        bool $throwTypeErrors = true,
        callable $cbTypeCheck = null,
        callable $cbSort = null,
        callable $cbSortRev = null
    ) {
        $this->reversed = $reversed;
        $this->throwTypeErrors = $throwTypeErrors;
        $this->cbTypeCheck = $cbTypeCheck;
        $this->cbSort = $cbSort;
        $this->cbSortRev = $cbSortRev;
    }

    public function reverse(bool $on = true)
    {
        $this->reversed = $on;
        $this->sort();
    }

    public function insert(...$values)
    {
        $values = $this->filter($values);
        if (count($values) == 1) {
            $this->data[] = $values[0];
        } else {
            $this->data = array_merge(
                $this->data,
                $values
            );
        }
        $this->sort();
    }

    public function delete(...$values)
    {
        $this->data = array_filter(
            $this->data,
            function ($value) use ($values) {
                return !in_array($value, $values);
            }
        );
        $this->sort();
    }

    public function deleteAt(int $index)
    {
        unset($this->data[$index]);
        $this->sort();
    }

    protected function filter(array $data = [])
    {
        $throwTypeErrors = $this->throwTypeErrors;
        $typeCheck = $this->cbTypeCheck;
        return array_values(
            array_filter(
                $data,
                function ($value) use ($typeCheck, $throwTypeErrors) {
                    $correctType = $typeCheck($value);
                    if ($throwTypeErrors && !$correctType) {
                        $err = "Value of '{$value}' is not correct type.";
                        throw new InvalidArgumentException($err);
                    }
                    return $correctType;
                }
            )
        );
    }

    protected function sort()
    {
        if ($this->reversed) {
            return usort($this->data, $this->cbSortRev);
        }
        return usort($this->data, $this->cbSort);
    }
}
