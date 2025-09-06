<?php

namespace Impono\Data;

class MimeData
{
    public function __construct(
        protected string $extension,
        protected string $type,
        protected string $mime
    ) {}

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMime(): string
    {
        return $this->mime;
    }
}
