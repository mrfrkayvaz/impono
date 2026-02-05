<?php

namespace Impono\Services;

use Impono\Traits\Conditionable;

trait FileConditionable {
    use Conditionable;

    public function whenExtension($extension, callable $callback) {
        return $this->when($this->getSource()->extension() === $extension, $callback);
    }

    public function whenExtensionIn(array $extensions, callable $callback) {
        $condition = in_array($this->getSource()->extension(), $extensions);
        return $this->when($condition, $callback);
    }
}