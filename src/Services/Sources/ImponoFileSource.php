<?php

namespace Impono\Services\Sources;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\UploadedFile;
use Impono\Contracts\ImponoSource;
use Impono\Data\MimeData;
use Impono\Support\MimeRegistry;

class ImponoFileSource implements ImponoSource {
    public function __construct(
        public UploadedFile $file,
        public MimeRegistry $mimeRegistry
    ) {}

    /**
     * @throws BindingResolutionException
     */
    public static function make(UploadedFile $file): self
    {
        return app()->make(static::class, ['file' => $file]);
    }

    public function mimeData(): ?MimeData
    {
        $extension = $this->file->getClientOriginalExtension();
        $mimeData = $this->mimeRegistry->getByExtension($extension);

        if (!$mimeData) {
            throw new \InvalidArgumentException('Unsupported mime type');
        }

        return $mimeData;
    }

    public function filename(): string {
        return pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
    }

    public function source(): string
    {
        return md5_file($this->file->getRealPath());
    }

    /**
     * @return resource
     */
    public function content()
    {
        $path = $this->file->getRealPath();

        $stream = @fopen($path, 'r');
        if (!$stream) {
            throw new \RuntimeException("Failed to open file: {$path}");
        }

        return $stream;
    }
}