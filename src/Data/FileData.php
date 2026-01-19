<?php

namespace Impono\Data;

class FileData {
    private ?string $path = null;
    private ?string $url = null;
    private bool $is_temp = false;
    private ?string $disk;
    private ?string $location;
    private ?string $filename;
    private ?string $extension;

    public function __construct() {
        $this->disk = config('filesystems.default', 'local');
        $this->filename = pathinfo($this->url, PATHINFO_FILENAME);
        $this->extension = pathinfo($this->url, PATHINFO_EXTENSION);
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

    public function push(
        $filename,
        $extension,
        $path,
        $url
    ): self {
        $this->filename = $filename;
        $this->extension = $extension;
        $this->path = $path;
        $this->url = $url;
        $this->is_temp = false;

        return $this;
    }
}