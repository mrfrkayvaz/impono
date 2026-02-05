<?php

namespace Impono\Services\Sources;

use Illuminate\Contracts\Container\BindingResolutionException;
use Impono\Contracts\ImponoSource;
use Symfony\Component\Mime\MimeTypes;

class ImponoDataSource implements ImponoSource {
    public function __construct(
        public string $data,
    ) {
        if (! preg_match('/^data:image\/[a-zA-Z0-9.+-]+;base64,/', $this->head())) {
            throw new \InvalidArgumentException('Only base64 encoded images are allowed.');
        }
    }

    /**
     * @throws BindingResolutionException
     */
    public static function make(string $data): self
    {
        return app()->make(static::class, ['data' => $data]);
    }

    private function head(): string {
        return substr($this->data, 0, 100);
    }

    public function extension(): string
    {
        if (! preg_match('/^data:([^;]+);base64,/', $this->head(), $matches)) {
            throw new \InvalidArgumentException('Unsupported mime type');
        }

        $mime = $matches[1];

        $mimeTypes = new MimeTypes();
        $extensions = $mimeTypes->getExtensions($mime);
        $extension = $extensions[0] ?? null;

        if (!$extension) {
            throw new \InvalidArgumentException('Unsupported mime type: ' . $mime);
        }

        return $extension;
    }

    public function filename(): string {
        return "";
    }

    public function source(): string
    {
        return md5($this->data);
    }

    /**
     * @return resource
     */
    public function content() {
        if (!preg_match('/^data:(.*?);base64,(.*)$/', $this->head(), $matches)) {
            throw new \InvalidArgumentException("Invalid data URI format");
        }

        $binary = base64_decode($matches[2], true);
        if ($binary === false) {
            throw new \RuntimeException("Failed to decode base64 data");
        }

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $binary);
        rewind($stream);

        return $stream;
    }
}