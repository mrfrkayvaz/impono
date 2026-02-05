<?php

namespace Impono\Services\Sources;

use Illuminate\Contracts\Container\BindingResolutionException;
use Impono\Contracts\ImponoSource;

class ImponoUrlSource implements ImponoSource {
    public function __construct(
        public string $url
    ) {}

    /**
     * @throws BindingResolutionException
     */
    public static function make(string $url): self
    {
        return app()->make(static::class, ['url' => $url]);
    }

    public function extension(): string
    {
        return pathinfo(
            parse_url($this->url, PHP_URL_PATH),
            PATHINFO_EXTENSION
        );
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