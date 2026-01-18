<?php

namespace Impono\Services;

use Illuminate\Support\Facades\Storage;
use Impono\Contracts\ImponoSource;
use Impono\Data\FileData;
use Impono\Traits\ImageManipulations;
use Impono\Traits\FileManipulations;
use Impono\Traits\CompressManipulations;

class FileService {
    use FileManipulations;
    use ImageManipulations;
    use CompressManipulations;

    public array $operations = [];

    public function __construct(
        public ImponoSource $source,
        public FileData $fileData
    ) {}

    public function getSource(): ImponoSource {
        return $this->source;
    }

    public function getWidth(): int {
        $path = Storage::disk('local')->path($this->fileData->getURL());
        [$width,] = getimagesize($path);
        return $width;
    }

    public function getHeight(): int {
        $path = Storage::disk('local')->path($this->fileData->getURL());
        [, $height] = getimagesize($path);
        return $height;
    }

    public function getFileSize(): int {
        $path = Storage::disk('local')->path($this->fileData->getURL());
        return filesize($path);
    }

    public function push(?string $target = null, array|string $options = []): FileData {
        $this->fileData = ManipulationService::apply($this->operations, $this->fileData);

        $filename = $target
            ? pathinfo($target, PATHINFO_FILENAME)
            : $this->fileData->getFilename();
        $extension = $target
            ? pathinfo($target, PATHINFO_EXTENSION)
            : $this->fileData->getExtension();

        $source = $this->fileData->getURL();
        $stream = Storage::disk('local')->readStream($source);

        $path = $this->fileData->getLocation(). '/' . $filename . '.' . $extension;
        Storage::disk($this->fileData->getDisk())->put($path, $stream, $options);

        $this->fileData->push($filename, $extension, $path);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $this->fileData;
    }
}