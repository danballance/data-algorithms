<?php

namespace DanBallance\DataAlgorithms;

use InvalidArgumentException;

class SortedListNumeric extends SortedList
{
    public function __construct(
        bool $reversed = false,
        bool $throwTypeErrors = true,
        callable $cbTypeCheck = null,
        callable $cbCompare = null,
        callable $cbSort = null,
        callable $cbSortRev = null
    ) {
        parent::__construct(
            $reversed,
            $throwTypeErrors,
            $cbTypeCheck,
            $cbCompare,
            $cbSort,
            $cbSortRev
        );
        if (!$this->cbTypeCheck) {
            $this->cbTypeCheck = function ($value) {
                return is_numeric($value);
            };
        }
        if (!$this->cbCompare) {
            $this->cbCompare = function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            };
        }
        if (!$this->cbSort) {
            $this->cbSort = function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            };
        }
        if (!$this->cbSortRev) {
            $this->cbSortRev = function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                return ($a > $b) ? -1 : 1;
            };
        }
    }
}
