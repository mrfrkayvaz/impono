<?php

namespace Impono\Traits;

use Impono\Data\OperationData;
use Impono\Enums\OperationDriver;
use Impono\Enums\OperationMethod;

trait ImageManipulations {
    public function convert(string $extension): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::FORMAT,
            ['format' => $extension]
        );
        $this->fileData->setExtension($extension);

        return $this;
    }

    public function quality(int $quality): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::QUALITY,
            ['quality' => $quality]
        );

        return $this;
    }

    public function resize(int $w, $h): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::RESIZE,
            ['width' => $w, 'height' => $h]
        );

        return $this;
    }

    public function sepia(): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::SEPIA
        );

        return $this;
    }

    public function blur(int $blur): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::BLUR,
            ['blur' => $blur]
        );

        return $this;
    }

    public function brightness(int $brightness): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::BRIGHTNESS,
            ['brightness' => $brightness]
        );

        return $this;
    }

    public function width(int $width): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::WIDTH,
            ['width' => $width]
        );

        return $this;
    }

    public function height(int $height): self {
        $this->operations[] = new OperationData(
            OperationDriver::SPATIE,
            OperationMethod::HEIGHT,
            ['height' => $height]
        );

        return $this;
    }
}