<?php

namespace Impono\Support;

use Impono\Constants;
use Impono\Data\MimeData;

class MimeRegistry
{
    public array $mimes = [];

    public function __construct(public array $extensions)
    {
        collect(Constants::$mimes)->filter(
            fn ($item) => in_array($item['extension'], $extensions, true)
        )->each(function ($mime) {
            $mimeData = new MimeData(
                $mime['extension'],
                $mime['type'],
                $mime['mime']
            );

            $this->mimes[strtolower($mimeData->getExtension())] = $mimeData;
        });
    }

    public function getByExtension(string $extension): ?MimeData
    {
        return $this->mimes[strtolower($extension)] ?? null;
    }

    public function getByMime(string $mime): ?MimeData
    {
        foreach ($this->mimes as $item) {
            if ($item->getMime() === $mime) {
                return $item;
            }
        }
        return null;
    }

    /** @return MimeData[] */
    public function all(): array
    {
        return array_values($this->mimes);
    }
}
