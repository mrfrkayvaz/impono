<?php

namespace Impono\Traits;

trait Conditionable {
    public function when($value, callable $callback)
    {
        if ($value) {
            return $callback($this, $value);
        }

        return $this;
    }
}