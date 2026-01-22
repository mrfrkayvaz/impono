<?php

namespace Impono\Data;

use Impono\Support\MimeRegistry;

class FileData {
    private ?string $path = null;
    private ?string $url = null;
    private bool $is_temp = false;
    private ?string $disk;
    private ?string $location;
    private ?string $filename;
    private ?string $extension;
    private ?MimeData $mimeData;

    public function __construct(
        public MimeRegistry $mimeRegistry
    ) {
        $this->disk = config('filesystems.default', 'local');
        $this->filename = pathinfo($this->path, PATHINFO_FILENAME);
        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION);
        $this->mimeData = $this->mimeRegistry->getByExtension($this->extension);
        $this->location = null;
    }

    public function getURL(): ?string
    {
        return $this->url;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getIsTemp(): bool {
        return $this->is_temp;
    }

    public function getDisk(): string {
        return $this->disk;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getFilename(): string {
        return $this->filename;
    }

    public function getExtension(): string {
        return $this->extension;
    }

    public function getMimeData(): MimeData {
        return $this->mimeData;
    }

    public function setTempFile(string $tempFile): self {
        $this->path = $tempFile;
        $this->is_temp = true;
        return $this;
    }

    public function setDisk(string $disk): self {
        $this->disk = $disk;
        return $this;
    }

    public function setLocation(string $location): self {
        $this->location = $location;
        return $this;
    }

    public function setFilename(string $filename): self {
        $this->filename = $filename;
        return $this;
    }

    public function setExtension(string $extension): self {
        $this->extension = $extension;
        return $this;
    }

    public function setMimeData(MimeData $mimeData): self {
        $this->mimeData = $mimeData;
        return $this;
    }

    public function push(
        $filename,
        $extension,
        $path,
        $url
    ): self {
        $this->filename = $filename;
        $this->extension = $extension;
        $this->mimeData = $this->mimeRegistry->getByExtension($extension);
        $this->path = $path;
        $this->url = $url;
        $this->is_temp = false;

        return $this;
    }
}