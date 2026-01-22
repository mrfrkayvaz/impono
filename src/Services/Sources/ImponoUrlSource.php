<?php

namespace Impono\Services\Sources;

use Illuminate\Contracts\Container\BindingResolutionException;
use Impono\Contracts\ImponoSource;
use Impono\Data\MimeData;
use Impono\Support\MimeRegistry;

class ImponoUrlSource implements ImponoSource {
    public function __construct(
        public string $url,
        public MimeRegistry $mimeRegistry
    ) {}

    /**
     * @throws BindingResolutionException
     */
    public static function make(string $url): self
    {
        return app()->make(static::class, ['url' => $url]);
    }

    public function mimeData(): ?MimeData
    {
        $extension = pathinfo(
            parse_url($this->url, PHP_URL_PATH),
            PATHINFO_EXTENSION
        );
        $mimeData = $this->mimeRegistry->getByExtension($extension);

        if (!$mimeData) {
            throw new \InvalidArgumentException('Unsupported mime type');
        }

        return $mimeData;
    }

    public function filename(): string {
        $filename = basename(
            parse_url($this->url, PHP_URL_PATH)
        );

        return pathinfo($filename, PATHINFO_FILENAME);
    }

    public function source(): string
    {
        return $this->url;
    }

    /**
     * @return resource
     */
    public function content()
    {
        $timeout = 30;

        $stream = @fopen($this->url, 'r', false, stream_context_create([
            'http' => ['timeout' => $timeout],
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
        ]));

        if (!$stream) {
            throw new \RuntimeException("Failed to download from URL: {$this->url}");
        }

        return $stream;
    }
}