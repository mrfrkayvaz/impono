<?php

namespace Impono\Traits;

use Impono\Data\OperationData;
use Impono\Enums\OperationDriver;
use Impono\Enums\OperationMethod;

trait CompressManipulations {
    public function compress(): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::COMPRESS
        );

        return $this;
    }
}