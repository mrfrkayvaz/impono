<?php

namespace Impono\Services;

use Illuminate\Support\Facades\Storage;
use Impono\Contracts\ImponoSource;
use Impono\Data\FileData;

class StoreService {
    public static function getFileData(ImponoSource $source): FileData {
        $tempFile = self::getTempFile($source);
        $fileData = new FileData();

        $fileData
            ->setExtension($source->extension()->getExtension())
            ->setFilename($source->filename())
            ->setTempFile($tempFile);

        return $fileData;
    }

    public static function getTempFile(ImponoSource $source): string {
        $stream = $source->content();
        $tempPath = rtrim(config('impono.temp_path', 'impono/tmp'), '/');
        $extension = $source->extension()->getExtension();
        $filename = $source->filename() . '_' . uniqid() . '.' . $extension;

        $path = $tempPath . '/' . $filename;;

        Storage::disk('local')->put($path, $stream);
        fclose($stream);

        return $path;
    }
}