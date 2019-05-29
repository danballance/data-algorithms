<?php

namespace DanBallance\DataAlgorithms;

use InvalidArgumentException;

class OrderedListNumeric extends OrderedList
{
    public function __construct(
        bool $reversed = false,
        bool $throwTypeErrors = true,
        callable $cbTypeCheck = null,
        callable $cbSort = null,
        callable $cbSortRev = null
    ) {
        parent::__construct(
            $reversed,
            $throwTypeErrors,
            $cbTypeCheck,
            $cbSort,
            $cbSortRev
        );
        if (!$this->cbTypeCheck) {
            $this->cbTypeCheck = function ($value) {
                return is_numeric($value);
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
