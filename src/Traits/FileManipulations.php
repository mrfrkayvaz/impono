<?php

namespace Impono\Traits;

trait FileManipulations {
    public function disk(string $disk): self {
        $this->fileData->setDisk($disk);

        return $this;
    }

    public function location(string $location): self {
        $this->fileData->setLocation($location);

        return $this;
    }
}