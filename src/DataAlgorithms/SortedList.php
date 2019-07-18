<?php

namespace DanBallance\DataAlgorithms;

use InvalidArgumentException;

/**
 * Batch insert pretty fast.
 * Single insertions very slow for large data sets.
 * If the data can be passed as an array to the class then searches by key are very fast
 */
class SortedList extends DataStructure implements DataStructureInterface
{
    protected $reversed = false;
    protected $throwTypeErrors = true;
    protected $cbTypeCheck;
    protected $cbCompare;
    protected $cbSort;
    protected $cbSortRev;
    protected $validateInsert = true;
 
    public function __construct(
        bool $reversed = false,
        bool $throwTypeErrors = true,
        callable $cbTypeCheck = null,
        callable $cbCompare = null,
        callable $cbSort = null,
        callable $cbSortRev = null,
        bool $validateInsert = true
    ) {
        $this->reversed = $reversed;
        $this->throwTypeErrors = $throwTypeErrors;
        $this->cbTypeCheck = $cbTypeCheck;
        $this->cbCompare = $cbCompare;
        $this->cbSort = $cbSort;
        $this->cbSortRev = $cbSortRev;
        $this->validateInsert = $validateInsert;
    }

    public function reverse(bool $on = true)
    {
        $this->reversed = $on;
        $this->sort();
    }

    public function insert(...$values)
    {
        if ($this->validateInsert) {
            $values = $this->filter($values);
        }
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

    public function search($key)
    {
        $compare = $this->cbCompare;
        $recursiveFind = function(array $data) use ($key, &$recursiveFind, $compare) {
            if (empty($data)) {
                return null;
            }
            $midIndex = floor(count($data) / 2);
            $value = $data[$midIndex];
            $result = $compare($key, $value);
            if ($midIndex === 0 && $result !== 0) {
                return null;
            } elseif ($result > 0) {
                $data = array_slice($data, $midIndex + 1);
                return $recursiveFind($data);
            } elseif ($result < 0) {
                $data = array_slice($data, 0, ($midIndex));
                return $recursiveFind($data);
            } else {  // == 0
                return $value;
            }
        };
        return $recursiveFind($this->data);
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
