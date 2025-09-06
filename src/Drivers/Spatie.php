<?php

namespace Impono\Drivers;

use Illuminate\Support\Facades\Storage;
use Impono\Data\FileData;
use Impono\Data\OperationData;
use Impono\Enums\OperationMethod;
use Spatie\Image\Image;

class Spatie {
    public FileData $fileData;
    public Image $image;

    public function __construct(FileData $fileData) {
        $this->fileData = $fileData;
        $path = Storage::disk($this->fileData->getDisk())->path($this->fileData->getURL());
        $this->image = Image::load($path);
    }

    /**
     * @param OperationData[] $operations
     */
    public function apply(array $operations): FileData {
        $compressOperations = array_filter($operations, fn(OperationData $operation) => $operation->getMethod() === OperationMethod::COMPRESS);
        $editOperations = array_filter($operations, fn(OperationData $operation) => $operation->getMethod() !== OperationMethod::COMPRESS);

        foreach ($editOperations as $operation) {
            $method = strtolower($operation->getMethod()->name);
            if (method_exists($this, $method)) {
                $this->{$method}(...$operation->getParams());
            }else if (method_exists($this->image, $method)) {
                $this->image->{$method}(...$operation->getParams());
            }
        }

        $this->save();

        foreach ($compressOperations as $operation) {
            $method = strtolower($operation->getMethod()->name);
            if (method_exists($this, $method)) {
                $this->{$method}(...$operation->getParams());
            }else if (method_exists($this->image, $method)) {
                $this->image->{$method}(...$operation->getParams());
            }
        }

        return $this->fileData;
    }

    public function compress(): void {
        $this->image->optimize();
    }

    public function save(): void {
        $path = Storage::disk($this->fileData->getDisk())->path($this->fileData->getURL());
        $this->image->save($path);
    }
}