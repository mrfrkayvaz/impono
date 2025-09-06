<?php

namespace Impono\Handlers;

use Impono\Contracts\UploadHandler;
use Impono\Data\MimeData;
use Impono\Support\MimeRegistry;

class DataHandler implements UploadHandler {
    public function __construct(
        public string $data
    ) {
        if (! preg_match('/^data:image\/[a-zA-Z0-9.+-]+;base64,/', $this->head())) {
            throw new \InvalidArgumentException('Only base64 encoded images are allowed.');
        }
    }

    private function head(): string {
        return substr($this->data, 0, 100);
    }

    public function extension(): ?MimeData
    {
        if (! preg_match('/^data:([^;]+);base64,/', $this->head(), $matches)) {
            throw new \InvalidArgumentException('Unsupported mime type');
        }

        $mime = $matches[1];
        $mimeData = MimeRegistry::getByMime($mime);

        if (!$mimeData) {
            throw new \InvalidArgumentException('Unsupported mime type: ' . $mime);
        }

        return $mimeData;
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

        $binary = base64_decode($matches[2]);
        if ($binary === false) {
            throw new \RuntimeException("Failed to decode base64 data");
        }

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $binary);
        rewind($stream);

        return $stream;
    }
}