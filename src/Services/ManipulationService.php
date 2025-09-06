<?php

namespace Impono\Services;

use Impono\Data\FileData;
use Impono\Data\OperationData;
use Impono\Drivers\Spatie;
use Impono\Enums\OperationDriver;

class ManipulationService {
    /**
     * @param OperationData[] $operations
     */
    public static function apply(array $operations, FileData $fileData): FileData {
        $grouped = [];
        foreach ($operations as $operation) {
            $driver = $operation->getDriver();
            $grouped[$driver->name][] = $operation;
        }

        foreach ($grouped as $driver_name => $ops) {
            $driver = OperationDriver::from($driver_name);
            $fileData = match ($driver) {
                OperationDriver::SPATIE => (new Spatie($fileData))->apply($ops)
            };
        }

        return $fileData;
    }
}