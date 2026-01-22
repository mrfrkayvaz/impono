<?php

namespace Impono\Data;

use Impono\Enums\OperationDriver;
use Impono\Enums\OperationMethod;

readonly class OperationData {
    public function __construct(
        public OperationDriver $driver,
        public OperationMethod $method,
        public array           $params = []
    ) {}

    public function getDriver(): OperationDriver {
        return $this->driver;
    }

    public function getMethod(): OperationMethod {
        return $this->method;
    }

    public function getParams(): array {
        return $this->params;
    }
}