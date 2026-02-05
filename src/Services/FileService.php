<?php

namespace Impono\Services;

use Illuminate\Support\Facades\Storage;
use Impono\Contracts\ImponoSource;
use Impono\Data\FileData;
use Impono\Traits\ImageManipulations;
use Impono\Traits\FileManipulations;
use Impono\Traits\CompressManipulations;

class FileService {
    use FileConditionable;
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
        $path = Storage::disk('local')->path($this->fileData->getPath());
        [$width,] = getimagesize($path);
        return $width;
    }

    public function getHeight(): int {
        $path = Storage::disk('local')->path($this->fileData->getPath());
        [, $height] = getimagesize($path);
        return $height;
    }

    public function getFileSize(): int {
        $path = Storage::disk('local')->path($this->fileData->getPath());
        return filesize($path);
    }

    public function push(?string $target = null, array|string $options = []): FileData {
        $this->fileData = ManipulationService::apply($this->operations, $this->fileData);

        $filename = $target
            ? pathinfo($target, PATHINFO_FILENAME)
            : $this->fileData->getFilename();

        $extension = pathinfo($target, PATHINFO_EXTENSION);
        $extension = $extension ?: $this->fileData->getExtension();

        $source = $this->fileData->getPath();
        $stream = Storage::disk('local')->readStream($source);

        $path = $this->fileData->getLocation(). '/' . $filename . '.' . $extension;
        Storage::disk($this->fileData->getDisk())->put($path, $stream, $options);

        $url = Storage::disk($this->fileData->getDisk())->url($path);
        $this->fileData->push($filename, $extension, $path, $url);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $this->fileData;
    }
}