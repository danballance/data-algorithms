<?php

namespace DanBallance\DataAlgorithms;

class OrderedListString extends OrderedList
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
                return is_string($value);
            };
        }
        if (!$this->cbSort) {
            $this->cbSort = function ($a, $b) {
                return strcmp($a, $b);
            };
        }
        if (!$this->cbSortRev) {
            $this->cbSortRev = function ($a, $b) {
                return strcmp($a, $b) * -1;
            };
        }
    }
}
