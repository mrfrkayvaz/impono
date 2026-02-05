<?php

namespace Impono\Data;

readonly class MimeData
{
    public function __construct(
        protected string $extension
    ) {}

    public function getExtension(): string
    {
        return $this->extension;
    }
}
